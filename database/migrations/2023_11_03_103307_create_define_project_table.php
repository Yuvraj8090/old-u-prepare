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
        Schema::create('define_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->string('hpc_number');
            $table->string('method_of_procurement');
            $table->integer('bid_number');
            $table->bigInteger('bid_fee')->default('0');
            $table->bigInteger('earnest_money_deposit')->default('0');
            $table->string('epd');
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
        Schema::dropIfExists('define_project');
    }
};
