<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Author::class)->constrained();
            $table->foreignIdFor(\App\Models\Genre::class)->constrained();
            $table->string('title', 100);
            $table->string('isbn', 13);
            $table->integer('pages');
            $table->unsignedTinyInteger('stock');
            $table->date('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
