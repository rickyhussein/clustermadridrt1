@extends('layouts.dashboard')
@section('button')
    <li class="nav-item mr-2">
        <a href="{{ url('/ulp') }}" class="btn nav-link" style="color: red; border:1px solid red; background-color:white; ">Back</a>
    </li>
@endsection
@section('isi')
    <div class="container-fluid">
        <div class="card col-lg-12">
            <div class="mt-4 p-4">
                <form method="post" action="{{ url('/ulp/update/'.$ulp->id) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-row">
                        <div class="col">
                            <label for="ulp_file_path" class="form-label">File PDF</label>
                            <input class="form-control @error('ulp_file_path') is-invalid @enderror" accept="application/pdf" type="file" id="ulp_file_path" name="ulp_file_path">
                            @if ($ulp->ulp_file_path)
                                <div class="badge clickable" data-url="{{ url('/storage/'.$ulp->ulp_file_path) }}" style="color: rgb(21, 47, 118); background-color:rgba(192, 218, 254, 0.889); border-radius:10px; cursor: pointer;" target="_blank"><i class="fa fa-download mr-1"></i> {{ $ulp->ulp_file_name }}</div>
                            @endif
                            @error('ulp_file_path')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </form>
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
