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
        Schema::create('notification', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('instrument_act_id');
            $table->foreign('instrument_act_id', 'fk_notification_instrument_act')
                ->references('id')
                ->on('instrument_act')
                ->onDelete('cascade')
                ->onUpdate('cascade');


                $table->unsignedBigInteger('notice_type_id');
                $table->foreign('notice_type_id', 'fk_notification_notice_type_act')
                    ->references('id')
                    ->on('notice_type')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    

                $table->date('presentation_date')->nullable();

    
                    $table->text('observations')->nullable();
                    $table->string('status',100)->default('Presentado'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};