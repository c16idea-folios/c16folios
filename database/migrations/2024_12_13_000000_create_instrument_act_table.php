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
        Schema::create('instrument_act', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('instrument_id');

            $table->foreign('instrument_id', 'fk_instrument_act_instruments')
            ->references('id')
            ->on('instruments')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->unsignedBigInteger('client_id');

            $table->foreign('client_id', 'fk_instrument_act_client')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

           $table->string('legal_representative')->nullable();  // Representante legal (opcional)

            $table->unsignedBigInteger('act_id');
            $table->foreign('act_id', 'fk_instrument_act_acts')
            ->references('id')
                ->on('acts')
                ->onDelete('cascade')
                ->onUpdate('cascade');


                $table->double('cost')->nullable();


            $table->enum('invoice', ['not_applicable', 'request','sent']); 
           
            $table->timestamps();

            // dinamic

            $table->string('appearing_character')->nullable();  
            $table->text('fact_recorded')->nullable();
            $table->enum('formalization_type',  ["NA", "ORDINARIA", "EXTRAORDINARIA"])->nullable(); 
            $table->string('notified_person')->nullable();  
            $table->string('notification_subject')->nullable();  
            $table->text('document_ratified')->nullable();
            $table->enum('formalization_contract', ["NA", "CONTRATO", "CONVENIO"])->nullable(); 
            $table->string('of')->nullable();  
            $table->text('mercantile_declarations')->nullable();
            $table->text('in_favor_of')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_act');
    }
};