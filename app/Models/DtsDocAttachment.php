<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DtsDocAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'file_at',
        'has_doctracking',
        'dts_document_id',
    ];
}
