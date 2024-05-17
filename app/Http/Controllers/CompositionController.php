<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\User;
use App\Models\Composition;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Validator;

class CompositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        return view('composition.index', ['data' => $user->compositions]);
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
        $dataValidation = Validator::make($request->all(), [
            'zoodiac_sign_sun' => 'required|string',
            'zoodiac_sign_moon' => 'required|string',
            'zoodiac_sign_rising' => 'required|string',
        ]);

        if($dataValidation->fails()){
            return redirect()->back()->withErrors($dataValidation)->withInput();
        }

        try {

            $sunAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Sun');    
            $moonAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Moon');    
            $risingAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Rising');   
            
            if(!$sunAudio || !$moonAudio || !$risingAudio){
                return redirect()->back()->with('failed','Error in getting files due to missing audio file in one of the category.');
            }
    
            $mergedFileName = 'output-'. date('m-d-Y_His') . '.wav';

            $background1Path = public_path('storage/composition/background1.wav');
            $background2Path = public_path('storage//composition/background2.wav');
            $outputPath = public_path('storage/composition/' . $mergedFileName);

            // Ensure the input files exist
            if (!file_exists($sunAudio) || !file_exists($moonAudio) || !file_exists($risingAudio)) {
                return redirect()->back()->withErrors(['msg' => 'Something went Wrong! Please try again!']);
            }

            // Reduce the volume of the second and third audio files (background)
            $reduceVolumeCommand1 = "ffmpeg -i {$moonAudio} -af \"volume=0.5\" {$background1Path}";
            shell_exec($reduceVolumeCommand1);

            $reduceVolumeCommand2 = "ffmpeg -i {$risingAudio} -af \"volume=0.5\" {$background2Path}";
            shell_exec($reduceVolumeCommand2);

            // Check if the volume reduction was successful
            if (!file_exists($background1Path) || !file_exists($background2Path)) {
                return redirect()->back()->withErrors(['msg' => 'Something went Wrong! Please try again!']);
            }

            // Merge the three audio files
            $mergeCommand = "ffmpeg -y -i {$sunAudio} -i {$background1Path} -i {$background2Path} -filter_complex '[0:a][1:a][2:a]amerge=inputs=3[a]' -map '[a]' -c:a pcm_s16le {$outputPath}";
            shell_exec($mergeCommand);

            if(!$outputPath){
                unlink($background1Path);
                unlink($background2Path);
                return redirect()->back()->withErrors(['msg' => 'Something went Wrong with the merging the file! Please try again!']);
            }

            $composition = new Composition();

            $composition->zoodiac_sign_sun = $request->zoodiac_sign_sun;
            $composition->zoodiac_sign_moon = $request->zoodiac_sign_moon;
            $composition->zoodiac_sign_rising = $request->zoodiac_sign_rising;
            $composition->composed_audio_path = $mergedFileName;
            $composition->user_id = auth()->user()->id;
            $composition->save();

            unlink($background1Path);
            unlink($background2Path);

            return redirect()->back()->with('success', 'Composition saved successfully!');  

        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => 'Something went Wrong! Please upload audio files for all the Zoodiac Signs from audio Settings first!']);
        }
    }

    public function getSignSelectedAudio($zoodiacSign, $category){
        $audio = Audio::where('zoodiac_sign', $zoodiacSign)
                        ->Where('category', $category)
                        ->first();
        if(!$audio){
            return redirect()->back()->withErrors(['msg' => 'Please upload audio files for all the Zoodiac Signs from audio Settings first!']);
        }
        // $filePath = 'public/' . $audio->upload->file_path;
        $filePath = public_path('storage/' . $audio->upload->file_path);
        return $filePath;
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
