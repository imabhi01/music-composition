<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZoodiacSign;

class ZoodiacSignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ZoodiacSign::create([
            'zoodiac_sign'=>'Aries',
        ]);
        
        ZoodiacSign::create([
            'zoodiac_sign'=>'Tauras',
        ]);
        
        ZoodiacSign::create([
            'zoodiac_sign'=>'Gemini',
        ]);
        
    }
}
