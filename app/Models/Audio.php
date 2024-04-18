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
        
    public $zoodiacSigns = ['Aries', 'Tauraus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricon', 'Aquarius', 'Pices'];
    
    public $classes = ['Sun', 'Moon', 'Rising'];

    public function upload(){
        return $this->belongsTo(Upload::class, 'upload_id');
    }

}
