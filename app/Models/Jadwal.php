<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jadwal extends Model 
{
    protected $table = 'jadwals';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'guru_id','ruangan_id', 'mapel_id','kelas_id', 'hari', 'jam_mulai', 'jam_selesai'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }
}
