<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Audio;

class Upload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function uploadFile($file){
        $fileName = $file->getClientOriginalName();
        $filePath = $file->store('uploads', 'public');

        $this->file_name = $fileName;
        $this->file_original_name = $file->getClientOriginalName();
        $this->file_path = $filePath;
        $this->save();
        return $this->id;
    }
}
