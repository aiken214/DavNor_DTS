<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtsOrganization extends Model
{
    use HasFactory;
     // Define the relationship with DtsOrganization
    public function systemSetting(){
        return $this->hasOne(DtsSystemSetting::class, 'org_dts_code', 'dts_code');
    }
}
