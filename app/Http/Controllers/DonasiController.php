<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\DonasiExport;
use Illuminate\Support\Facades\DB;
use App\Exports\laporanDonasiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\UserNotification;

class DonasiController extends Controller
{
    public function index()
    {
        $title = 'Donasi';
        $user = request()->input('user');
        $type = request()->input('type');
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');
        $payment_source = request()->input('payment_source');
        $status = request()->input('status');
        $status_approval = request()->input('status_approval');

        $transaction_donasi = Transaction::when($user, function ($query) use ($user) {
            $query->whereHas('user', function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('name', 'LIKE', '%'.$user.'%')
                    ->orWhere('alamat', 'LIKE', '%'.$user.'%');
                });
            });
        })
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })
        ->when(!$type, function ($query) {
            $query->where(function ($q) {
                $q->where('type', 'Donasi Fasum')
                ->orWhere('type', 'Donasi RTH RT01')
                ->orWhere('type', 'Donasi Inventaris')
                ->orWhere('type', 'Donasi Lainnya');
            });
        })
        ->when($type, function ($query) use ($type) {
            $query->where('type', $type);
        })
        ->when($payment_source, function ($query) use ($payment_source) {
            $query->where('payment_source', $payment_source);
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($status_approval, function ($query) use ($status_approval) {
            $query->where('status_approval', $status_approval);
        })
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

        return view('donasi.index', compact(
            'title',
            'transaction_donasi'
        ));
    }

    public function export()
    {
        return (new DonasiExport($_GET))->download('Donasi.xlsx');
    }

    public function show($id)
    {
        $title = 'Donasi';
        $donasi = Transaction::find($id);

        return view('donasi.show', compact(
            'title',
            'donasi',
        ));
    }

    public function approval(Request $request, $id)
    {
        $donasi = Transaction::find($id);
        $status = null;
        DB::transaction(function ()  use ($request, $donasi, $status) {
            $validated = $request->validate([
                'status_approval' => 'required',
                'approver_notes' => 'nullable',
            ]);

            $validated['approved_by'] = auth()->user()->id;


            if ($request->status_approval == 'approved') {
                $validated['status'] = 'paid';
                $validated['paid_date'] = date('Y-m-d H:i:s');
                $this->status = 'Diapprove';
                $message = 'Donasi anda telah di approve oleh ' . auth()->user()->name;
            } else {
                $validated['status'] = 'unpaid';
                $this->status = 'Direject';
                $message = 'Donasi anda telah di reject oleh ' . auth()->user()->name;
            }

            $donasi->update($validated);

            $user = User::find($donasi->user_id);

            $data = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $message ,
                'action'   =>  '/my-donasi/show/'.$donasi->id
            ];

            $user->notify(new UserNotification($data));

        });

        return redirect('/donasi/show/'.$id)->with('success', 'Data Berhasil ' . $this->status);
    }

    public function myDonasi()
    {
        $title = 'Donasi';
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');

        $transaction_donasi = Transaction::where('user_id', auth()->user()->id)
        ->where(function ($query) {
            $query->where('type', 'Donasi Fasum')
            ->orWhere('type', 'Donasi RTH RT01')
            ->orWhere('type', 'Donasi Inventaris')
            ->orWhere('type', 'Donasi Lainnya');
        })
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

        return view('donasi.myDonasi', compact(
            'title',
            'transaction_donasi'
        ));
    }

    public function tambahMyDonasi()
    {
        $title = 'Donasi';

        return view('donasi.tambahMyDonasi', compact(
            'title',
        ));
    }

    public function storeMyDonasi(Request $request)
    {
        $result = null;
        DB::transaction(function ()  use ($request, $result) {
            $validated = $request->validate([
                'date' => 'required',
                'type' => 'required',
                'payment_source' => 'required',
                'nominal' => 'required',
                'notes' => 'nullable',
                'file_transaction_path' => 'required|file|max:10240',
            ]);

            $validated['nominal'] = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $validated['month'] = date('m', strtotime($request->date));
            $validated['year'] = date('Y', strtotime($request->date));
            $validated['user_id'] = auth()->user()->id;
            $validated['created_by'] = auth()->user()->id;
            $validated['status'] = 'unpaid';
            $validated['status_approval'] = 'draft';
            $validated['in_out'] = 'in';

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $donasi = Transaction::create($validated);
            $this->result = $donasi->id;

            $message = $donasi->type . ' dari ' . auth()->user()->name . ' membutuhkan approval dari anda.';

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($users as $user) {
                $data = [
                    'user_id'   =>  auth()->user()->id,
                    'from'   =>  auth()->user()->name,
                    'message'   =>  $message,
                    'action'   =>  '/donasi/show/'.$donasi->id
                ];

                $user->notify(new UserNotification($data));
            }
        });

        return redirect('/my-donasi/show/'.$this->result)->with('success', 'Data Berhasil Ditambahkan');
    }

    public function editMyDonasi($id)
    {
        $title = 'Donasi';
        $donasi = Transaction::find($id);

        return view('donasi.editMyDonasi', compact(
            'title',
            'donasi',
        ));
    }

    public function updateMyDonasi(Request $request, $id)
    {
        $donasi = Transaction::find($id);
        DB::transaction(function ()  use ($request, $donasi) {
            $validated = $request->validate([
                'date' => 'required',
                'type' => 'required',
                'payment_source' => 'required',
                'nominal' => 'required',
                'notes' => 'nullable',
                'file_transaction_path' => 'file|max:10240',
            ]);

            $validated['nominal'] = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $validated['month'] = date('m', strtotime($request->date));
            $validated['year'] = date('Y', strtotime($request->date));

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $donasi->update($validated);

            $message = $donasi->type . ' dari ' . auth()->user()->name . ' membutuhkan approval dari anda.';

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($users as $user) {
                $data = [
                    'user_id'   =>  auth()->user()->id,
                    'from'   =>  auth()->user()->name,
                    'message'   =>  $message,
                    'action'   =>  '/donasi/show/'.$donasi->id
                ];

                $user->notify(new UserNotification($data));
            }
        });

        return redirect('/my-donasi/show/'.$donasi->id)->with('success', 'Data Berhasil Diupdate');
    }

    public function showMyDonasi($id)
    {
        $title = 'Donasi';
        $donasi = Transaction::find($id);

        return view('donasi.showMyDonasi', compact(
            'title',
            'donasi',
        ));
    }

    public function uploadMyDonasi(Request $request, $id)
    {
        $donasi = Transaction::find($id);
        DB::transaction(function ()  use ($request, $donasi) {
            $validated = $request->validate([
                'file_transaction_path' => 'required|file|max:10240',
            ]);

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $donasi->update($validated);
        });

        return redirect('/my-donasi/show/'.$id)->with('success', 'File Berhasil Diupload');
    }

    public function deleteMyDonasi($id)
    {
        $donasi = Transaction::find($id);
        DB::transaction(function ()  use ($donasi) {
            $donasi->delete();
        });

        return redirect('/my-donasi')->with('success', 'Data Berhasil Didelete');
    }

    public function laporanDonasi()
    {
        if (request()->input('year')) {
            $year = request()->input('year');
        } else {
            $year = date('Y');
        }

        $title = 'Laporan Donasi ' . $year;
        $search = request()->input('search');
        $rt = request()->input('rt');
        $rw = request()->input('rw');
        $status = request()->input('status');
        $month = request()->input('month');

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
        ->orderBy('rt', 'asc')
        ->orderBy('alamat', 'asc')
        ->paginate(10)
        ->withQueryString();

        return view('donasi.laporanDonasi', compact(
            'title',
            'users',
            'year',
        ));
    }

    public function laporanDonasiExport(Request $request)
    {
        return Excel::download(new laporanDonasiExport($request), 'laporan-donasi.xlsx');
    }
}
