<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Upload;

class Audio extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'zoodiac_sign',
        'category',
        'upload_id',
        'active'
    ];
        
    public $zoodiacSigns = ['Aries', 'Tauraus', 'Gemini', 'Cancer', 'Virgo', 'Scorpio'];
    
    public $classes = ['Rising', 'Sun', 'Moon'];

    public function upload(){
        return $this->belongsTo(Upload::class, 'upload_id');
    }

}
