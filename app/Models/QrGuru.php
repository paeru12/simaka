<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class QrGuru extends Model
{
    protected $table = 'qr_guru';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'guru_id', 'token', 'aktif', 'file'
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
