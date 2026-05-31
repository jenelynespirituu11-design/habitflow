<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the expenses table for the Income & Expense Tracker module.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();                                           // Primary key (auto-increment)

            $table->unsignedBigInteger('user_id');                  // Foreign key – NOT NULL
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');                            // Delete expenses when user is deleted

            $table->string('category');                             // e.g. Food, Transportation, Entertainment
            $table->decimal('amount', 8, 2);                       // e.g. 1234.56 (max 999999.99)
            $table->date('date');                                   // When the expense happened
            $table->text('description')->nullable();                // Optional notes

            $table->timestamps();                                   // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Drops the expenses table.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
