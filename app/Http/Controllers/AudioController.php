<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audio;
use App\Models\Upload;
use Validator;
use File;

class AudioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = Audio::with('upload')->get();
        return view('audio.index', ['data' => $data]);
    }

    public function create(){
        $audio = new Audio();
        return view('audio.create', [
            'signs' => $audio->zoodiacSigns,
            'category' => $audio->classes
        ]);
    }

    public function save(Request $request){
        try{
            
            $dataValidation = Validator::make($request->all(), [
                'zoodiac_sign' => 'required|string',
                'category' => 'required|string',
                'file' => 'mimes:wav'
            ]);

            if($dataValidation->fails()){
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }

            $file = $request->file('audio_file');
            $saveAudio = new Audio();
            $uploadFile = new Upload();

            $saveAudio->zoodiac_sign = $request->zoodiac_sign;
            $saveAudio->category = $request->category;
            $saveAudio->upload_id = $uploadFile->uploadFile($file);
            $saveAudio->save();
            
            return redirect()->route('audio.index')->with(['success' => 'File Saved!']);  
        }catch(Exception $e){
            return $e;
        }
    }
}
