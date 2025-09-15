<?php
// windows

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeWithScheduler extends Command
{
    protected $signature = 'serve:with-scheduler';
    protected $description = 'Jalankan Laravel serve dan schedule:work bersamaan';

    public function handle()
    {
        $this->info('🚀 Menjalankan Laravel dan Scheduler...');
        $serve = new Process(['php', 'artisan', 'serve']);
        $serve->start();
        $schedule = new Process(['php', 'artisan', 'schedule:work']);
        $schedule->start();
        while ($serve->isRunning() || $schedule->isRunning()) {
            if ($serve->isRunning() && $output = $serve->getIncrementalOutput()) {
                echo "[Serve] " . $output;
            }

            if ($schedule->isRunning() && $output = $schedule->getIncrementalOutput()) {
                echo "[Schedule] " . $output;
            }

            usleep(100000);
        }

        $this->info('✅ Kedua proses selesai.');
    }
}

// linux

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use Symfony\Component\Process\Process;

// class ServeWithScheduler extends Command
// {
//     protected $signature = 'serve:with-scheduler';
//     protected $description = 'Jalankan Laravel serve dan schedule:work bersamaan';

//     public function handle()
//     {
//         $this->info('🚀 Menjalankan Laravel dan Scheduler...');

//         $serve = new Process(['php', 'artisan', 'serve']);
//         $serve->setTty(true);
//         $serve->start();

//         $schedule = new Process(['php', 'artisan', 'schedule:work']);
//         $schedule->setTty(true);
//         $schedule->start();

//         foreach ([$serve, $schedule] as $process) {
//             $process->wait(function ($type, $buffer) {
//                 echo $buffer;
//             });
//         }
//     }
// }
