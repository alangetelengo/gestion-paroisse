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
        Schema::create('funerals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defunt_id')->constrained('members')->onDelete('cascade');
            $table->date('date_obseques');
            $table->string('lieu')->nullable();
            $table->foreignId('celebre_par_id')->nullable()->constrained('members')->onDelete('set null');
            $table->string('famille_contact')->nullable();
            $table->string('lieu_inhumation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funerals');
    }
};
