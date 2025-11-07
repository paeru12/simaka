<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guru extends Model
{
    protected $table = 'gurus';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'jabatan_id',
        'nik',
        'nama',
        'jk',
        'no_hp',
        'foto'
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
    public function users() 
    {
        return $this->hasOne(User::class, 'guru_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    public function qrguru()
    {
        return $this->hasOne(QrGuru::class, 'guru_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'guru_id');
    }
}
