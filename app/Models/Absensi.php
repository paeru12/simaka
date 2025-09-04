<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Absensi extends Model
{
    protected $table = 'absensis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'jadwal_id', 'mapel_id', 'user_id', 'tanggal', 'jam_absen', 'status', 'keterangan', 'foto'
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

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

}
