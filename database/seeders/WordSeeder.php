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
            ['en' => 'aardvark', 'pos_id' => rand(1, 11)],
            ['en' => 'aardvarks', 'pos_id' => rand(1, 11)],
            ['en' => 'abaci', 'pos_id' => rand(1, 11)],
            ['en' => 'aback', 'pos_id' => rand(1, 11)],
            ['en' => 'abacus', 'pos_id' => rand(1, 11)],
            ['en' => 'abacuses', 'pos_id' => rand(1, 11)],
            ['en' => 'abaft', 'pos_id' => rand(1, 11)],
            ['en' => 'abalone', 'pos_id' => rand(1, 11)],
            ['en' => 'abalones', 'pos_id' => rand(1, 11)],
            ['en' => 'abandon', 'pos_id' => rand(1, 11)],
            ['en' => 'abandoned', 'pos_id' => rand(1, 11)],
            ['en' => 'abandoning', 'pos_id' => rand(1, 11)],
            ['en' => 'abandonment', 'pos_id' => rand(1, 11)],
            ['en' => 'abandons', 'pos_id' => rand(1, 11)],
            ['en' => 'abase', 'pos_id' => rand(1, 11)],
            ['en' => 'abased', 'pos_id' => rand(1, 11)],
            ['en' => 'abasement', 'pos_id' => rand(1, 11)],
            ['en' => 'abases', 'pos_id' => rand(1, 11)],
            ['en' => 'abash', 'pos_id' => rand(1, 11)],
            ['en' => 'abashed', 'pos_id' => rand(1, 11)],
            ['en' => 'abashes', 'pos_id' => rand(1, 11)],
            ['en' => 'abashing', 'pos_id' => rand(1, 11)],
            ['en' => 'abasing', 'pos_id' => rand(1, 11)],
            ['en' => 'abate', 'pos_id' => rand(1, 11)],
            ['en' => 'abated', 'pos_id' => rand(1, 11)],
            ['en' => 'abatement', 'pos_id' => rand(1, 11)],
            ['en' => 'abates', 'pos_id' => rand(1, 11)],
            ['en' => 'abating', 'pos_id' => rand(1, 11)],
            ['en' => 'abattoir', 'pos_id' => rand(1, 11)],
            ['en' => 'abattoirs', 'pos_id' => rand(1, 11)],
            ['en' => 'abbess', 'pos_id' => rand(1, 11)],
            ['en' => 'abbesses', 'pos_id' => rand(1, 11)],
            ['en' => 'abbey', 'pos_id' => rand(1, 11)],
            ['en' => 'abbeys', 'pos_id' => rand(1, 11)],
            ['en' => 'abbot', 'pos_id' => rand(1, 11)],
            ['en' => 'abbots', 'pos_id' => rand(1, 11)],
            ['en' => 'employing', 'pos_id' => rand(1, 11)],
            ['en' => 'employee', 'pos_id' => rand(1, 11)],
            ['en' => 'employed', 'pos_id' => rand(1, 11)],
            ['en' => 'employ', 'pos_id' => rand(1, 11)],
            ['en' => 'employer', 'pos_id' => rand(1, 11)],
        ]);
    }
}
