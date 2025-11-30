<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTypesTable extends Migration
{
    public function up()
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('payable')->default(true);
            $table->integer('max_days_per_year')->nullable();
            $table->boolean('carry_over_allowed')->default(false);
            $table->boolean('requires_document')->default(false);
            $table->integer('requires_approval_level')->default(1);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id','code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_types');
    }
}
