<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class AbsensiHarian extends Model
{ 
    use HasFactory;
    protected $table = 'absensi_harians';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'guru_id',
        'tanggal',
        'jam_datang',
        'jam_pulang',
        'status',
        'foto',
        'lokasi',
        'keterangan',
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
        return $this->belongsTo(Guru::class);
    }
}
