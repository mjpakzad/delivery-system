<?php

use App\Enums\ParcelStatus;
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
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->foreignId('courier_id')->nullable()->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('business_id')->nullable()->constrained()->onUpdate('cascade')->nullOnDelete();
            $table->string('origin_name');
            $table->string('origin_mobile');
            $table->text('origin_address');
            $table->point('origin_location');
            $table->string('destination_name');
            $table->string('destination_mobile');
            $table->text('destination_address');
            $table->point('destination_location');
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedTinyInteger('status')->default(ParcelStatus::PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
