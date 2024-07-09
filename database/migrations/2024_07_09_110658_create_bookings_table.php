<?php

use App\Models\BookingGroup;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('repetition', ['no', 'every_weeks', 'even_weeks', 'odd_weeks'])->default('no');
            $table->string('day');
            $table->foreignIdFor(BookingGroup::class)->nullable();
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
