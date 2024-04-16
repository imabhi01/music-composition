<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'zoodiac_sign_sun',
        'zoodiac_sign_moon',
        'zoodiac_sign_rising',
        'user_id',
        'active'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
