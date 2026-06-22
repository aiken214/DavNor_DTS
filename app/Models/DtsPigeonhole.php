<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsPigeonhole extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'section_id',
        'description',
        'is_active',
    ];

    public function section()
    {
        return $this->belongsTo(DtsSection::class, 'section_id');
    }

    public function docRoutes()
    {
        return $this->hasMany(DtsDocRoute::class, 'pigeonhole_id');
    }
}
