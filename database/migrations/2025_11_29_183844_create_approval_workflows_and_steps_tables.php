<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalWorkflowsAndStepsTables extends Migration
{
    public function up()
    {
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->string('name');
            $table->string('scope')->default('company'); // company|department|leave_type
            $table->boolean('is_default')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('approval_workflow_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('workflow_id')->index();
            $table->integer('step_order')->default(1)->index();
            $table->integer('parallel_group')->nullable()->index();
            $table->string('selector_type')->default('role'); // role|user|manager|department_head|dynamic
            $table->string('selector_value')->nullable(); // e.g., role slug or user id
            $table->boolean('allow_override')->default(false);
            $table->json('condition_json')->nullable();
            $table->timestamps();

            $table->foreign('workflow_id')->references('id')->on('approval_workflows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval_workflow_steps');
        Schema::dropIfExists('approval_workflows');
    }
}
