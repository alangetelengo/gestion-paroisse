<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('communions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('members')->onDelete('cascade');
            $table->date('date_communion');
            $table->string('lieu')->nullable();
            $table->string('catechiste')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communions');
    }
};
