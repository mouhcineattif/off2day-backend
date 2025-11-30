<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->uuid('employee_profile_id')->index();
            $table->uuid('leave_type_id')->index();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->boolean('start_half_day')->default(false);
            $table->boolean('end_half_day')->default(false);
            $table->decimal('number_of_days', 8, 2)->default(0);
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->uuid('approved_by_user_id')->nullable()->index();
            $table->text('reason')->nullable();
            $table->json('attachments')->nullable();
            $table->json('approval_flow')->nullable(); // snapshot of resolved workflow
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['company_id','status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
