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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['direction_generale', 'paroisse_admin', 'paroisse_secretaire', 'paroisse_tresorier'])
                  ->default('paroisse_secretaire')
                  ->after('email');

            $table->foreignId('paroisse_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('paroisses')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['paroisse_id']);
            $table->dropColumn(['role', 'paroisse_id']);
        });
    }
};
