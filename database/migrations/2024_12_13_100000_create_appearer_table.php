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
        Schema::create('appearer', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('instrument_act_id');

            $table->foreign('instrument_act_id', 'fk_instrument_act_appearer')
            ->references('id')
            ->on('instrument_act')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->unsignedBigInteger('appearer');

            $table->foreign('appearer', 'fk_appearer_clients')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

           $table->string('legal_representative')->nullable();  // Representante legal (opcional)



            $table->enum('legend', ['yes', 'no']); 
            $table->text('observations')->nullable();

            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appearer');
    }
};