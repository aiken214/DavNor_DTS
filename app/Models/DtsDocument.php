<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsDocument extends Model
{
    use HasFactory,  SoftDeletes;
    
    protected $fillable = [
        'tracking_code',
        'dts_doc_type_id',
        'tracking_issuedby_id',
        'fromuser_id',
        'from_section_id',
        'guest_origin_name',
        'particulars',
        'mo_yr',
        'issued_num'
    ];
    

    public static function getSystemSettingCode()
    {
        return DtsSystemSetting::find(1)?->org_dts_code ?? 'W';
    }

    public static function getSystemPadding()
    {
        return DtsSystemSetting::find(1)?->number_of_padding ?? 5;
    }

    public static function generateTrackingCode()
    {
        $now = Carbon::now();
        $year = $now->format('y'); // e.g., "26"

        $lastDocument = self::where('mo_yr', $year)
                            ->orderBy('issued_num', 'desc')
                            ->first();

        $newSequence = $lastDocument ? $lastDocument->issued_num + 1 : 1;
        $sequencePadded = str_pad($newSequence, 6, '0', STR_PAD_LEFT);

        $trackingCode = $year . '-' . $sequencePadded;

        return [
            'tracking_code' => $trackingCode,
            'mo_yr' => $year,
            'issued_num' => $newSequence
        ];
    }

    public function routes(): HasMany
    {
        return $this->hasMany(DtsDocRoute::class, 'dts_document_id');
    }

    public function docType(): BelongsTo
    {
        return $this->belongsTo(DtsDocType::class, 'dts_doc_type_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tracking_issuedby_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fromuser_id');
    }

    public function fromSection(): BelongsTo
    {
        return $this->belongsTo(DtsSection::class, 'from_section_id');
    }

    public function docStatus(): BelongsTo
    {
        return $this->belongsTo(DtsDocStatus::class, 'dts_doc_status_id');
    }

    public function routeNotes(): HasMany
    {
        return $this->hasMany(DtsRouteNote::class, 'dts_document_id');
    }

   

    // Add a new method to get the last route
    public function getLastRoute()
    {
        return $this->routes()->latest()->first();
    }

    // Add a new method to get the last route note use DtsRouteClass relationship to get the last route note
    public function getLastRouteNote()
    {
        return $this->hasOneThrough(DtsRouteNote::class, DtsDocRoute::class)->latest();
    }

    # q who is the receiver of the document
    # a the receiver of the document is the user who is the receiver of the last route
    public function getReceiverUserAttribute()
    {
        return $this->getLastRoute()?->receiverUser;
    }

}
