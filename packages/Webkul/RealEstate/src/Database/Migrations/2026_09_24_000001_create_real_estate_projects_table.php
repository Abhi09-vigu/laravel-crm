<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('real_estate_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name');
            $table->string('project_code')->unique()->index();
            $table->string('developer_name');
            $table->string('project_type');
            $table->text('description')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode')->nullable();
            $table->string('rera_number')->nullable();
            $table->string('total_land_area')->nullable();
            $table->unsignedInteger('total_buildings_towers')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->string('status')->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_projects');
    }
};
