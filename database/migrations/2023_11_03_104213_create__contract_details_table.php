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
        Schema::create('contract_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->date('start_date');
            $table->unsignedBigInteger('security_number');
            $table->date('end_security_date');
            $table->string('issuing_authority');
            $table->bigInteger('amount');
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();

            $table->index('contract_id');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_contract_details');
    }
};
