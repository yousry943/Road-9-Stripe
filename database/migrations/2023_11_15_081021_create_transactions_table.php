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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('Amount');
           // $table->integer('Payer');
            $table->foreignId('company_id')->nullable()->constrained('companies','id')->nullOnDelete();

            $table->foreignId('Payer_id')->nullable()->constrained('users','id')->nullOnDelete();
            $table->date('due_on')->nullable();
            $table->integer('vat')->nullable();
            $table->integer('is_vat_inclusive')->nullable();
            /**
             * The status of the transaction.
             *
             * @enum('Paid', 'Outstanding', 'Overdue')
            */
            $table->string('Status');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
