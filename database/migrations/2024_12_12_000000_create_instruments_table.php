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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->integer('no');
            $table->unsignedBigInteger('responsible_id');
            $table->foreign('responsible_id', 'fk_responsible_users')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');


            $table->enum('type', ['Acta', 'Póliza']);  // Tipo de persona
            $table->date('authorization_date')->nullable();


            $table->string('status',100)->default('active'); 
            
            $table->date('submission_date')->nullable();
            $table->string('who_receives',250)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};