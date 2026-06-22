<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsGuestdocument extends Model
{
    use HasFactory,  SoftDeletes;

    protected $fillable = [
        'doctype_id',
        'doc_description',
        'school_id',
        'organization',
        'from_section_id',
        'submittedby',
        'submitter_id',
        'receiver_section_id',
        'intended_receiver_id',
        'actions_needed',
        'is_accepted',
        'is_active',
    ];

    public function docType(): BelongsTo
    {
        return $this->belongsTo(DtsDocType::class, 'doctype_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function fromSection(): BelongsTo
    {
        return $this->belongsTo(DtsSection::class, 'from_section_id');
    }

    public function receiverSection(): BelongsTo
    {
        return $this->belongsTo(DtsSection::class, 'receiver_section_id');
    }

    public function intendedReceiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'intended_receiver_id');
    }

}
