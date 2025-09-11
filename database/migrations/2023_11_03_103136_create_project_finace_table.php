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
        Schema::create('project_finance_expense', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->string('month');
            $table->bigInteger('budget');
            $table->bigInteger('office_equipment_exp');
            $table->bigInteger('electricty_exp');
            $table->bigInteger('transport_exp');
            $table->bigInteger('salaries_exp');
            $table->bigInteger('miscelleneous_exp');
            $table->bigInteger('total_exp');
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('project_id');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_finace');
    }
};
