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
        'guest_name',
        'particulars',
        'mo_yr',
        'issued_num'
    ];
    

    public static function getSystemSettingCode()
    {
        $setting = DtsSystemSetting::find(1)->select('org_dts_code')->first();
        return $setting ? $setting->org_dts_code : 'W';
    }

    public static function getSystemPadding()
    {
        $setting = DtsSystemSetting::find(1)->select('number_of_padding')->first();
        return $setting ? $setting->number_of_padding : 5;
    }

    public static function generateTrackingCode()
    {
        $now = Carbon::now();
        $monthYear = $now->format('my'); // e.g., "0724"
        
        // Fetch the highest issued_num for the current month and year
        $lastDocument = self::where('mo_yr', $monthYear)
                            ->orderBy('issued_num', 'desc')
                            ->first();

        $code = self::getSystemSettingCode();
        $padding = self::getSystemPadding();
        $newSequence = $lastDocument ? $lastDocument->issued_num + 1 : 1;
        
        // Pad the sequence number with leading zeros to make it 5 digits
        $sequencePadded = str_pad($newSequence, $padding, '0', STR_PAD_LEFT);
        
        // Combine month-year, system setting code, and sequence to form the new tracking code
        $trackingCode = $monthYear . $code . $sequencePadded;
        
        return [
            'tracking_code' => $trackingCode,
            'mo_yr' => $monthYear,
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
        return $this->getLastRoute()->receiverUser;
    }

}
