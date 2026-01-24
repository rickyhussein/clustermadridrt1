<?php

namespace App\Http\Controllers;

use App\Models\Ulp;
use Illuminate\Http\Request;

class UlpController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Unduh Laporan PDF';
        $search = request()->input('search');

        $ulps = Ulp::when($search, function ($query) use ($search) {
            $query->where('ulp_file_name', 'LIKE', '%'.$search.'%');
        })
        ->orderBy('id', 'ASC')
        ->paginate(10)
        ->withQueryString();

        return view('ulp.index', compact(
            'title',
            'ulps'
        ));
    }

    public function tambah()
    {
        $title = 'Unduh Laporan PDF';

        return view('ulp.tambah', compact(
            'title',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ulp_file_path' => 'file|max:10240',
        ]);

        if ($request->file('ulp_file_path')) {
            $validated['ulp_file_path'] = $request->file('ulp_file_path')->store('ulp_file_path');
            $validated['ulp_file_name'] = $request->file('ulp_file_path')->getClientOriginalName();
        }

        Ulp::create($validated);

        return redirect('/ulp')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function edit($id)
    {
        $title = 'Unduh Laporan PDF';
        $ulp = Ulp::find($id);

        return view('ulp.edit', compact(
            'title',
            'ulp',
        ));
    }

    public function update(Request $request, $id)
    {
        $ulp = Ulp::find($id);

        $validated = $request->validate([
            'ulp_file_path' => 'file|max:10240',
        ]);

        if ($request->file('ulp_file_path')) {
            $validated['ulp_file_path'] = $request->file('ulp_file_path')->store('ulp_file_path');
            $validated['ulp_file_name'] = $request->file('ulp_file_path')->getClientOriginalName();
        }

        $ulp->update($validated);

        return redirect('/ulp')->with('success', 'Data Berhasil di update');
    }

    public function delete($id)
    {
        $ulp = Ulp::find($id);
        $ulp->delete();
        return redirect('/ulp')->with('success', 'Data Berhasil di hapus');
    }
}
