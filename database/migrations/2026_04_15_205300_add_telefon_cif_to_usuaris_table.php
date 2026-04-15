<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuaris', function (Blueprint $table) {
            $table->string('telefon', 20)->nullable()->after('empresa');
            $table->string('cif', 20)->nullable()->after('telefon');
            $table->boolean('actiu')->default(true)->after('cif');
        });
    }

    public function down(): void
    {
        Schema::table('usuaris', function (Blueprint $table) {
            $table->dropColumn(['telefon', 'cif', 'actiu']);
        });
    }
};
