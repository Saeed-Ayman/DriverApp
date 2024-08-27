<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image_id')->default(Location::DEFAULT_LOGO);
            $table->string('excerpt');
            $table->string('description');
            $table->string('whatsapp');
            $table->string('phone');
            $table->string('landline');
            $table->json('prices');
            $table->json('location');

            $table->foreignIdFor(\App\Models\City::class)->constrained();
            $table->foreignIdFor(\App\Models\Country::class)->constrained();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
