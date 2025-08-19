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
        Schema::create('file', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('instrument_act_id');
            $table->foreign('instrument_act_id', 'fk_file_instrument_act')
                ->references('id')
                ->on('instrument_act')
                ->onDelete('cascade')
                ->onUpdate('cascade');


                $table->unsignedBigInteger('file_type_id');
                $table->foreign('file_type_id', 'fk_file_file_type')
                    ->references('id')
                    ->on('file_type')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
    
            $table->string('file_path', 2048)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file');
    }
};