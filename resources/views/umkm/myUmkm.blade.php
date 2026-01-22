@extends('layouts.app')
@section('back')
    <a href="{{ url('/dashboard-user') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <div id="app-wrap" class="style1">
        <div class="tf-container">
            <form action="{{ url('/my-umkm') }}" class="mt-4">
                <div class="row">
                    <div class="col-10">
                        <input type="text" name="search" placeholder="Search.." id="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

            @if (count($umkms) <= 0)
                <div class="d-flex justify-content-center align-items-center vh-100">
                    <div class="bill-content text-center">
                        <div class="tf-container">
                            <p class="m-0">Data not available</p>
                        </div>
                    </div>
                </div>
            @else
                @foreach ($umkms as $umkm)
                    <div class="ms-2 mt-4 me-2">
                        <a href="{{ url('/my-umkm/show/'.$umkm->id) }}">
                            <div class="card text-dark bg-light mb-3" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="row  d-flex align-items-center">
                                        <div class="col-9">
                                            <h5 class="card-title">{{ $umkm->name ?? '' }}</h5>
                                            <h5 class="card-title critical_color fw_6">Rp {{ number_format($umkm->price) }}</h5>
                                            <p class="card-text">{{ Str::limit($umkm->description, 100, '...') }}</p>
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex justify-content-center align-items-center text-white rounded">
                                                <img src="{{ url('/storage/'.$umkm->items->first()->umkm_file_path) }}" style="width: 70px; height: 70px; border-radius: 15px; object-fit: cover;" alt="Kos Image">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 mb-4">
                                            <a class="tf-btn small" style="color: green; border:1px solid green; background-color:white;" href="{{ url('/my-umkm/edit/'.$umkm->id) }}"><i class="fas fa-pencil-alt"></i>Edit</a>
                                        </div>

                                        <div class="col-6 mb-4">
                                            <a href="#" class="tf-btn small btn-logout" style="color: red; border:1px solid red; background-color:white;" data-target="#logoutModal-{{ $umkm->id }}"><i class="fas fa-trash"></i>Hapus</a>
                                        </div>

                                        <div class="tf-panel logout" id="logoutModal-{{ $umkm->id }}">
                                            <div class="panel_overlay"></div>
                                            <div class="panel-box panel-center panel-logout">
                                                <div class="heading">
                                                    <h2 class="text-center">Anda yakin ingin menghapus data ini?</h2>
                                                </div>
                                                <div class="bottom">
                                                    <a class="clear-panel" href="#">Cancel</a>
                                                    <a class="clear-panel critical_color clickable" data-url="{{ url('/my-umkm/delete/'.$umkm->id) }}" style="cursor: pointer;">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="d-flex justify-content-end me-4 mt-4">
                            {{ $umkms->links() }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="bottom-navigation-bar st2 bottom-btn-fixed" style="bottom:50px">
        <div class="tf-container">
            <a href="{{ url('/my-umkm/tambah') }}" class="tf-btn accent large"> + Tambah</a>
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

    @push('script')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.btn-logout', function(e) {
                    e.preventDefault();
                    const targetModal = $(this).data('target');
                    $(targetModal).addClass("panel-open");
                });

                $(document).on('click', '.panel_overlay, .clear-panel', function(e) {
                    e.preventDefault();
                    $(".logout").removeClass("panel-open");
                });

                $(".clickable").on("click", function() {
                    window.location.href = $(this).data("url");
                });
            });
        </script>
    @endpush


@endsection
