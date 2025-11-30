<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalStepsTable extends Migration
{
    public function up()
    {
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('leave_request_id')->index();
            $table->uuid('tpl_step_id')->nullable()->index(); // template step origin (if any)
            $table->integer('step_order')->default(1)->index();
            $table->integer('parallel_group')->nullable()->index();
            $table->string('approver_type')->nullable(); // role|user|manager_of_requester|department_head|dynamic
            $table->uuid('approver_user_id')->nullable()->index();
            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->text('comment')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();

            $table->foreign('leave_request_id')->references('id')->on('leave_requests')->onDelete('cascade');
            $table->foreign('approver_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval_steps');
    }
}
