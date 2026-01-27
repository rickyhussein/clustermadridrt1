<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\GateCardExport;
use Illuminate\Support\Facades\DB;
use App\Exports\laporanGateCardExport;
use App\Notifications\UserNotification;

class GateCardController extends Controller
{
    public function index()
    {
        $title = 'Gate Card';
        $user = request()->input('user');
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');
        $payment_source = request()->input('payment_source');
        $status = request()->input('status');
        $status_approval = request()->input('status_approval');

        $transaction_gate_card = Transaction::where('type', 'Gate Card')
        ->when($user, function ($query) use ($user) {
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

        return view('gate-card.index', compact(
            'title',
            'transaction_gate_card'
        ));
    }

    public function export()
    {
        return (new GateCardExport($_GET))->download('Gate Card.xlsx');
    }

    public function show($id)
    {
        $title = 'Gate Card';
        $gate_card = Transaction::find($id);

        return view('gate-card.show', compact(
            'title',
            'gate_card',
        ));
    }

    public function approval(Request $request, $id)
    {
        $gate_card = Transaction::find($id);
        $status = null;
        DB::transaction(function ()  use ($request, $gate_card, $status) {
            $validated = $request->validate([
                'status_approval' => 'required',
                'approver_notes' => 'nullable',
            ]);

            $validated['approved_by'] = auth()->user()->id;

            if ($request->status_approval == 'approved') {
                $validated['status'] = 'paid';
                $validated['paid_date'] = date('Y-m-d H:i:s');
                $this->status = 'Diapprove';
                $message = 'Pembayaran Gate Card anda telah di approve oleh ' . auth()->user()->name;
            } else {
                $validated['status'] = 'unpaid';
                $this->status = 'Direject';
                $message = 'Pembayaran Gate Card anda telah di reject oleh ' . auth()->user()->name;
            }

            $gate_card->update($validated);

            $user = User::find($gate_card->user_id);

            $data = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $message ,
                'action'   =>  '/my-gate-card/show/'.$gate_card->id
            ];

            $user->notify(new UserNotification($data));

        });

        return redirect('/gate-card/show/'.$id)->with('success', 'Data Berhasil ' . $this->status);
    }

    public function myGateCard()
    {
        $title = 'Gate Card';
        $start_date = request()->input('start_date');
        $end_date = request()->input('end_date');

        $transaction_gate_card = Transaction::where('user_id', auth()->user()->id)
        ->where('type', 'Gate Card')
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

        return view('gate-card.myGateCard', compact(
            'title',
            'transaction_gate_card'
        ));
    }

    public function tambahMyGateCard()
    {
        $title = 'Gate Card';

        return view('gate-card.tambahMyGateCard', compact(
            'title',
        ));
    }

    public function storeMyGateCard(Request $request)
    {
        $result = null;
        DB::transaction(function ()  use ($request, $result) {
            $validated = $request->validate([
                'date' => 'required',
                'type' => 'required',
                'status_gate_card' => 'required',
                'status_gate_card_text' => 'required_if:status_gate_card,Katu Akses Pengganti',
                'payment_source' => 'required',
                'nominal' => 'required',
                'qty' => 'required|numeric|max:4',
                'vehicle_type' => 'required',
                'vehicle_type_text' => 'required_if:vehicle_type,Lainnya',
                'notes' => 'nullable',
                'file_transaction_path' => 'required|file|max:10240',
            ]);

            $validated['nominal'] = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $validated['month'] = date('m', strtotime($request->date));
            $validated['year'] = date('Y', strtotime($request->date));
            $validated['user_id'] = auth()->user()->id;
            $validated['created_by'] = auth()->user()->id;
            $validated['status_approval'] = 'draft';
            $validated['status'] = 'unpaid';
            $validated['in_out'] = 'in';

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $gate_card = Transaction::create($validated);
            $this->result = $gate_card->id;

            $message = 'Permintaan pembuatan Gate Card oleh ' . auth()->user()->name . ' membutuhkan approval dari anda.';

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($users as $user) {
                $data = [
                    'user_id'   =>  auth()->user()->id,
                    'from'   =>  auth()->user()->name,
                    'message'   =>  $message,
                    'action'   =>  '/gate-card/show/'.$gate_card->id
                ];

                $user->notify(new UserNotification($data));
            }
        });

        return redirect('/my-gate-card/show/'.$this->result)->with('success', 'Data Berhasil Ditambahkan');
    }

    public function editMyGateCard($id)
    {
        $title = 'Gate Card';
        $gate_card = Transaction::find($id);

        return view('gate-card.editMyGateCard', compact(
            'title',
            'gate_card',
        ));
    }

    public function updateMyGateCard(Request $request, $id)
    {
        $gate_card = Transaction::find($id);
        DB::transaction(function ()  use ($request, $gate_card) {
            $validated = $request->validate([
                'date' => 'required',
                'type' => 'required',
                'status_gate_card' => 'required',
                'status_gate_card_text' => 'required_if:status_gate_card,Katu Akses Pengganti',
                'payment_source' => 'required',
                'nominal' => 'required',
                'qty' => 'required|numeric|max:4',
                'vehicle_type' => 'required',
                'vehicle_type_text' => 'required_if:vehicle_type,Lainnya',
                'notes' => 'nullable',
                'file_transaction_path' => 'file|max:10240',
            ]);

            $validated['nominal'] = $request->nominal ? str_replace(',', '', $request->nominal) : 0;
            $validated['month'] = date('m', strtotime($request->date));
            $validated['year'] = date('Y', strtotime($request->date));
            $validated['updated_by'] = auth()->user()->id;

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $gate_card->update($validated);

            $message = 'Permintaan pembuatan Gate Card oleh ' . auth()->user()->name . ' membutuhkan approval dari anda.';

            $users = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($users as $user) {
                $data = [
                    'user_id'   =>  auth()->user()->id,
                    'from'   =>  auth()->user()->name,
                    'message'   =>  $message,
                    'action'   =>  '/gate-card/show/'.$gate_card->id
                ];

                $user->notify(new UserNotification($data));
            }
        });

        return redirect('/my-gate-card/show/'.$gate_card->id)->with('success', 'Data Berhasil Diupdate');
    }

    public function showMyGateCard($id)
    {
        $title = 'Gate Card';
        $gate_card = Transaction::find($id);

        return view('gate-card.showMyGateCard', compact(
            'title',
            'gate_card',
        ));
    }

    public function uploadMyGateCard(Request $request, $id)
    {
        $gate_card = Transaction::find($id);
        DB::transaction(function ()  use ($request, $gate_card) {
            $validated = $request->validate([
                'file_transaction_path' => 'required|file|max:10240',
            ]);

            if ($request->file('file_transaction_path')) {
                $validated['file_transaction_path'] = $request->file('file_transaction_path')->store('file_transaction_path');
                $validated['file_transaction_name'] = $request->file('file_transaction_path')->getClientOriginalName();
            }

            $gate_card->update($validated);
        });

        return redirect('/my-gate-card/show/'.$id)->with('success', 'File Berhasil Diupload');
    }

    public function deleteMyGateCard($id)
    {
        $gate_card = Transaction::find($id);
        DB::transaction(function ()  use ($gate_card) {
            $gate_card->delete();
        });

        return redirect('/my-gate-card')->with('success', 'Data Berhasil Didelete');
    }

    public function laporanGateCard()
    {
        $title = 'Laporan Gate Card';
        $search = request()->input('search');
        $rt = request()->input('rt');
        $rw = request()->input('rw');
        $status = request()->input('status');

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

        return view('gate-card.laporanGateCard', compact(
            'title',
            'users',
        ));
    }

    public function laporanGateCardExport(Request $request)
    {
        return (new laporanGateCardExport($_GET))->download('Laporan Gate Card.xlsx');
    }
}
