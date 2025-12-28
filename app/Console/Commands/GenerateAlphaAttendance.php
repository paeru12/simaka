<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use Carbon\Carbon;

#[Schedule(every: '5 minutes')]
class GenerateAlphaAttendance extends Command
{
    protected $signature = 'absensi:generate-alpha-jadwal';
    protected $description = 'Alpha mapel otomatis berdasarkan jadwal dan absensi harian guru';

    public function handle()
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        $hariIni = ucfirst($today->locale('id')->dayName);

        $jadwals = Jadwal::where('hari', $hariIni)->get();

        $count = 0;
        $skipped = 0;

        foreach ($jadwals as $jadwal) {

            $batasAlpha = Carbon::parse($jadwal->jam_selesai)->addMinutes(30);

            if ($now->lt($batasAlpha)) {
                continue;
            }

            $alphaHarian = AbsensiHarian::where('guru_id', $jadwal->guru_id)
                ->whereDate('tanggal', $today)
                ->where('status', 'Alpha')
                ->exists();

            if ($alphaHarian) {
                $skipped++;
                continue;
            }

            $absensiExists = Absensi::where('jadwal_id', $jadwal->id)
                ->where('guru_id', $jadwal->guru_id)
                ->whereDate('tanggal', $today)
                ->exists();

            if (!$absensiExists) {
                Absensi::create([
                    'jadwal_id' => $jadwal->id,
                    'mapel_id'  => $jadwal->mapel_id,
                    'guru_id'   => $jadwal->guru_id,
                    'tanggal'   => $today,
                    'jam_absen' => $now->format('H:i:s'),
                    'status'    => 'Alpha',
                    'keterangan'=> 'Tidak hadir, Alpha mapel otomatis',
                    'foto'      => 'assets/img/blank.jpg',
                ]);

                $count++;
            }
        }

        $this->info("✅ {$count} Alpha mapel dibuat | ⏭ {$skipped} dilewati (Alpha harian)");
    }
}
