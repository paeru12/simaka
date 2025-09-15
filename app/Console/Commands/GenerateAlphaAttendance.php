<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use App\Models\Absensi;
use Carbon\Carbon;

class GenerateAlphaAttendance extends Command
{
    protected $signature = 'absensi:generate-alpha';
    protected $description = 'Buat data absensi Alpha otomatis untuk guru yang tidak hadir';

    public function handle()
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $jadwals = Jadwal::where('hari', $today->locale('id')->dayName)->get();

        $count = 0;

        foreach ($jadwals as $jadwal) {
            $jadwalSelesai = Carbon::parse($jadwal->jam_selesai);
            if ($now->lt($jadwalSelesai->copy()->addMinute())) {
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
                    'keterangan' => 'Tidak hadir, otomatis alpha oleh sistem',
                    'foto'      => 'assets/img/blank.jpg',
                ]);
                $count++;
            }
        }

        $this->info("✅ {$count} absensi Alpha berhasil dibuat.");
    }
}
