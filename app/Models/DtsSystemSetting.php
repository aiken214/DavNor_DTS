<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtsSystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'org_dts_code', 
        'custom_system_name', 
        'number_of_padding', 
        'allow_auto_park', 
        'auto_parkdays', 
        'logo_at', 
        'logo_light_at', 
        'login_image_at', 
        'allow_fileupload',
        'allow_guest_docform'
    ];

    public function organization()
    {
        return $this->belongsTo(DtsOrganization::class, 'org_dts_code', 'dts_code');
    }


   
}
