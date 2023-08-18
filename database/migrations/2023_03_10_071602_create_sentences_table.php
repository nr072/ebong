<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sentences', function (Blueprint $table) {

            $table->id();
            $table->timestamps();

            $table->text('text');
            $table->string('lang', 6)->default('en');

            $table->string('context', 200)->nullable();
            $table->string('subcontext', 200)->nullable();

            $table->string('project', 100)->nullable();

            $table->string('link_1', 200)->nullable();
            $table->string('link_2', 200)->nullable();
            $table->string('link_3', 200)->nullable();

            $table->text('note')->nullable();
            $table->enum('note_type', ['Note', 'Reference'])
                    ->default('Note');

            $table->boolean('needs_revision')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sentences');
    }
}
