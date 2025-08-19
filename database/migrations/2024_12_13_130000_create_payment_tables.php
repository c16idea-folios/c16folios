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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('instrument_act_id');
            $table->foreign('instrument_act_id', 'fk_payment_instrument_act')
                ->references('id')
                ->on('instrument_act')
                ->onDelete('cascade')
                ->onUpdate('cascade');


                $table->date('payment_date')->nullable();

                $table->string('received_from')->nullable();  

                $table->double('amount_paid')->nullable();


                $table->unsignedBigInteger('payment_method_id');
                $table->foreign('payment_method_id', 'fk_payment_payment_method')
                    ->references('id')
                    ->on('payment_method')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    
                    $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};