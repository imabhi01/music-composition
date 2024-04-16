<?php

namespace App\Http\Controllers;

use App\Models\Audio;
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
        $data = Composition::all();
        return view('composition.index', ['data' => $data]);
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

        $sunAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Sun');    
        $moonAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Moon');    
        $risingAudio = $this->getSignSelectedAudio($request->zoodiac_sign_sun, 'Rising');   

        if(!$sunAudio || !$moonAudio || !$risingAudio){
            return redirect()->back()->with('failed','Error in getting files due to missing audio file in one of the category.');
        }

        $mergedFileName = 'output-'. date('m-d-Y_His') . '.wav';

        // try {
            $ffmpeg = FFMpeg::fromDisk('local')
            ->open([$sunAudio, $moonAudio, $risingAudio])
            ->export()
            ->concatWithoutTranscoding()
            ->save('public/composition/' . $mergedFileName);
        
            // $savedFilePath = storage_path('app/composition/'. $mergedFileName);
            
            $composition = new Composition();

            $composition->zoodiac_sign_sun = $request->zoodiac_sign_sun;
            $composition->zoodiac_sign_moon = $request->zoodiac_sign_moon;
            $composition->zoodiac_sign_rising = $request->zoodiac_sign_rising;
            $composition->composed_audio_path = $mergedFileName;
            $composition->user_id = auth()->user()->id;
            $composition->save(); 

        // } catch (\Throwable $th) {
        //     return redirect()->back()->withErrors(['msg' => 'Something went Wrong!']);
        // }

        return redirect()->back()->with('success', 'Composition saved successfully!');  
    
    }

    public function getSignSelectedAudio($zoodiacSign, $category){
        $audio = Audio::where('zoodiac_sign', $zoodiacSign)
                        ->Where('category', $category)
                        ->first();
        $filePath = 'public/' . $audio->upload->file_path;
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
