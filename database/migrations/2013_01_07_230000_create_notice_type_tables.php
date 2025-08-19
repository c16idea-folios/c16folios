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
        Schema::create('notice_type', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('act_id')->nullable();
            $table->foreign('act_id', 'fk_notice_type_acts')
                  ->references('id')
                  ->on('acts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('type')->nullable();
            $table->integer('days')->nullable();

            $table->enum('foreigners', ['yes', 'no']);
            $table->text('observations')->nullable();  // Observaciones (opcional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_type');
    }
};