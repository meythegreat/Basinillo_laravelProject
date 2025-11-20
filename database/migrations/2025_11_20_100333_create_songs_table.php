<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist')->nullable();
            $table->unsignedBigInteger('genre_id')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->year('release_year')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('genre_id')
                  ->references('id')->on('genres')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
