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

}
