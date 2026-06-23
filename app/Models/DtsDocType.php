<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class DtsDocType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
        'for_guest',
        'menu_sequence',
    ];

    public function documents()
    {
        return $this->hasMany(DtsDocument::class, 'dts_doc_type_id');
    }
}
