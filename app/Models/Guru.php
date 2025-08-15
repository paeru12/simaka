<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guru extends Model
{
    protected $table = 'gurus'; 
    public $incrementing = false; // non-integer ID
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'nik', 'nama', 'jenis_kelamin', 'alamat', 'no_hp', 'status_aktif', 'foto'
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

    // Relasi ke Jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }

    // Relasi ke Absensi
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'guru_id');
    }

    // Relasi ke Penggajian
    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'guru_id');
    }
}
