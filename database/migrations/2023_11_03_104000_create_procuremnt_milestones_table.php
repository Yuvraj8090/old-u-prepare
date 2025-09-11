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
        Schema::create('procuremnt_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->integer('percent_of_work');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('amended_start_date')->nullable();
            $table->date('amended_end_date')->nullable();
            $table->integer('financial_progress')->nullable();
            $table->integer('physical_progress')->nullable();
            $table->bigInteger('accumulative')->nullable();
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
        Schema::dropIfExists('procuremnt_milestones');
    }
};
