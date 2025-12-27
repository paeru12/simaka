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
    protected $description = 'Buat otomatis absensi Alpha jadwal untuk guru yang tidak hadir';

    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();

        // Samakan format dengan database
        $hariIni = ucfirst($today->locale('id')->dayName);

        $jadwals = Jadwal::where('hari', $hariIni)->get();

        $count = 0;

        foreach ($jadwals as $jadwal) {

            $jadwalSelesai = Carbon::parse($jadwal->jam_selesai);

            // Skip jadwal yang belum selesai + 1 menit
            if ($now->lt($jadwalSelesai->copy()->addMinute())) {
                continue;
            }

            // Check apakah guru sudah absen
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
                    'keterangan' => 'Tidak hadir, otomatis alpha jadwal oleh sistem',
                    'foto'      => 'assets/img/blank.jpg',
                ]);

                $count++;
            }
        }

        $this->info("✅ {$count} absensi Alpha jadwal berhasil dibuat.");
    }
}
