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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
       //     $table->enum('role', ['administrator', 'operator', 'technical_support']);
            $table->string('password');



            $table->unsignedBigInteger('work_team_id')->nullable();
            $table->foreign('work_team_id', 'fk_work_team_users')
                  ->references('id')
                  ->on('work_team')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->date('expires')->nullable();
            $table->boolean('is_active')->default(true);
    
            $table->string('profile_photo_path', 2048)->nullable();
            $table->text('observations')->nullable();  // Observaciones (opcional)
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

