<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->index();
            $table->uuid('company_id')->index();
            $table->string('employee_number')->nullable()->index();
            $table->string('job_title')->nullable();
            $table->uuid('department_id')->nullable()->index();
            $table->uuid('manager_id')->nullable()->index();
            $table->date('hire_date')->nullable();
            $table->json('work_schedule')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_profiles');
    }
}
