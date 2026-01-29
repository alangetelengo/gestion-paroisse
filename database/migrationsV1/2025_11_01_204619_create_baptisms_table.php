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
        Schema::create('baptisms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('members')->onDelete('cascade');
            $table->date('date_bapteme');
            $table->string('lieu')->nullable();
            $table->foreignId('celebre_par_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('parrain_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('marraine_id')->nullable()->constrained('members')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baptisms');
    }
};
