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
        Schema::disableForeignKeyConstraints();

        Schema::create('pedagangs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('nik', 16)->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('tipepedagang_id')->constrained('tipepedagangs')->cascadeOnDelete();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('pasar_id')->constrained('pasars')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedagangs');
    }
};
