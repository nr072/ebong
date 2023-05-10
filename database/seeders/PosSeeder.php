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
            ['en' => 'verb', 'short' => 'v.'],
            ['en' => 'noun', 'short' => 'n.'],
            ['en' => 'proper noun', 'short' => 'prop. n.'],
            ['en' => 'adjective', 'short' => 'adj.'],
            ['en' => 'adverb', 'short' => 'adv.'],
            ['en' => 'pronoun', 'short' => 'pron.'],
            ['en' => 'determiner', 'short' => 'det.'],
            ['en' => 'onomatopoeia', 'short' => 'onoma.'],
            ['en' => 'preposition', 'short' => 'prep.'],
            ['en' => 'conjunction', 'short' => 'conj.'],
            ['en' => 'interjection', 'short' => 'interj.'],
        ]);
    }
}
