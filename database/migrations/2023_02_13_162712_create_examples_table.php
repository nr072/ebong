<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Term;

class CreateExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('en');
            $table->text('bn')->nullable();
            $table->string('source', 100)->nullable();
            $table->string('link_1', 200)->nullable();
            $table->string('link_2', 200)->nullable();
            $table->string('link_3', 200)->nullable();
            $table->foreignIdFor(Term::class)->constrained()
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
        Schema::dropIfExists('examples');
    }
}
