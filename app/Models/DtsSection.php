<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DtsSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'mainsection_id',
        'is_record_management',
        'is_public_dropdown',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'section_user');
    }

    public function category()
    {
        return $this->belongsTo(DtsSectionCategory::class, 'category_id');
    }
}
