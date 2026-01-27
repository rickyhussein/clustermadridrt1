@extends('layouts.app')
@section('back')
    @if (auth()->user())
        <a href="{{ url('/my-ipkl') }}" class="back-btn"> <i class="icon-left"></i> </a>
    @endif
@endsection
@section('container')
    @if ($ipkl)
        @php
            session()->put('id', $ipkl->id);
        @endphp
        <div id="app-wrap" class="mt-4">
            <div class="bill-content">
                <div class="tf-container ms-4 me-4">
                    <ul class="mt-4">
                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Nama
                                </p>
                                <h5>
                                    {{ $ipkl->user->name ?? '-' }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Alamat
                                </p>
                                <h5>
                                    {{ $ipkl->user->alamat ?? '-' }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Status Rumah
                                </p>
                                <h5>
                                    {{ $ipkl->user->status ?? '-' }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Tanggal
                                </p>
                                <h5>
                                    @php
                                        if ($ipkl->date) {
                                            Carbon\Carbon::setLocale('id');
                                            $date = Carbon\Carbon::createFromFormat('Y-m-d', $ipkl->date);
                                            $new_date = $date->translatedFormat('d F Y');
                                        } else {
                                            $new_date = '-';
                                        }
                                    @endphp
                                    {{ $new_date  }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Jatuh Tempo
                                </p>
                                <h5 style="color: red">
                                    @php
                                        if ($ipkl->expired_date) {
                                            $expired_date = Carbon\Carbon::createFromFormat('Y-m-d', $ipkl->expired_date);
                                            $new_expired_date = $expired_date->translatedFormat('d F Y');
                                        } else {
                                            $new_expired_date = '-';
                                        }
                                    @endphp
                                    {{ $new_expired_date }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Jenis Transaksi
                                </p>
                                <h5>
                                    @php
                                        $month = Carbon\Carbon::createFromFormat('m', $ipkl->month)->translatedFormat('F');
                                    @endphp
                                    {{ $ipkl->type ?? '-' }} ({{ $month }} {{ $ipkl->year }})
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Nominal
                                </p>
                                <h5>
                                    Rp {{ number_format($ipkl->nominal) }}
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Status
                                </p>
                                <h5>
                                    @if ($ipkl->status == 'paid')
                                        <div class="badge" style="color: rgba(20, 78, 7, 0.889); background-color:rgb(186, 238, 162); border-radius:10px;">Lunas</div>
                                    @else
                                        <div class="badge" style="color: rgba(78, 26, 26, 0.889); background-color:rgb(242, 170, 170); border-radius:10px;">Belum Lunas</div>
                                    @endif
                                </h5>
                            </div>
                        </li>

                        <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                            <div class="content-right">
                                <p>
                                    Keterangan
                                </p>
                                <h5>
                                    {!! $ipkl->notes ? nl2br(e($ipkl->notes)) : '-' !!}
                                </h5>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if ($ipkl->status == 'unpaid')
            <div class="bottom-navigation-bar st2 bottom-btn-fixed" style="bottom:65px">
                <div class="tf-container">
                    <a href="{{ $ipkl->redirect_url }}" target="_blank" class="tf-btn accent large">Bayar Sekarang</a>
                </div>
            </div>
        @endif
    @else
        <div id="app-wrap" class="d-flex justify-content-center align-items-center vh-100">
            <div class="bill-content text-center">
                <div class="tf-container">
                    <p class="m-0">Data Not Found</p>
                </div>
            </div>
        </div>
    @endif


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
