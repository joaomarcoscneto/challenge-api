<?php

use App\Models\User;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 9)->unique();
            $table->unsignedDecimal('value', 10, 2);
            $table->date('issuance_date');
            $table->string('sender_cnpj', 14);
            $table->string('sender_name', 100);
            $table->string('transporter_cnpj', 14);
            $table->string('transporter_name', 100);

            $table->foreignIdFor(User::class)->constrained()->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
