<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use FFMpeg\FFMpeg;

class CompositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $audio = new Audio();
        return view('composition.create', [
            'signs' => $audio->zoodiacSigns,
            'category' => $audio->classes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sunAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Sun');    
        $moonAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Moon');    
        $risingAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Rising');   

        if(!$sunAudio || !$moonAudio || !$risingAudio){
            return redirect()->route('composition.create')->with('failed','Error in getting files due to missing audio file in one of the category.');
        }

        $wavs = [$sunAudio, $moonAudio, $risingAudio];  
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => 'C:/FFmpeg/bin/ffmpeg.exe',
            'ffprobe.binaries' => 'C:/FFmpeg/bin/ffprobe.exe'
        ]);
        dd($ffmpeg);

        $mergingAudio = $this->joinwavs($wavs);
        dd($mergingAudio);

        dd(public_path('storage') . '/' . $sunAudio->file_path, public_path('storage') . '/' . $moonAudio->file_path);        
    }

    public function joinwavs($wavs){
        $fields = join('/',array( 'H8ChunkID', 'VChunkSize', 'H8Format',
                                  'H8Subchunk1ID', 'VSubchunk1Size',
                                  'vAudioFormat', 'vNumChannels', 'VSampleRate',
                                  'VByteRate', 'vBlockAlign', 'vBitsPerSample' ));
        $data = '';
        foreach($wavs as $wav){
            $fp     = fopen($_SERVER['DOCUMENT_ROOT'] . '/posts/filename.php', $wav);
            $header = fread($fp,36);
            $info   = unpack($fields,$header);
            // read optional extra stuff
            if($info['Subchunk1Size'] > 16){
                $header .= fread($fp,($info['Subchunk1Size']-16));
            }
            // read SubChunk2ID
            $header .= fread($fp,4);
            // read Subchunk2Size
            $size  = unpack('vsize',fread($fp, 4));
            $size  = $size['size'];
            // read data
            $data .= fread($fp,$size);
        }
        return $header.pack('V',strlen($data)).$data;
    }

    public function getSignSelectedAudio($zoodiacSign, $category){
        $audio = Audio::where('zoodiac_sign', $zoodiacSign)
                        ->Where('category', $category)
                        ->first();
        return $audio;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function show(Composition $composition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function edit(Composition $composition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Composition $composition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Composition  $composition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Composition $composition)
    {
        //
    }
}
