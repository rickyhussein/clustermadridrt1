@extends('layouts.dashboard')
@section('button')
    <li class="nav-item mr-2">
        <a href="{{ url('/ulp/tambah') }}" class="btn btn-primary nav-link" style="color: white">+ Tambah</a>
    </li>
@endsection
@section('isi')
    <form action="{{ url('/ulp') }}" class="mr-2 ml-2">
        <div class="form-row mb-2">
            <div class="col-10">
                <input type="text" class="form-control" name="search" placeholder="search" id="mulai" value="{{ request('search') }}">
            </div>
            <div class="col-2">
                <button type="submit" id="search" class="btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
    <div class="container-fluid">
        <div class="card p-4">
            <div class="table-responsive" style="border-radius: 10px">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="position: sticky; left: 0; background-color: rgb(215, 215, 215); z-index: 2; width:5%">No.</th>
                            <th style="width: 90%; background-color:rgb(243, 243, 243);" class="text-center">File PDF</th>
                            <th style="background-color:rgb(243, 243, 243); width:5%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($ulps) <= 0)
                            <tr>
                                <td colspan="10" class="text-center">Tidak Ada Data</td>
                            </tr>
                        @else
                            @foreach ($ulps as $key => $ulp)
                                <tr>
                                    <td class="text-center" style="position: sticky; left: 0; background-color: rgb(235, 235, 235); z-index: 1; vertical-align: middle;">{{ ($ulps->currentpage() - 1) * $ulps->perpage() + $key + 1 }}.</td>
                                    <td style="vertical-align: middle;" class="text-center">
                                        @if ($ulp->ulp_file_path)
                                            <div class="badge clickable" data-url="{{ url('/storage/'.$ulp->ulp_file_path) }}" style="color: rgb(21, 47, 118); background-color:rgba(192, 218, 254, 0.889); border-radius:10px; cursor: pointer;" target="_blank"><i class="fa fa-download mr-1"></i> {{ $ulp->ulp_file_name }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div style="display: flex; gap: 5px;">
                                            <a href="{{ url('/ulp/edit/'.$ulp->id) }}" class="btn btn-primary btn-sm" title="Edit Tata Tertib"><i class="fa fa-edit"></i></a>

                                            <form action="{{ url('/ulp/delete/'.$ulp->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button title="Delete ulp" class="border-0 btn btn-danger btn-sm" onClick="return confirm('Are You Sure')"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end me-4 mt-4">
                {{ $ulps->links() }}
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(".clickable").on("click", function() {
                window.location.href = $(this).data("url");
            });
        </script>
    @endpush
@endsection




