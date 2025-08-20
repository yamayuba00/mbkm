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
        Schema::create('mbkm_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->foreignId('lecturer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('submission_period_id')->constrained('submission_periods')->onDelete('cascade');
            $table->foreignId('submission_types_id')->constrained('submission_types')->onDelete('cascade');
            $table->string('ipk')->nullable();
            $table->string('sks')->nullable();
            $table->string('cv')->nullable();
            $table->string('khs')->nullable();
            $table->string('portfolio')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); #admin
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null'); #lecturer
            $table->dateTime('validated_at')->nullable();
            $table->string('reason')->nullable();
            $table->string('academic_value')->default(0);
            $table->string('field_value')->default(0);
            $table->integer('status')->default(1); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbkm_programs');
    }
};
