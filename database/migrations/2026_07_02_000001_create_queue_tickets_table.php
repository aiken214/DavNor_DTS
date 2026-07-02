<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_number');
            $table->string('client_name', 100)->default('Visitor');
            $table->enum('status', ['waiting', 'calling', 'completed'])->default('waiting');
            $table->string('transaction_type', 20)->default('receiving');
            $table->text('push_subscription')->nullable();
            $table->timestamps();

            $table->index(['status', 'transaction_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_tickets');
    }
};
