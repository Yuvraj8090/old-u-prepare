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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->date('contract_unique_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->date('contract_signing_date');
            $table->bigInteger('contract_value');
            $table->date('end_date');
            $table->bigInteger('bid_Fee');
            $table->string('contract_agency');
            $table->integer('authorized_personel');
            $table->string('contact');
            $table->string('contractor_address');
            $table->string('cancel_reason')->nullable();
            $table->date('forclose_reason')->nullable();
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
        Schema::dropIfExists('contracts');
    }
};
