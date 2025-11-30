<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerForeignToEmployeeProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            // Ensure manager_id type matches id type (uuid)
            $table->foreign('manager_id')
                  ->references('id')
                  ->on('employee_profiles')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });
    }
}
