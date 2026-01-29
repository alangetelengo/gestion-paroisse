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
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conjoint1_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('conjoint2_id')->constrained('members')->onDelete('cascade');
            $table->date('date_mariage');
            $table->string('lieu')->nullable();
            $table->foreignId('celebre_par_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('temoin1_id')->nullable()->constrained('members')->onDelete('set null');
            $table->foreignId('temoin2_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('numero_acte_civil')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
