<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->nullable()->index();
            $table->string('email')->index();
            $table->string('password')->nullable();
            $table->string('full_name')->nullable();
            // simple role: super_admin, company_admin, employee
            $table->string('role')->default('employee');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id','email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
