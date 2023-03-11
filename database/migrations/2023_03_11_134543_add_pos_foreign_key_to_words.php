<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pos;

class AddPosForeignKeyToWords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropColumn('pos');
            $table->foreignId('pos_id')
                    ->nullable()
                    ->constrained('poses')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('words', function (Blueprint $table) {
            //
        });
    }
}
