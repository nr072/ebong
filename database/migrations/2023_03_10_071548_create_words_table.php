<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {

            $table->id();
            $table->timestamps();

            $table->string('en', 50);
            $table->string('bn', 50)->nullable();

            $table->foreignId('pos_id')
                    ->nullable()
                    ->constrained('poses')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

            $table->foreignId('group_id')
                    ->nullable()
                    ->constrained()
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
        Schema::dropIfExists('words');
    }
}
