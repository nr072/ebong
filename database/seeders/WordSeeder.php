<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('words')->insert([
            ['en' => 'aardvark'],
            ['en' => 'aardvarks'],
            ['en' => 'abaci'],
            ['en' => 'aback'],
            ['en' => 'abacus'],
            ['en' => 'abacuses'],
            ['en' => 'abaft'],
            ['en' => 'abalone'],
            ['en' => 'abalones'],
            ['en' => 'abandon'],
            ['en' => 'abandoned'],
            ['en' => 'abandoning'],
            ['en' => 'abandonment'],
            ['en' => 'abandons'],
            ['en' => 'abase'],
            ['en' => 'abased'],
            ['en' => 'abasement'],
            ['en' => 'abases'],
            ['en' => 'abash'],
            ['en' => 'abashed'],
            ['en' => 'abashes'],
            ['en' => 'abashing'],
            ['en' => 'abasing'],
            ['en' => 'abate'],
            ['en' => 'abated'],
            ['en' => 'abatement'],
            ['en' => 'abates'],
            ['en' => 'abating'],
            ['en' => 'abattoir'],
            ['en' => 'abattoirs'],
            ['en' => 'abbess'],
            ['en' => 'abbesses'],
            ['en' => 'abbey'],
            ['en' => 'abbeys'],
            ['en' => 'abbot'],
            ['en' => 'abbots'],
        ]);
    }
}
