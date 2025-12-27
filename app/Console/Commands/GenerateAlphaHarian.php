<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\AbsensiHarian;
use App\Models\Guru;
use Carbon\Carbon;

#[Schedule(dailyAt: '20:00')]
class GenerateAlphaHarian extends Command
{
    protected $signature = 'absensi:generate-alpha-harian';
    protected $description = 'Buat otomatis Alpha harian jika guru tidak absen sama sekali';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now()->format('H:i:s');

        $gurus = Guru::all();
        $count = 0;

        foreach ($gurus as $guru) {

            $exists = AbsensiHarian::where('guru_id', $guru->id)
                ->whereDate('tanggal', $today)
                ->exists();

            if (!$exists) {
                AbsensiHarian::create([
                    'guru_id'   => $guru->id,
                    'tanggal'   => $today,
                    'jam_absen' => $now,
                    'status'    => 'Alpha',
                    'keterangan'=> 'Tidak absen hari ini, otomatis Alpha oleh sistem',
                    'foto'      => 'assets/img/blank.jpg',
                ]);

                $count++;
            }
        }

        $this->info("✅ {$count} absensi harian Alpha berhasil dibuat.");
    }
}
