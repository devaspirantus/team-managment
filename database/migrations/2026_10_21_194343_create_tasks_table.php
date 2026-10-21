<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // To-Do title
            
            // العلاقات والمستخدمين
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete(); // Assigned Member
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Admin Creator
            
            // التواريخ والمدد
            $table->string('due_duration')->nullable(); // Due (e.g. "3 Days", "5 Hours")
            $table->date('due_date'); // Due Date
            $table->dateTime('finished_date')->nullable(); // Finished Date
            
            // حالات المهمة والتوقيت
            // Task Status: Pending, In Progress, Done, Closed
            $table->enum('task_status', ['pending', 'in_progress', 'done', 'closed'])->default('pending'); 
            $table->integer('timing_minutes')->default(0); // Timing in minutes
            $table->text('stats')->nullable(); // Stats / Summary notes
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};