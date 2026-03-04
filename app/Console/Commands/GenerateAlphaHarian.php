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
        $today = Carbon::today();
        $now   = Carbon::now()->format('H:i:s');

        $isSunday = $today->isSunday();

        $gurus = Guru::with(['jadwals', 'jabatan'])->get();

        $count = 0;

        foreach ($gurus as $guru) {

            $exists = AbsensiHarian::where('guru_id', $guru->id)
                ->whereDate('tanggal', $today)
                ->exists();

            if ($exists) continue;

            $jabatan = strtolower(optional($guru->jabatan)->jabatan);

            $buatAlpha = false;

            if ($jabatan === 'guru') {

                $hariIni = ucfirst($today->translatedFormat('l'));

                $adaJadwal = $guru->jadwals()
                    ->where('hari', $hariIni)
                    ->exists();

                if ($adaJadwal) {
                    $buatAlpha = true;
                }
            } else {

                if (!$isSunday) {
                    $buatAlpha = true;
                }
            }

            if ($buatAlpha) {

                AbsensiHarian::create([
                    'guru_id'   => $guru->id,
                    'tanggal'   => $today->toDateString(),
                    'jam_absen' => $now,
                    'status'    => 'Alpha',
                    'keterangan' => 'Tidak absen hari ini, otomatis Alpha oleh sistem',
                    'foto'      => 'assets/img/blank.jpg',
                ]);

                $count++;
            }
        }

        $this->info("✅ {$count} absensi harian Alpha berhasil dibuat.");
    }
}
