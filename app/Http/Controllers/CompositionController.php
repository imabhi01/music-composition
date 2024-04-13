<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => exec('which ffmpeg'),
            'ffprobe.binaries' => exec('which ffprobe')
        ]);

        $audio1 = $ffmpeg->open(public_path(). '/storage/' .$sunAudio->upload->file_path);
        $audio2 = $ffmpeg->open(public_path(). '/storage/' .$moonAudio->upload->file_path);
        $audio3 = $ffmpeg->open(public_path(). '/storage/' .$risingAudio->upload->file_path);

        // Path to save the merged audio file
        $outputFile = storage_path('app/audio/merged.wav');

        // Temporary directory to store intermediate files
        $tempDir = storage_path('temp');

        // Ensure the temporary directory exists
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Copy input audio files to temporary directory
        $tempAudioFile1 = $tempDir . '/' . $sunAudio->upload->file_path;
        $tempAudioFile2 = $tempDir . '/' . $moonAudio->upload->file_path;
        copy(public_path(). '/storage/' .$sunAudio->upload->file_path, $tempAudioFile1);
        copy(public_path(). '/storage/' .$moonAudio->upload->file_path, $tempAudioFile2);

        // Concatenate audio files using FFMpeg CLI
        $process = new Process([
            'ffmpeg',
            '-i', $tempAudioFile1,
            '-i', $tempAudioFile2,
            '-filter_complex', 'concat=n=2:v=0:a=1',
            $outputFile,
            '-y' // Overwrite output file if exists
        ]);
        $process->mustRun();

        // Remove temporary files and directory
        unlink($tempAudioFile1);
        unlink($tempAudioFile2);
        rmdir($tempDir);

        // Use Storage facade to return the merged audio file
        return Storage::download('audio/merged.wav');







        // $outputFile = storage_path('app/audio/merged.wav');
        
        // Merge audio files
        // $finalAudio = $ffmpeg->concat([$audio1, $audio2]);
        // $finalAudio = $audio1->concat(array($audio2));

        // dd($finalAudio);
        // Save the merged audio file
        // $finalAudio->save($outputFile);
        // $finalAudio->export()
        //     ->inFormat($format)
        //     ->save($outputFile);
        // Use Storage facade to return the merged audio file
        // return Storage::download('public/uploads/ELAKDnd2svAtAS4bZkga89JR6Yoz3IRge852Qbql.wav');
        // return Storage::download('public/uploads/ELAKDnd2svAtAS4bZkga89JR6Yoz3IRge852Qbql.wav');

        // $finalAudio = $audio1->concat(array($audio2));

        // // Save the merged audio file
        // $fileName = time() . '.wav';
        // $finalAudio->saveFromSameCodecs(storage_path('app/merged/'. $fileName));
        // dd($finalAudio);
        // return response()->download($finalAudio, 'merged.wav');

        // $mergingAudio = $this->joinwavs($wavs);
        // dd($mergingAudio);

        // dd(public_path('storage') . '/' . $sunAudio->file_path, public_path('storage') . '/' . $moonAudio->file_path);        
    }

    public function joinwavs($wavs){

       

        // $fields = join('/',array( 'H8ChunkID', 'VChunkSize', 'H8Format',
        //                           'H8Subchunk1ID', 'VSubchunk1Size',
        //                           'vAudioFormat', 'vNumChannels', 'VSampleRate',
        //                           'VByteRate', 'vBlockAlign', 'vBitsPerSample' ));
        // $data = '';
        // foreach($wavs as $wav){
        //     $fp     = fopen($_SERVER['DOCUMENT_ROOT'] . '/' . $wav->upload->file_path, $wav);
        //     $header = fread($fp,36);
        //     $info   = unpack($fields,$header);
        //     // read optional extra stuff
        //     if($info['Subchunk1Size'] > 16){
        //         $header .= fread($fp,($info['Subchunk1Size']-16));
        //     }
        //     // read SubChunk2ID
        //     $header .= fread($fp,4);
        //     // read Subchunk2Size
        //     $size  = unpack('vsize',fread($fp, 4));
        //     $size  = $size['size'];
        //     // read data
        //     $data .= fread($fp,$size);
        // }
        // return $header.pack('V',strlen($data)).$data;
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
