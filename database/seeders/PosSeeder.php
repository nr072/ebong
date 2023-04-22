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
            ['en' => 'verb'],
            ['en' => 'noun'],
            ['en' => 'proper noun'],
            ['en' => 'adjective'],
            ['en' => 'adverb'],
            ['en' => 'pronoun'],
            ['en' => 'determiner'],
            ['en' => 'onomatopoeia'],
            ['en' => 'preposition'],
            ['en' => 'conjunction'],
            ['en' => 'interjection'],
        ]);
    }
}
