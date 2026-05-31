<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#FFB6D9');
            $table->string('icon', 50)->default('star');
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->unsignedTinyInteger('target_days')->default(1);
            $table->string('category', 100)->nullable();
            $table->enum('status', ['active', 'completed', 'paused'])->default('active');
            $table->date('start_date');
            $table->timestamps();

            $table->index('user_id', 'habits_user_id_index');

            $table->foreign('user_id', 'habits_user_id_fk')
                  ->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('habits');
    }
};
