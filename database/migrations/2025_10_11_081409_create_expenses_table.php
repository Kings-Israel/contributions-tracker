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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('expense_type')->nullable();
            $table->longText('payment_reference_id')->nullable();
            $table->double('amount', 15, 8);
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->default('pending');
            $table->dateTime('month')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('attachment', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
