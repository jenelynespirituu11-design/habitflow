<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('habit_id');
            $table->unsignedBigInteger('user_id');
            $table->date('logged_date');
            $table->boolean('completed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('habit_id', 'hl_habit_id_index');
            $table->index('user_id', 'hl_user_id_index');
            $table->unique(['habit_id', 'logged_date'], 'hl_habit_date_unique');

            $table->foreign('habit_id', 'hl_habit_id_fk')
                  ->references('id')->on('habits')->onDelete('cascade');
            $table->foreign('user_id', 'hl_user_id_fk')
                  ->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('habit_logs');
    }
};
