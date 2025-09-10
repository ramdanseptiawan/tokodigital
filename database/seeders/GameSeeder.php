<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::insert([
            ['title'=>'GTA V','slug'=>'gta-5','price'=>299000,'cover_url'=>null],
            ['title'=>'Red Dead Redemption 2','slug'=>'rdr-2','price'=>499000,'cover_url'=>null],
            ['title'=>'Black Myth: Wukong','slug'=>'black-myth-wukong','price'=>699000,'cover_url'=>null],
           ]);
    }
}
