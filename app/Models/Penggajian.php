<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Penggajian extends Model
{
    protected $table = 'penggajians';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'guru_id', 'bulan', 'tahun', 'total_jam_mengajar', 'total_hadir', 'total_izin',
        'total_sakit', 'total_alpha', 'total_terlambat', 'gaji_per_jam', 'potongan', 'total_gaji'
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
}
