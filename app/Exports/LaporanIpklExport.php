<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanIpklExport implements FromView, WithStyles, WithEvents, WithColumnFormatting
{
    public function columnFormats(): array
    {
        return [
            'G' => '"Rp" #,##0',
            'I' => '"Rp" #,##0',
            'K' => '"Rp" #,##0',
            'M' => '"Rp" #,##0',
            'O' => '"Rp" #,##0',
            'Q' => '"Rp" #,##0',
            'S' => '"Rp" #,##0',
            'U' => '"Rp" #,##0',
            'W' => '"Rp" #,##0',
            'Y' => '"Rp" #,##0',
            'AA' => '"Rp" #,##0',
            'AC' => '"Rp" #,##0',
            'AE' => '"Rp" #,##0',
        ];
    }

    public function view(): View
    {
        if (request()->input('year')) {
            $year = request()->input('year');
        } else {
            $year = date('Y');
        }

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
        ->get();

        return view('ipkl.laporanIpklExport', [
            'users' => $users,
            'year' => $year,
        ]);
    }



    public function styles(Worksheet $sheet)
    {
        $year = request()->input('year') ?? date('Y');
        $search = request()->input('search');
        $rt = request()->input('rt');
        $rw = request()->input('rw');
        $status = request()->input('status');
        $month = request()->input('month');
        $status_transaksi = request()->input('status_transaksi');

        $totalUsers = User::when($search, function ($query) use ($search) {
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
        ->count();

        // Baris awal tabel (header) adalah 1
        $startRow = 1;
        $endRow = $totalUsers + 2; // +1 untuk header, +1 untuk footer

        // Tentukan range tabel sesuai kondisi month
        if ($month) {
            $tableRange = "A{$startRow}:H" . ($endRow - 1); // hanya sampai kolom H
        } else {
            $tableRange = "A{$startRow}:AE" . ($endRow - 1); // sampai kolom AE
        }

        $footerTotalRange = "A{$endRow}:F{$endRow}";
        $footerOtherRange = $month
            ? "G{$endRow}:H{$endRow}"
            : "G{$endRow}:AE{$endRow}";

        return [
            // Header styling sesuai kondisi month
            ($month ? 'A1:H1' : 'A1:AE1') => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D7D7D7'],
                ],
            ],

            // Border untuk data tabel sesuai kondisi
            $tableRange => [
                'alignment' => ['horizontal' => 'center'],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],

            // Footer bagian total
            $footerTotalRange => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '808080'],
                ],
            ],

            // Footer bagian bulan dan total lainnya
            $footerOtherRange => [
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D7D7D7'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }





    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Daftar kolom dari F sampai AD (total 25 kolom)
                $columns = [
                    'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
                    'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
                    'Z', 'AA', 'AB', 'AC', 'AD', 'AE'
                ];

                // Set lebar kolom secara manual
                foreach ($columns as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(false);
                    $event->sheet->getColumnDimension($column)->setWidth(20); // ~200px
                }

                // Kolom A sampai E tetap auto-size
                foreach (range('A', 'E') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

}
