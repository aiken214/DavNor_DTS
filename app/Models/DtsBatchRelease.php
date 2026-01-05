<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsBatchRelease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'createdby_id',
        'section_id',
        'release_date',
        'receiver_name',
    ];


    public function section()
    {
        return $this->belongsTo(DtsSection::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'releaseby_id');
    }
}
