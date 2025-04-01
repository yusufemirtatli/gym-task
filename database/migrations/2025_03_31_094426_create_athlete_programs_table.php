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
        Schema::create('athlete_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('athlete_id');
            $table->foreign('athlete_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('id')->on('training_programs')->onDelete('cascade');
            $statusEnums = array_column(\App\Enum\AthleteProgramsStatus::cases(),'value'); //ENUM YAPISI
            $table->enum('status',$statusEnums)->default(\App\Enum\AthleteProgramsStatus::ACTIVE->value);;
            $table->softDeletes(); // deleted_at sütununu ekler
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athlete_programs');
    }
};
