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
        Schema::create('procuremnt_value_by_category', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('procurement_param_id');
            $table->float('weight');
            $table->string('method_of_procurement');
            $table->date('planned_date')->nullable();
            $table->date('actual_date')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();


            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procuremnt_value_by_category');
    }
};
