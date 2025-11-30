<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->index();
            $table->uuid('employee_profile_id')->index();
            $table->uuid('leave_type_id')->index();
            $table->integer('year')->index();
            $table->decimal('entitled_days', 8, 2)->default(0);
            $table->decimal('taken_days', 8, 2)->default(0);
            $table->decimal('available_days', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');

            $table->unique(['employee_profile_id','leave_type_id','year'], 'leave_balance_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_balances');
    }
}
