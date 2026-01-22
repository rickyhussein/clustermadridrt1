@extends('layouts.app')
@section('back')
    <a href="{{ url('/dashboard-user') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <div id="app-wrap" class="style1">
        <div class="tf-container">
            <form action="{{ url('/my-berita') }}" class="mt-4">
                <div class="row">
                    <div class="col-10">
                        <input type="text" name="search" placeholder="Search.." id="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            @if (count($beritas) <= 0)
                <div class="d-flex justify-content-center align-items-center vh-100">
                    <div class="bill-content text-center">
                        <div class="tf-container">
                            <p class="m-0">Data not available</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-4">
                    @foreach ($beritas as $key => $berita)
                        <a href="{{ url('/my-berita/show/'.$berita->id) }}" class="col-6" style="color: black">
                            <div class="card mb-3" style="border-radius: 15px;">
                                <img style="max-height: 150px; border-top-left-radius: 15px; border-top-right-radius: 15px;" src="{{ url('/storage/'.$berita->berita_file_path) }}" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $berita->judul ?? '' }}</h5>
                                    <p class="card-text" style="font-size: 8px;">{{ Str::limit($berita->isi, 100, '...') }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="d-flex justify-content-end me-4 mt-4">
                    {{ $beritas->links() }}
                </div>
            @endif
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


@endsection
