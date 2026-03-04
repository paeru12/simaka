<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsenExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;
    protected $search;

    public function __construct($bulan, $tahun, $search)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->search = $search;
    }

    public function collection()
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        $rekapHarian = DB::table('absensi_harians')
            ->select(
                'guru_id',
                DB::raw("SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN status='Izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN status='Sakit' THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN status='Alpha' THEN 1 ELSE 0 END) as total_alpha")
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('guru_id');

        $rekapMapel = DB::table('absensis')
            ->select(
                'guru_id',
                DB::raw("SUM(CASE WHEN status='Hadir' THEN 1 ELSE 0 END) as total_hadir_mapel")
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('guru_id');

        $query = DB::table('gurus as g')
            ->leftJoinSub($rekapHarian, 'harian', 'g.id', '=', 'harian.guru_id')
            ->leftJoinSub($rekapMapel, 'mapel', 'g.id', '=', 'mapel.guru_id')
            ->join('jabatans', 'jabatans.id', '=', 'g.jabatan_id')
            ->select(
                'g.nama',
                'jabatans.jabatan',

                DB::raw('COALESCE(harian.total_hadir,0) as total_hadir'),
                DB::raw('COALESCE(harian.total_izin,0) as total_izin'),
                DB::raw('COALESCE(harian.total_sakit,0) as total_sakit'),
                DB::raw('COALESCE(harian.total_alpha,0) as total_alpha'),

                DB::raw('COALESCE(mapel.total_hadir_mapel,0) as total_hadir_mapel'),

                DB::raw('
                COALESCE(harian.total_hadir,0) +
                COALESCE(mapel.total_hadir_mapel,0)
                as total_kehadiran
            ')
            );

        if ($this->search) {
            $query->where('g.nama', 'like', "%{$this->search}%");
        }

        return $query->orderBy('g.nama')->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Jabatan',
            'Total Hadir Harian',
            'Total Izin',
            'Total Sakit',
            'Total Alpha',
            'Total Hadir Mapel',
            'Total Kehadiran'
        ];
    }
}
