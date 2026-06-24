<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsBatchSubmit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'batch_code',
        'createdby_id',
        'section_id',
        'submittedby_id',
        'submit_date',
    ];

    protected static function booted()
    {
        static::created(function ($batch) {
            if (!$batch->batch_code) {
                $batch->batch_code = 'BS-' . str_pad($batch->id, 5, '0', STR_PAD_LEFT);
                $batch->saveQuietly();
            }
        });
    }

    public function section()
    {
        return $this->belongsTo(DtsSection::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submittedby_id');
    }

    public function docRoutes()
    {
        return $this->belongsToMany(DtsDocRoute::class, 'dts_batch_submit_doc_route', 'batch_submit_id', 'doc_route_id');
    }
}
