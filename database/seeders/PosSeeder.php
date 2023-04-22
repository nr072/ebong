<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Needs to be run before deployment on production.
        DB::table('poses')->insert([
            ['en' => 'noun'],
            ['en' => 'proper noun'],
            ['en' => 'pronoun'],
            ['en' => 'adjective'],
            ['en' => 'verb'],
            ['en' => 'adverb'],
            ['en' => 'preposition'],
            ['en' => 'conjunction'],
            ['en' => 'interjection'],
            ['en' => 'determiner'],
        ]);
    }
}
