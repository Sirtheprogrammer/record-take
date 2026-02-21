<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Section A – General Information
            $table->date('report_date');
            $table->enum('shift', ['morning', 'night']);
            $table->string('site_location')->nullable();

            // Section B – Safety & Attendance
            $table->text('incidents')->nullable();
            $table->boolean('toolbox_meeting')->default(false);
            $table->text('toolbox_notes')->nullable();
            $table->unsignedInteger('total_personnel')->default(0);

            // Section C – Production Summary
            $table->text('machines_used')->nullable();
            $table->decimal('total_working_hours', 5, 2)->nullable();
            $table->text('work_done')->nullable();
            $table->enum('site_status', ['on_schedule', 'delayed', 'completed', 'ongoing'])->default('ongoing');

            // Section D – Equipment Condition
            $table->enum('machine_status', ['good', 'minor_issue', 'critical'])->default('good');
            $table->text('breakdowns')->nullable();
            $table->unsignedTinyInteger('fuel_level')->nullable()->comment('Percentage 0-100');
            $table->boolean('maintenance_required')->default(false);

            // Section E – Challenges / Delays
            $table->text('challenges')->nullable();

            // Section F – Plan for Tomorrow
            $table->text('plan_for_tomorrow')->nullable();

            // Status
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
