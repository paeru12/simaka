<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\Jadwal;
use App\Models\Absensi;
use Carbon\Carbon;

#[Schedule(every: '5 minutes')]
class GenerateAlphaAttendance extends Command
{
    protected $signature = 'absensi:generate-alpha-jadwal';
    protected $description = 'Alpha mapel otomatis berdasarkan jadwal';

    public function handle()
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        if ($today->isSunday()) {
            $this->info('⏭ Hari Minggu, tidak ada Alpha mapel.');
            return;
        }

        $hariIni = ucfirst($today->locale('id')->dayName); 

        $jadwals = Jadwal::where('hari', $hariIni)->get();

        $count = 0;

        foreach ($jadwals as $jadwal) {

            $batasAlpha = Carbon::parse($jadwal->jam_selesai)->addMinutes(15);

            if ($now->lt($batasAlpha)) {
                continue;
            }

            $absensiExists = Absensi::where('jadwal_id', $jadwal->id)
                ->whereDate('tanggal', $today)
                ->exists();

            if ($absensiExists) {
                continue;
            }

            Absensi::create([
                'jadwal_id' => $jadwal->id,
                'mapel_id'  => $jadwal->mapel_id,
                'guru_id'   => $jadwal->guru_id,
                'tanggal'   => $today->toDateString(),
                'jam_absen' => $now->format('H:i:s'),
                'status'    => 'Alpha',
                'keterangan' => 'Tidak hadir sesuai jadwal, Alpha otomatis oleh sistem',
                'foto'      => 'assets/img/blank.jpg',
            ]);

            $count++;
        }

        $this->info("✅ {$count} Alpha mapel berhasil dibuat.");
    }
}
