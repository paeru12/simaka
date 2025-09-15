<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jabatan extends Model
{
    protected $table = 'jabatans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'jabatan',
        'gapok',
        'tunjangan'
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

    public function gurus()
    {
        return $this->hasMany(Guru::class, 'jabatan_id');
    }
}
