<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtsDocStatus extends Model
{
    use HasFactory, SoftDeletes;

    //create many to one relationship with DtsDocument
    public function document()
    {
        return $this->belongsTo(DtsDocument::class);
    }
    
  

}
