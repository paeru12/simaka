<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jurusan extends Model
{
    protected $table = 'jurusans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama'
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

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
