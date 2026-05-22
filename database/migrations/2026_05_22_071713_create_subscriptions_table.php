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
    Schema::create("subscriptions", function (Blueprint $table) {
        $table->id();

        $table->foreignId("customer_id")
            ->constrained("customers")
            ->cascadeOnDelete();

        $table->foreignId("service_id")
            ->constrained("services")
            ->cascadeOnDelete();

        $table->date("start_date")->nullable();
        $table->date("end_date")->nullable();

        $table->enum("status", [
            "active",
            "inactive",
            "trial",
            "isolir",
            "dismantle"
        ]);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
