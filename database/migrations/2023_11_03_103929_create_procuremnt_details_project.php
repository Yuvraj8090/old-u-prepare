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
        Schema::create('procuremnt_details_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->bigInteger('eot_number');   
            $table->date('revised_contract_end_date');  
            $table->bigInteger('revised_contract_value');    
            $table->bigInteger('contract_value_variation');  
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
        Schema::dropIfExists('procuremnt_details_project');
    }
};
