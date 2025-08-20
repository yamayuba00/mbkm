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
        Schema::create('log_books', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('mbkm_program_id')->constrained('mbkm_programs')->onDelete('cascade');
            $table->date('date');
            $table->text('activity');
            $table->string('duration')->nullable();
            $table->text('output')->nullable();
            $table->text('obstacle')->nullable();
            $table->integer('status', 0)->default(0); // 0: Pending, 1: accepted, 2: rejected            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('log_books');
    }
};
