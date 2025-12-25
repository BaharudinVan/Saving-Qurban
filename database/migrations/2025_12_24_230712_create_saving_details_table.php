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
        Schema::create('saving_details', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('saving_id');
            $table->decimal('amount', 20, 5);
            $table->string('evidence')->nullable();
            $table->timestamps();

            $table->foreign('saving_id')->references('id')->on('savings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_details');
    }
};
