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
            
            return redirect()->route('audio.index')->with(['success' => 'Audio File Saved!']);  
        }catch(Exception $e){
            return $e;
        }
    }

    public function edit($id){
        $settings = new Audio();
        $audio = Audio::find($id);

        return view('audio.edit', [
            'audio' => $audio,
            'signs' => $settings->zoodiacSigns,
            'category' => $settings->classes
        ]);
    }

    public function update(Request $request, $id){
        try{
            $dataValidation = Validator::make($request->all(), [
                'zoodiac_sign' => 'required|string',
                'category' => 'required|string',
                'file' => 'mimes:wav'
            ]);

            if($dataValidation->fails()){
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }
            
            $uploadFile = new Upload();

            $saveAudio = Audio::find($id);
            $previousAudioFilePath = $saveAudio->upload->file_path;

            $file = $request->file('audio_file');
            $saveAudio->zoodiac_sign = $request->zoodiac_sign;
            $saveAudio->category = $request->category;
            $saveAudio->upload_id = $uploadFile->uploadFile($file);

            if($saveAudio->upload_id){
                unlink(public_path('storage') . '/'. $previousAudioFilePath); // Unlinking the previous audio file : deleting it.
            }

            $saveAudio->save();
            
            return redirect()->route('audio.index')->with(['success' => 'Audio Updated!']);  
        }catch(Exception $e){
            return $e;
        }
    }

    public function destroy($id){
        $audio = Audio::find($id);
        $previousAudioFilePath = $audio->upload->file_path;

        if($previousAudioFilePath){
            unlink(public_path('storage') . '/'. $previousAudioFilePath); // Unlinking the previous audio file : deleting it.
        }

        $audio->delete();
        return redirect()->route('audio.index')->with('success', 'Audio deleted successfully');
    }
}
