<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->nullable()->index(); // nullable for global
            $table->string('name');
            $table->date('date')->index();
            $table->enum('recurring', ['none','yearly'])->default('none');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id','date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('holidays');
    }
}
