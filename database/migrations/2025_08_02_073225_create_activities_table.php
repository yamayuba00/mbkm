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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbkm_program_id')->constrained('mbkm_programs')->onDelete('cascade');
            $table->date('date');
            $table->string('activity_title');
            $table->text('activity_detail');
            $table->string('location')->nullable();
            $table->string('duration')->nullable();
            $table->text('output')->nullable();
            $table->text('obstacle')->nullable();
            $table->string('evidence_file')->nullable();
            $table->tinyInteger('status')->default(0); // 0: draft, 1: submitted, 2: validated
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('validated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
