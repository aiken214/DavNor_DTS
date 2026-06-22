<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsDocRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dts_document_id', 'previous_route_id', 'from_user_id', 'from_section_id',
        'for_section_id', 'for_user_id', 'receiver_user_id', 'actions_taken',
        'actedby_user_id', 'date_forwarded', 'date_accepted', 'date_acted', 'status_id', 'route_purpose',
        'io_type', 'fwd_io_type','end_remarks', 'deferred_reason', 'deferred_date', 'defer_until', 'out_released_to',
        'logbook_page', 'del_reason','autoaction_date', 'pigeonhole_id'
        

    ];

    public function document()
    {
        return $this->belongsTo(DtsDocument::class, 'dts_document_id');
    }

    public function previousRoute()
    {
        return $this->belongsTo(DtsDocRoute::class, 'previous_route_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function fromSection()
    {
        return $this->belongsTo(DtsSection::class, 'from_section_id');
    }

    public function forSection()
    {
        return $this->belongsTo(DtsSection::class, 'for_section_id');
    }

    public function forUser()
    {
        return $this->belongsTo(User::class, 'for_user_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function actedByUser()
    {
        return $this->belongsTo(User::class, 'actedby_user_id');
    }

    public function docType()
{
    return $this->hasOneThrough(
        DtsDocType::class,
        DtsDocument::class,
        'id', // Foreign key on the DtsDocument table...
        'id', // Foreign key on the DtsDocType table...
        'dts_document_id', // Local key on the DtsDocRoute table...
        'dts_doc_type_id' // Local key on the DtsDocument table...
    );
}

// create me a one to many relationship with DtsRouteNote
public function routeNotes()
{
    return $this->hasMany(DtsRouteNote::class);

}
//create me  amethod to get the last DTSRouteNote
public function lastRouteNote()
{
    return $this->hasOne(DtsRouteNote::class)->latest();
}

public function pigeonhole()
{
    return $this->belongsTo(DtsPigeonhole::class, 'pigeonhole_id');
}

}
