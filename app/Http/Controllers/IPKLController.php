<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\IpklMail;
use App\Exports\IpklExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Jobs\SendNotification;
use App\Exports\LaporanIpklExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\UserNotification;

class IPKLController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Iuran Pemeliharaan dan  Keamanan Lingkungan';
        $user = request()->input('user');
        $user_id = request()->input('user_id');
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');
        $status = request()->input('status');
        $month = request()->input('month');
        $year = request()->input('year');

        $transaction_ipkl = Transaction::select('transactions.*')
        ->join('users', 'transactions.user_id', '=', 'users.id')
        ->where('type', 'IPKL')
        ->when($user, function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('users.name', 'LIKE', '%'.$user.'%')
                ->orWhere('users.alamat', 'LIKE', '%'.$user.'%');
            });
        })
        ->when($user_id, function ($query) use ($user_id) {
            $query->where('users.id', $user_id);
        })
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })
        ->when($status, function ($query) use ($status) {
            $query->where('transactions.status', $status);
        })
        ->when($month, function ($query) use ($month) {
            $query->where('transactions.month', $month);
        })
        ->when($year, function ($query) use ($year) {
            $query->where('transactions.year', $year);
        })
        ->orderBy('date', 'DESC')
        ->orderBy('users.alamat', 'ASC')
        ->paginate(10)
        ->withQueryString();

        return view('ipkl.index', compact(
            'title',
            'transaction_ipkl'
        ));
    }

    public function export()
    {
        return (new IpklExport($_GET))->download('IPKL.xlsx');
    }

    public function tambah()
    {
        $title = 'Iuran Pemeliharaan dan  Keamanan Lingkungan';
        $users = User::select('id', 'name', 'alamat', 'status')->orderBy('alamat', 'ASC')->get();

        return view('ipkl.tambah', compact(
            'title',
            'users',
        ));
    }

    public function store(Request $request)
    {
        DB::transaction(function ()  use ($request) {
            $request->validate([
                'user_id' => 'required|array|min:1',
                'type' => 'required',
                'date' => 'required',
                'nominal' => 'required',
                'expired_date' => 'required',
            ]);

            $nominal = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $user_id = $request->input('user_id', []);
            $month = date('m', strtotime($request->date));
            $month_name = Carbon::createFromFormat('m', $month)->translatedFormat('F');
            $year = date('Y', strtotime($request->date));

            $merchant_id = config('faspay.merchant_id');
            $merchant_user_id = config('faspay.merchant_user_id');
            $merchant_password = config('faspay.merchant_password');
            $merchant_post_url = config('faspay.merchant_post_url');

            for ($i = 0; $i < count($user_id); $i++) {
                if ($user_id[$i] !== null) {
                    $exists = Transaction::where('user_id', $user_id[$i])
                    ->where('month', $month)
                    ->where('year', $year)
                    ->where('type', $request->type)
                    ->exists();

                    if (!$exists) {
                        $ipkl = Transaction::create([
                            'user_id' => $user_id[$i],
                            'type' => $request->type,
                            'date' => $request->date,
                            'expired_date' => $request->expired_date,
                            'nominal' => $nominal,
                            'notes' => $request->notes,
                            'status' => 'unpaid',
                            'created_by' => auth()->user()->id,
                            'month' => $month,
                            'year' => $year,
                            'in_out' => 'in',
                        ]);
                        
                        $user = User::find($ipkl->user_id);
                        
                        $combined_data = $merchant_user_id . $merchant_password . $ipkl->id . $ipkl->nominal;
                        $md5_hash = md5($combined_data);
                        $sha1_hash = sha1($md5_hash);
                
                        $payload = [
                            "merchant_id" => $merchant_id,
                            "bill_no" => $ipkl->id,
                            "bill_date" => $ipkl->date . " " . date('H:i:s'),
                            "bill_expired" => $ipkl->expired_date . " " . date('H:i:s'),
                            "bill_gross" => $ipkl->nominal,
                            "bill_miscfee" => 0,
                            "bill_total" => $ipkl->nominal,
                            "bill_desc" => 'Pembayaran #' . $ipkl->id,
                            "cust_no" => $user->id,
                            "cust_name" => $user->name,
                            "return_url" => url('/my-ipkl/finish'),
                            "msisdn" => $user->no_hp,
                            "email" => $user->email,
                            "billing_address" => $user->alamat,
                            "item" => [
                                [
                                    "product" => $ipkl->id,
                                    "qty" => "1",
                                    "amount" => $ipkl->nominal
                                ]
                            ],
                            "button_color" => "333333",
                            "background_color" => "333333",
                            "signature" => $sha1_hash
                        ];
                
                        $response = Http::post($merchant_post_url, $payload);
                        $dataResponse = $response->object(); 
                        $ipkl->update([
                            'redirect_url' => $dataResponse->redirect_url,
                            'signature' => $sha1_hash
                        ]);
                
                        $data = [
                            'user_id' => auth()->user()->id,
                            'from' => auth()->user()->name,
                            'message' => 'IPKL (' . $month_name . ' ' . $year . ') Harap untuk segera melakukan pembayaran!',
                            'action' => '/my-ipkl/show/' . $ipkl->id
                        ];
                
                        $user->notify(new UserNotification($data));
                
                        SendNotification::dispatch($user, $ipkl, $month_name, $ipkl->expired_date, $request->nominal);
                
                        Mail::to($user->email)->send(new IpklMail($ipkl));
                    }
                }
            }
        });

        return redirect('/ipkl')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function tambahPerUser()
    {
        $title = 'Iuran Pemeliharaan dan  Keamanan Lingkungan';
        $users = User::select('id', 'name', 'alamat', 'status')->orderBy('alamat', 'ASC')->get();

        return view('ipkl.tambahPerUser', compact(
            'title',
            'users',
        ));
    }

    public function storePerUser(Request $request)
    {
        $result = null;
        DB::transaction(function ()  use ($request, $result) {
            $request->validate([
                'user_id' => 'required',
                'type' => 'required',
                'date' => 'required',
                'nominal' => 'required',
                'expired_date' => 'required',
            ]);
    
            $nominal = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $month = date('m', strtotime($request->date));
            $month_name = Carbon::createFromFormat('m', $month)->translatedFormat('F');
            $year = date('Y', strtotime($request->date));
    
            $ipkl = Transaction::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'date' => $request->date,
                'expired_date' => $request->expired_date,
                'nominal' => $nominal,
                'notes' => $request->notes,
                'status' => 'unpaid',
                'created_by' => auth()->user()->id,
                'month' => $month,
                'year' => $year,
                'in_out' => 'in',
            ]);

            $this->result = $ipkl->id;
    
            $user = User::find($ipkl->user_id);
            
            $merchant_id = config('faspay.merchant_id');
            $merchant_user_id = config('faspay.merchant_user_id');
            $merchant_password = config('faspay.merchant_password');
            $merchant_post_url = config('faspay.merchant_post_url');
            
            $combined_data = $merchant_user_id . $merchant_password . $ipkl->id . $ipkl->nominal;
            $md5_hash = md5($combined_data);
            $sha1_hash = sha1($md5_hash);
    
            $payload = [
                "merchant_id" => $merchant_id,
                "bill_no" => $ipkl->id,
                "bill_date" => $ipkl->date . " " . date('H:i:s'),
                "bill_expired" => $ipkl->expired_date . " " . date('H:i:s'),
                "bill_gross" => $ipkl->nominal,
                "bill_miscfee" => 0,
                "bill_total" => $ipkl->nominal,
                "bill_desc" => 'Pembayaran #' . $ipkl->id,
                "cust_no" => $user->id,
                "cust_name" => $user->name,
                "return_url" => url('/my-ipkl/finish'),
                "msisdn" => $user->no_hp,
                "email" => $user->email,
                "billing_address" => $user->alamat,
                "item" => [
                    [
                        "product" => $ipkl->id,
                        "qty" => "1",
                        "amount" => $ipkl->nominal
                    ]
                ],
                "button_color" => "333333",
                "background_color" => "333333",
                "signature" => $sha1_hash
            ];
    
            $response = Http::post($merchant_post_url, $payload);
            $dataResponse = $response->object(); 
            $ipkl->update([
                'redirect_url' => $dataResponse->redirect_url,
                'signature' => $sha1_hash
            ]);
    
            $data = [
                'user_id' => auth()->user()->id,
                'from' => auth()->user()->name,
                'message' => 'IPKL (' . $month_name . ' ' . $year . ') Harap untuk segera melakukan pembayaran!',
                'action' => '/my-ipkl/show/' . $ipkl->id
            ];
    
            $user->notify(new UserNotification($data));
    
            SendNotification::dispatch($user, $ipkl, $month_name, $ipkl->expired_date, $request->nominal);
    
            Mail::to($user->email)->send(new IpklMail($ipkl));
        });

        return redirect('/ipkl/show/'.$this->result)->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $title = 'Iuran Pemeliharaan dan  Keamanan Lingkungan';
        $users = User::select('id', 'name', 'alamat', 'status')->orderBy('alamat', 'ASC')->get();
        $ipkl = Transaction::find($id);

        return view('ipkl.edit', compact(
            'title',
            'users',
            'ipkl',
        ));
    }

    public function update(Request $request, $id)
    {
        $ipkl_old = Transaction::find($id);
        $result = null;
        DB::transaction(function ()  use ($request, $ipkl_old, $result) {
            $request->validate([
                'user_id' => 'required',
                'type' => 'required',
                'date' => 'required',
                'nominal' => 'required',
                'expired_date' => 'required',
                'notes' => 'nullable',
            ]);

            $nominal = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $month = date('m', strtotime($request->date));
            $month_name = Carbon::createFromFormat('m', $month)->translatedFormat('F');
            $year = date('Y', strtotime($request->date));
    
            $ipkl = Transaction::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'date' => $request->date,
                'expired_date' => $request->expired_date,
                'nominal' => $nominal,
                'notes' => $request->notes,
                'status' => $ipkl_old->status,
                'created_by' => $ipkl_old->created_by,
                'month' => $month,
                'year' => $year,
                'in_out' => $ipkl_old->in_out,
            ]);

            $this->result = $ipkl->id;

            $ipkl_old->delete();

            $user = User::find($ipkl->user_id);
            
            $merchant_id = config('faspay.merchant_id');
            $merchant_user_id = config('faspay.merchant_user_id');
            $merchant_password = config('faspay.merchant_password');
            $merchant_post_url = config('faspay.merchant_post_url');
            
            $combined_data = $merchant_user_id . $merchant_password . $ipkl->id . $ipkl->nominal;
            $md5_hash = md5($combined_data);
            $sha1_hash = sha1($md5_hash);
    
            $payload = [
                "merchant_id" => $merchant_id,
                "bill_no" => $ipkl->id,
                "bill_date" => $ipkl->date . " " . date('H:i:s'),
                "bill_expired" => $ipkl->expired_date . " " . date('H:i:s'),
                "bill_gross" => $ipkl->nominal,
                "bill_miscfee" => 0,
                "bill_total" => $ipkl->nominal,
                "bill_desc" => 'Pembayaran #' . $ipkl->id,
                "cust_no" => $user->id,
                "cust_name" => $user->name,
                "return_url" => url('/my-ipkl/finish'),
                "msisdn" => $user->no_hp,
                "email" => $user->email,
                "billing_address" => $user->alamat,
                "item" => [
                    [
                        "product" => $ipkl->id,
                        "qty" => "1",
                        "amount" => $ipkl->nominal
                    ]
                ],
                "button_color" => "333333",
                "background_color" => "333333",
                "signature" => $sha1_hash
            ];
    
            $response = Http::post($merchant_post_url, $payload);
            $dataResponse = $response->object(); 
            $ipkl->update([
                'redirect_url' => $dataResponse->redirect_url,
                'signature' => $sha1_hash
            ]);
    
            $data = [
                'user_id' => auth()->user()->id,
                'from' => auth()->user()->name,
                'message' => 'IPKL (' . $month_name . ' ' . $year . ') Harap untuk segera melakukan pembayaran!',
                'action' => '/my-ipkl/show/' . $ipkl->id
            ];
    
            $user->notify(new UserNotification($data));
    
            SendNotification::dispatch($user, $ipkl, $month_name, $ipkl->expired_date, $request->nominal);
    
            Mail::to($user->email)->send(new IpklMail($ipkl));

        });

        return redirect('/ipkl/show/'.$this->result)->with('success', 'Data Berhasil Diupdate');
    }

    public function myIpklUpdate(Request $request, $id)
    {
        $ipkl_old = Transaction::find($id);
        $result = null;
        DB::transaction(function ()  use ($request, $ipkl_old, $result) {
            $request->validate([
                'user_id' => 'required',
                'type' => 'required',
                'date' => 'required',
                'nominal' => 'required',
                'expired' => 'required',
                'notes' => 'nullable',
            ]);


            $nominal = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $month = date('m', strtotime($request->date));
            $year = date('Y', strtotime($request->date));


            $ipkl = Transaction::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'date' => $request->date,
                'nominal' => $nominal,
                'expired' => $request->expired,
                'notes' => $request->notes,
                'status' => $ipkl_old->status,
                'created_by' => $ipkl_old->created_by,
                'updated_by' => auth()->user()->id,
                'month' => $month,
                'year' => $year,
                'in_out' => $ipkl_old->in_out,
            ]);

            $this->result = $ipkl->id;

            $ipkl_old->delete();

            $date = Carbon::parse($ipkl->date);
            $now = Carbon::now();
            $diff_day = $now->diffInDays($date->addDay(), false);
            $diff_day = max(0, $diff_day);
            $total_expired = $diff_day + $ipkl->expired;
            $user = User::find($request->user_id);

            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $ipkl->id,
                    'gross_amount' => $ipkl->nominal,
                ),
                'expiry' => array(
                    'start_time' => date("Y-m-d H:i:s O"),
                    'unit' => 'days',
                    'duration' => $total_expired,
                ),
                'customer_details' => array(
                    'first_name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'phone' => $user->no_hp,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $ipkl->update([
                'snaptoken' => $snapToken
            ]);

            $month_name = Carbon::createFromFormat('m', $month)->translatedFormat('F');

            $data = [
                'user_id' => auth()->user()->id,
                'from' => auth()->user()->name,
                'message' => 'IPKL (' . $month_name . ' ' . $year . ') Harap untuk segera melakukan pembayaran!',
                'action' => '/my-ipkl/show/' . $ipkl->id
            ];

            $user->notify(new UserNotification($data));

            if ($ipkl->date) {
                Carbon::setLocale('id');
                $date = Carbon::createFromFormat('Y-m-d', $ipkl->date);
                $expired_date = $date->addDays($ipkl->expired)->translatedFormat('d F Y');
            } else {
                $expired_date = '-';
            }

            SendNotification::dispatch($user, $ipkl, $month_name, $expired_date, $request->nominal);

            Mail::to($user->email)->send(new IpklMail($ipkl));
        });

        return redirect('/my-ipkl/show/'.$this->result)->with('success', 'Data Berhasil Diupdate');
    }

    public function show($id)
    {
        $title = 'Iuran Pemeliharaan dan  Keamanan Lingkungan';
        $ipkl = Transaction::find($id);

        return view('ipkl.show', compact(
            'title',
            'ipkl',
        ));
    }

    public function delete($id)
    {
        $ipkl = Transaction::find($id);
        $ipkl->delete();

        return back()->with('success', 'Data Berhasil Dihapus');
    }

    public function myIpkl(Request $request)
    {
        $title = 'IPKL';
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');

        $transaction_ipkl = Transaction::where('user_id', auth()->user()->id)
        ->where('type', 'IPKL')
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

        return view('ipkl.myIpkl', compact(
            'title',
            'transaction_ipkl'
        ));
    }

    public function myIpklShow($id)
    {
        $title = 'IPKL';
        $ipkl = Transaction::find($id);
        session()->put('id', $ipkl->id);

        return view('ipkl.myIpklShow', compact(
            'title',
            'ipkl',
        ));
    }

    public function myIpkFinish()
    {
        return redirect('/my-ipkl/show/'.session()->get('id'));
    }

    public function laporanIpkl()
    {
        if (request()->input('year')) {
            $year = request()->input('year');
        } else {
            $year = date('Y');
        }

        $title = 'Laporan IPKL ' . $year;
        $search = request()->input('search');
        $rt = request()->input('rt');
        $rw = request()->input('rw');
        $status = request()->input('status');
        $month = request()->input('month');
        $status_transaksi = request()->input('status_transaksi');

        $users = User::when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('alamat', 'LIKE', '%'.$search.'%');
            });
        })
        ->when($rt, function ($query) use ($rt) {
            $query->where('rt', $rt);
        })
        ->when($rw, function ($query) use ($rw) {
            $query->where('rw', $rw);
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($status_transaksi == 'tagihan belum dibuat', function ($query) use ($month, $year) {
            $query->whereDoesntHave('transaction', function ($q) use ($month, $year) {
                $q->where('type', 'IPKL')
                  ->where('month', $month)
                  ->where('year', $year);
            });
        })
        ->when($status_transaksi == 'paid', function ($query) use ($month, $year) {
            $query->whereHas('transaction', function ($q) use ($month, $year) {
                $q->where('type', 'IPKL')
                  ->where('status', 'paid')
                  ->where('month', $month)
                  ->where('year', $year);
            });
        })
        ->when($status_transaksi == 'unpaid', function ($query) use ($month, $year) {
            $query->whereHas('transaction', function ($q) use ($month, $year) {
                $q->where('type', 'IPKL')
                  ->where('status', 'unpaid')
                  ->where('month', $month)
                  ->where('year', $year);
            })
            ->whereDoesntHave('transaction', function ($q) use ($month, $year) {
                $q->where('type', 'IPKL')
                  ->where('status', 'paid')
                  ->where('month', $month)
                  ->where('year', $year);
            });
        })
        ->orderBy('rt', 'asc')
        ->orderBy('alamat', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view('ipkl.laporanIpkl', compact(
            'title',
            'users',
            'year',
        ));
    }

    public function laporanIpklExport(Request $request)
    {
        return Excel::download(new LaporanIpklExport($request), 'laporan-ipkl.xlsx');
    }

    public function callbackTransaction(Request $request)
    {
        $transaction = Transaction::find($request->bill_no);
        $transaction->update([
            'status' => 'paid',
            'trx_id' => $request->trx_id,
            'payment_reff' => $request->payment_reff,
            'payment_date' => $request->payment_date,
            'payment_status_code' => $request->payment_status_code,
            'payment_status_desc' => $request->payment_status_desc,
            'payment_channel_uid' => $request->payment_channel_uid,
            'payment_channel' => $request->payment_channel,
        ]);

        $month_name = Carbon::createFromFormat('m', $transaction->month)->translatedFormat('F');

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        $message = 'IPKL (' . $month_name . ' ' . $transaction->year . ') berhasil dibayar oleh ' . $transaction->user->name . ' sebesar Rp ' . number_format($transaction->nominal);
        $action = '/ipkl/show/'.$transaction->id;

        $message_user = 'Terimakasih anda telah melakukan pembayaran IPKL (' . $month_name . ' ' . $transaction->year . ') sebesar Rp ' . number_format($transaction->nominal);
        $action_user = '/my-ipkl/show/'.$transaction->id;

        foreach ($users as $user) {
            $data = [
                'user_id'   =>  $transaction->user_id,
                'from'   =>  $transaction->user->name,
                'message'   =>  $message,
                'action'   =>  $action
            ];

            $user->notify(new UserNotification($data));
        }

        $user_payment = User::find($transaction->user_id);
        $data_user = [
            'user_id'   =>  $transaction->user_id,
            'from'   =>  $transaction->user->name,
            'message'   =>  $message_user,
            'action'   =>  $action_user
        ];

        $user->notify(new UserNotification($data_user));

        return response()->json([
            "response"       => "Payment Notification",
            "trx_id"         => $request->trx_id,
            "merchant_id"    => $request->merchant_id,
            "merchant"       => $request->merchant,
            "bill_no"        => $request->bill_no,
            "response_code"  => "00",
            "response_desc"  => "Success",
            "response_date"  => now()->format('Y-m-d H:i:s')
        ]);
    }
}
