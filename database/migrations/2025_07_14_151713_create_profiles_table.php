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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('gender')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('nid')->nullable();
            $table->string('bid')->nullable();

            $table->integer('task_point')->default(0);
            $table->unsignedBigInteger('bal_point')->default(150);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
