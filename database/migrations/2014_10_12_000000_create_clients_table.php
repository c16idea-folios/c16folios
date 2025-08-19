<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->enum('person_type', ['física', 'moral']);  // Tipo de persona
            $table->string('rfc', 13)->nullable();  // RFC
            $table->string('name')->nullable();   // Nombre o razón social
            $table->string('last_name')->nullable();  // Primer apellido (opcional)
            $table->string('second_last_name')->nullable();  // Segundo apellido (opcional)


            $table->unsignedBigInteger('denomination_id')->nullable();
            $table->foreign('denomination_id', 'fk_clients_denominations')
                  ->references('id')
                  ->on('denominations')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->string('legal_representative')->nullable();  // Representante legal (opcional)
            $table->string('phone_number', 15)->nullable();   // Número telefónico
            $table->string('email')->nullable();  // Correo electrónico
            $table->string('country')->nullable();   // País
            $table->string('street')->nullable();   // Calle
            $table->string('n_exterior')->nullable();   // No. Exterior
            $table->string('suburb')->nullable();   // Colonia
            $table->string('municipality')->nullable();   // Municipio
            $table->string('entity')->nullable();   // Entidad
            $table->string('zip_code', 5)->nullable();   // C.P.
            $table->text('observations')->nullable();  // Observaciones (opcional)
            $table->timestamps();  // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
