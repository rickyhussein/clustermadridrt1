<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Donasi</title>
</head>
<body>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Alamat</th>
                <th rowspan="2">RT</th>
                <th rowspan="2">RW</th>
                <th rowspan="2">Status</th>

                @if (!request('month') || request('month') == '01')
                    <th colspan="4">Januari</th>
                @endif

                @if (!request('month') || request('month') == '02')
                    <th colspan="4">Februari</th>
                @endif

                @if (!request('month') || request('month') == '03')
                    <th colspan="4">Maret</th>
                @endif

                @if (!request('month') || request('month') == '04')
                    <th colspan="4">April</th>
                @endif

                @if (!request('month') || request('month') == '05')
                    <th colspan="4">Mei</th>
                @endif

                @if (!request('month') || request('month') == '06')
                    <th colspan="4">Juni</th>
                @endif

                @if (!request('month') || request('month') == '07')
                    <th colspan="4">Juli</th>
                @endif

                @if (!request('month') || request('month') == '08')
                    <th colspan="4">Agustus</th>
                @endif

                @if (!request('month') || request('month') == '09')
                    <th colspan="4">September</th>
                @endif

                @if (!request('month') || request('month') == '10')
                    <th colspan="4">Oktober</th>
                @endif

                @if (!request('month') || request('month') == '11')
                    <th colspan="4">November</th>
                @endif

                @if (!request('month') || request('month') == '12')
                    <th colspan="4">Desember</th>
                @endif

                <th rowspan="2">Total</th>
            </tr>

            <tr>
                @if (!request('month') || request('month') == '01')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '02')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '03')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '04')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '05')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '06')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '07')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '08')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '09')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '10')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '11')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif

                @if (!request('month') || request('month') == '12')
                    <th>Donasi Fasum</th>
                    <th>Donasi RTH RT01</th>
                    <th>Donasi Inventaris</th>
                    <th>Donasi Lainnya</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if (count($users) <= 0)
                <tr>
                    <td colspan="43" class="text-center">Tidak Ada Data</td>
                </tr>
            @else
                @php
                    $total_total_januari_fasum = 0;
                    $total_total_januari_rth_rt01 = 0;
                    $total_total_januari_inventaris = 0;
                    $total_total_januari_lainnya = 0;

                    $total_total_februari_fasum = 0;
                    $total_total_februari_rth_rt01 = 0;
                    $total_total_februari_inventaris = 0;
                    $total_total_februari_lainnya = 0;

                    $total_total_maret_fasum = 0;
                    $total_total_maret_rth_rt01 = 0;
                    $total_total_maret_inventaris = 0;
                    $total_total_maret_lainnya = 0;

                    $total_total_april_fasum = 0;
                    $total_total_april_rth_rt01 = 0;
                    $total_total_april_inventaris = 0;
                    $total_total_april_lainnya = 0;

                    $total_total_mei_fasum = 0;
                    $total_total_mei_rth_rt01 = 0;
                    $total_total_mei_inventaris = 0;
                    $total_total_mei_lainnya = 0;

                    $total_total_juni_fasum = 0;
                    $total_total_juni_rth_rt01 = 0;
                    $total_total_juni_inventaris = 0;
                    $total_total_juni_lainnya = 0;

                    $total_total_juli_fasum = 0;
                    $total_total_juli_rth_rt01 = 0;
                    $total_total_juli_inventaris = 0;
                    $total_total_juli_lainnya = 0;

                    $total_total_agustus_fasum = 0;
                    $total_total_agustus_rth_rt01 = 0;
                    $total_total_agustus_inventaris = 0;
                    $total_total_agustus_lainnya = 0;

                    $total_total_september_fasum = 0;
                    $total_total_september_rth_rt01 = 0;
                    $total_total_september_inventaris = 0;
                    $total_total_september_lainnya = 0;

                    $total_total_oktober_fasum = 0;
                    $total_total_oktober_rth_rt01 = 0;
                    $total_total_oktober_inventaris = 0;
                    $total_total_oktober_lainnya = 0;

                    $total_total_november_fasum = 0;
                    $total_total_november_rth_rt01 = 0;
                    $total_total_november_inventaris = 0;
                    $total_total_november_lainnya = 0;

                    $total_total_desember_fasum = 0;
                    $total_total_desember_rth_rt01 = 0;
                    $total_total_desember_inventaris = 0;
                    $total_total_desember_lainnya = 0;

                    $total_total = 0;
                @endphp
                @foreach ($users as $key => $user)
                    <tr>
                        @php
                            $total_januari_fasum = $user->getDonasiFasum($user->id, '01', $year);
                            $total_januari_rth_rt01 = $user->getDonasiRthRt01($user->id, '01', $year);
                            $total_januari_inventaris = $user->getDonasiInventaris($user->id, '01', $year);
                            $total_januari_lainnya = $user->getDonasiLainnya($user->id, '01', $year);

                            $total_februari_fasum = $user->getDonasiFasum($user->id, '02', $year);
                            $total_februari_rth_rt01 = $user->getDonasiRthRt01($user->id, '02', $year);
                            $total_februari_inventaris = $user->getDonasiInventaris($user->id, '02', $year);
                            $total_februari_lainnya = $user->getDonasiLainnya($user->id, '02', $year);

                            $total_maret_fasum = $user->getDonasiFasum($user->id, '03', $year);
                            $total_maret_rth_rt01 = $user->getDonasiRthRt01($user->id, '03', $year);
                            $total_maret_inventaris = $user->getDonasiInventaris($user->id, '03', $year);
                            $total_maret_lainnya = $user->getDonasiLainnya($user->id, '03', $year);

                            $total_april_fasum = $user->getDonasiFasum($user->id, '04', $year);
                            $total_april_rth_rt01 = $user->getDonasiRthRt01($user->id, '04', $year);
                            $total_april_inventaris = $user->getDonasiInventaris($user->id, '04', $year);
                            $total_april_lainnya = $user->getDonasiLainnya($user->id, '04', $year);

                            $total_mei_fasum = $user->getDonasiFasum($user->id, '05', $year);
                            $total_mei_rth_rt01 = $user->getDonasiRthRt01($user->id, '05', $year);
                            $total_mei_inventaris = $user->getDonasiInventaris($user->id, '05', $year);
                            $total_mei_lainnya = $user->getDonasiLainnya($user->id, '05', $year);

                            $total_juni_fasum = $user->getDonasiFasum($user->id, '06', $year);
                            $total_juni_rth_rt01 = $user->getDonasiRthRt01($user->id, '06', $year);
                            $total_juni_inventaris = $user->getDonasiInventaris($user->id, '06', $year);
                            $total_juni_lainnya = $user->getDonasiLainnya($user->id, '06', $year);

                            $total_juli_fasum = $user->getDonasiFasum($user->id, '07', $year);
                            $total_juli_rth_rt01 = $user->getDonasiRthRt01($user->id, '07', $year);
                            $total_juli_inventaris = $user->getDonasiInventaris($user->id, '07', $year);
                            $total_juli_lainnya = $user->getDonasiLainnya($user->id, '07', $year);

                            $total_agustus_fasum = $user->getDonasiFasum($user->id, '08', $year);
                            $total_agustus_rth_rt01 = $user->getDonasiRthRt01($user->id, '08', $year);
                            $total_agustus_inventaris = $user->getDonasiInventaris($user->id, '08', $year);
                            $total_agustus_lainnya = $user->getDonasiLainnya($user->id, '08', $year);

                            $total_september_fasum = $user->getDonasiFasum($user->id, '09', $year);
                            $total_september_rth_rt01 = $user->getDonasiRthRt01($user->id, '09', $year);
                            $total_september_inventaris = $user->getDonasiInventaris($user->id, '09', $year);
                            $total_september_lainnya = $user->getDonasiLainnya($user->id, '09', $year);

                            $total_oktober_fasum = $user->getDonasiFasum($user->id, '10', $year);
                            $total_oktober_rth_rt01 = $user->getDonasiRthRt01($user->id, '10', $year);
                            $total_oktober_inventaris = $user->getDonasiInventaris($user->id, '10', $year);
                            $total_oktober_lainnya = $user->getDonasiLainnya($user->id, '10', $year);

                            $total_november_fasum = $user->getDonasiFasum($user->id, '11', $year);
                            $total_november_rth_rt01 = $user->getDonasiRthRt01($user->id, '11', $year);
                            $total_november_inventaris = $user->getDonasiInventaris($user->id, '11', $year);
                            $total_november_lainnya = $user->getDonasiLainnya($user->id, '11', $year);

                            $total_desember_fasum = $user->getDonasiFasum($user->id, '12', $year);
                            $total_desember_rth_rt01 = $user->getDonasiRthRt01($user->id, '12', $year);
                            $total_desember_inventaris = $user->getDonasiInventaris($user->id, '12', $year);
                            $total_desember_lainnya = $user->getDonasiLainnya($user->id, '12', $year);

                            $total_total_januari_fasum += $total_januari_fasum;
                            $total_total_januari_rth_rt01 += $total_januari_rth_rt01;
                            $total_total_januari_inventaris += $total_januari_inventaris;
                            $total_total_januari_lainnya += $total_januari_lainnya;

                            $total_total_februari_fasum += $total_februari_fasum;
                            $total_total_februari_rth_rt01 += $total_februari_rth_rt01;
                            $total_total_februari_inventaris += $total_februari_inventaris;
                            $total_total_februari_lainnya += $total_februari_lainnya;

                            $total_total_maret_fasum += $total_maret_fasum;
                            $total_total_maret_rth_rt01 += $total_maret_rth_rt01;
                            $total_total_maret_inventaris += $total_maret_inventaris;
                            $total_total_maret_lainnya += $total_maret_lainnya;

                            $total_total_april_fasum += $total_april_fasum;
                            $total_total_april_rth_rt01 += $total_april_rth_rt01;
                            $total_total_april_inventaris += $total_april_inventaris;
                            $total_total_april_lainnya += $total_april_lainnya;

                            $total_total_mei_fasum += $total_mei_fasum;
                            $total_total_mei_rth_rt01 += $total_mei_rth_rt01;
                            $total_total_mei_inventaris += $total_mei_inventaris;
                            $total_total_mei_lainnya += $total_mei_lainnya;

                            $total_total_juni_fasum += $total_juni_fasum;
                            $total_total_juni_rth_rt01 += $total_juni_rth_rt01;
                            $total_total_juni_inventaris += $total_juni_inventaris;
                            $total_total_juni_lainnya += $total_juni_lainnya;

                            $total_total_juli_fasum += $total_juli_fasum;
                            $total_total_juli_rth_rt01 += $total_juli_rth_rt01;
                            $total_total_juli_inventaris += $total_juli_inventaris;
                            $total_total_juli_lainnya += $total_juli_lainnya;

                            $total_total_agustus_fasum += $total_agustus_fasum;
                            $total_total_agustus_rth_rt01 += $total_agustus_rth_rt01;
                            $total_total_agustus_inventaris += $total_agustus_inventaris;
                            $total_total_agustus_lainnya += $total_agustus_lainnya;

                            $total_total_september_fasum += $total_september_fasum;
                            $total_total_september_rth_rt01 += $total_september_rth_rt01;
                            $total_total_september_inventaris += $total_september_inventaris;
                            $total_total_september_lainnya += $total_september_lainnya;

                            $total_total_oktober_fasum += $total_oktober_fasum;
                            $total_total_oktober_rth_rt01 += $total_oktober_rth_rt01;
                            $total_total_oktober_inventaris += $total_oktober_inventaris;
                            $total_total_oktober_lainnya += $total_oktober_lainnya;

                            $total_total_november_fasum += $total_november_fasum;
                            $total_total_november_rth_rt01 += $total_november_rth_rt01;
                            $total_total_november_inventaris += $total_november_inventaris;
                            $total_total_november_lainnya += $total_november_lainnya;

                            $total_total_desember_fasum += $total_desember_fasum;
                            $total_total_desember_rth_rt01 += $total_desember_rth_rt01;
                            $total_total_desember_inventaris += $total_desember_inventaris;
                            $total_total_desember_lainnya += $total_desember_lainnya;

                            if (request('month') == '01') {
                                $total = $total_januari_fasum + $total_januari_rth_rt01 + $total_januari_inventaris + $total_januari_lainnya;
                            } else if (request('month') == '02') {
                                $total = $total_februari_fasum + $total_februari_rth_rt01 + $total_februari_inventaris + $total_februari_lainnya;
                            } else if (request('month') == '03') {
                                $total = $total_maret_fasum + $total_maret_rth_rt01 + $total_maret_inventaris + $total_maret_lainnya;
                            } else if (request('month') == '04') {
                                $total = $total_april_fasum + $total_april_rth_rt01 + $total_april_inventaris + $total_april_lainnya;
                            } else if (request('month') == '05') {
                                $total = $total_mei_fasum + $total_mei_rth_rt01 + $total_mei_inventaris + $total_mei_lainnya;
                            } else if (request('month') == '06') {
                                $total = $total_juni_fasum + $total_juni_rth_rt01 + $total_juni_inventaris + $total_juni_lainnya;
                            } else if (request('month') == '07') {
                                $total = $total_juli_fasum + $total_juli_rth_rt01 + $total_juli_inventaris + $total_juli_lainnya;
                            } else if (request('month') == '08') {
                                $total = $total_agustus_fasum + $total_agustus_rth_rt01 + $total_agustus_inventaris + $total_agustus_lainnya;
                            } else if (request('month') == '09') {
                                $total = $total_september_fasum + $total_september_rth_rt01 + $total_september_inventaris + $total_september_lainnya;
                            } else if (request('month') == '10') {
                                $total = $total_oktober_fasum + $total_oktober_rth_rt01 + $total_oktober_inventaris + $total_oktober_lainnya;
                            } else if (request('month') == '11') {
                                $total = $total_november_fasum + $total_november_rth_rt01 + $total_november_inventaris + $total_november_lainnya;
                            } else if (request('month') == '12') {
                                $total = $total_desember_fasum + $total_desember_rth_rt01 + $total_desember_inventaris + $total_desember_lainnya;
                            } else {
                                $total = $total_januari_fasum + $total_januari_rth_rt01 + $total_januari_inventaris + $total_januari_lainnya + $total_februari_fasum + $total_februari_rth_rt01 + $total_februari_inventaris + $total_februari_lainnya + $total_maret_fasum + $total_maret_rth_rt01 + $total_maret_inventaris + $total_maret_lainnya + $total_april_fasum + $total_april_rth_rt01 + $total_april_inventaris + $total_april_lainnya + $total_mei_fasum + $total_mei_rth_rt01 + $total_mei_inventaris + $total_mei_lainnya + $total_juni_fasum + $total_juni_rth_rt01 + $total_juni_inventaris + $total_juni_lainnya + $total_juli_fasum + $total_juli_rth_rt01 + $total_juli_inventaris + $total_juli_lainnya + $total_agustus_fasum + $total_agustus_rth_rt01 + $total_agustus_inventaris + $total_agustus_lainnya + $total_september_fasum + $total_september_rth_rt01 + $total_september_inventaris + $total_september_lainnya + $total_oktober_fasum + $total_oktober_rth_rt01 + $total_oktober_inventaris + $total_oktober_lainnya + $total_november_fasum + $total_november_rth_rt01 + $total_november_inventaris + $total_november_lainnya + $total_desember_fasum + $total_desember_rth_rt01 + $total_desember_inventaris + $total_desember_lainnya;
                            }

                            $total_total += $total;
                        @endphp
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->alamat ?? '-' }}</td>
                        <td>{{ $user->rt ?? '-' }}</td>
                        <td>{{ $user->rw ?? '-' }}</td>
                        <td>{{ $user->status ?? '-' }}</td>

                        @if (!request('month') || request('month') == '01')
                            <td>{{ $total_januari_fasum }}</td>
                            <td>{{ $total_januari_rth_rt01 }}</td>
                            <td>{{ $total_januari_inventaris }}</td>
                            <td>{{ $total_januari_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '02')
                            <td>{{ $total_februari_fasum }}</td>
                            <td>{{ $total_februari_rth_rt01 }}</td>
                            <td>{{ $total_februari_inventaris }}</td>
                            <td>{{ $total_februari_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '03')
                            <td>{{ $total_maret_fasum }}</td>
                            <td>{{ $total_maret_rth_rt01 }}</td>
                            <td>{{ $total_maret_inventaris }}</td>
                            <td>{{ $total_maret_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '04')
                            <td>{{ $total_april_fasum }}</td>
                            <td>{{ $total_april_rth_rt01 }}</td>
                            <td>{{ $total_april_inventaris }}</td>
                            <td>{{ $total_april_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '05')
                            <td>{{ $total_mei_fasum }}</td>
                            <td>{{ $total_mei_rth_rt01 }}</td>
                            <td>{{ $total_mei_inventaris }}</td>
                            <td>{{ $total_mei_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '06')
                            <td>{{ $total_juni_fasum }}</td>
                            <td>{{ $total_juni_rth_rt01 }}</td>
                            <td>{{ $total_juni_inventaris }}</td>
                            <td>{{ $total_juni_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '07')
                            <td>{{ $total_juli_fasum }}</td>
                            <td>{{ $total_juli_rth_rt01 }}</td>
                            <td>{{ $total_juli_inventaris }}</td>
                            <td>{{ $total_juli_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '08')
                            <td>{{ $total_agustus_fasum }}</td>
                            <td>{{ $total_agustus_rth_rt01 }}</td>
                            <td>{{ $total_agustus_inventaris }}</td>
                            <td>{{ $total_agustus_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '09')
                            <td>{{ $total_september_fasum }}</td>
                            <td>{{ $total_september_rth_rt01 }}</td>
                            <td>{{ $total_september_inventaris }}</td>
                            <td>{{ $total_september_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '10')
                            <td>{{ $total_oktober_fasum }}</td>
                            <td>{{ $total_oktober_rth_rt01 }}</td>
                            <td>{{ $total_oktober_inventaris }}</td>
                            <td>{{ $total_oktober_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '11')
                            <td>{{ $total_november_fasum }}</td>
                            <td>{{ $total_november_rth_rt01 }}</td>
                            <td>{{ $total_november_inventaris }}</td>
                            <td>{{ $total_november_lainnya }}</td>
                        @endif

                        @if (!request('month') || request('month') == '12')
                            <td>{{ $total_desember_fasum }}</td>
                            <td>{{ $total_desember_rth_rt01 }}</td>
                            <td>{{ $total_desember_inventaris }}</td>
                            <td>{{ $total_desember_lainnya }}</td>
                        @endif

                        <td>{{ $total }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center" style="position: sticky; left: 0; background-color: rgb(215, 215, 215); z-index: 1; vertical-align: middle;"></td>
                    <td class="text-center" colspan="5" style="vertical-align: middle; background-color: rgb(215, 215, 215);">Total</td>

                    @if (!request('month') || request('month') == '01')
                        <td>{{ $total_total_januari_fasum }}</td>
                        <td>{{ $total_total_januari_rth_rt01 }}</td>
                        <td>{{ $total_total_januari_inventaris }}</td>
                        <td>{{ $total_total_januari_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '02')
                        <td>{{ $total_total_februari_fasum }}</td>
                        <td>{{ $total_total_februari_rth_rt01 }}</td>
                        <td>{{ $total_total_februari_inventaris }}</td>
                        <td>{{ $total_total_februari_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '03')
                        <td>{{ $total_total_maret_fasum }}</td>
                        <td>{{ $total_total_maret_rth_rt01 }}</td>
                        <td>{{ $total_total_maret_inventaris }}</td>
                        <td>{{ $total_total_maret_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '04')
                        <td>{{ $total_total_april_fasum }}</td>
                        <td>{{ $total_total_april_rth_rt01 }}</td>
                        <td>{{ $total_total_april_inventaris }}</td>
                        <td>{{ $total_total_april_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '05')
                        <td>{{ $total_total_mei_fasum }}</td>
                        <td>{{ $total_total_mei_rth_rt01 }}</td>
                        <td>{{ $total_total_mei_inventaris }}</td>
                        <td>{{ $total_total_mei_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '06')
                        <td>{{ $total_total_juni_fasum }}</td>
                        <td>{{ $total_total_juni_rth_rt01 }}</td>
                        <td>{{ $total_total_juni_inventaris }}</td>
                        <td>{{ $total_total_juni_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '07')
                        <td>{{ $total_total_juli_fasum }}</td>
                        <td>{{ $total_total_juli_rth_rt01 }}</td>
                        <td>{{ $total_total_juli_inventaris }}</td>
                        <td>{{ $total_total_juli_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '08')
                        <td>{{ $total_total_agustus_fasum }}</td>
                        <td>{{ $total_total_agustus_rth_rt01 }}</td>
                        <td>{{ $total_total_agustus_inventaris }}</td>
                        <td>{{ $total_total_agustus_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '09')
                        <td>{{ $total_total_september_fasum }}</td>
                        <td>{{ $total_total_september_rth_rt01 }}</td>
                        <td>{{ $total_total_september_inventaris }}</td>
                        <td>{{ $total_total_september_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '10')
                        <td>{{ $total_total_oktober_fasum }}</td>
                        <td>{{ $total_total_oktober_rth_rt01 }}</td>
                        <td>{{ $total_total_oktober_inventaris }}</td>
                        <td>{{ $total_total_oktober_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '11')
                        <td>{{ $total_total_november_fasum }}</td>
                        <td>{{ $total_total_november_rth_rt01 }}</td>
                        <td>{{ $total_total_november_inventaris }}</td>
                        <td>{{ $total_total_november_lainnya }}</td>
                    @endif

                    @if (!request('month') || request('month') == '12')
                        <td>{{ $total_total_desember_fasum }}</td>
                        <td>{{ $total_total_desember_rth_rt01 }}</td>
                        <td>{{ $total_total_desember_inventaris }}</td>
                        <td>{{ $total_total_desember_lainnya }}</td>
                    @endif

                    <td>{{ $total_total }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
