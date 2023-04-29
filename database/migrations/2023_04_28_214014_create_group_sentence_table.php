<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Group;
use App\Models\Sentence;

class CreateGroupSentenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_sentence', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Sentence::class)->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignIdFor(Group::class)->constrained()
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
        Schema::dropIfExists('group_sentence');
    }
}
