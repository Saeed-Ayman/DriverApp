<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use App\Models\LocationCategory;
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
            $table->string('excerpt');
            $table->string('description');
            $table->string('whatsapp');
            $table->string('phone');
            $table->string('landline');
            $table->json('services');
            $table->json('location');

            $table->foreignIdFor(City::class)->constrained();
            $table->foreignIdFor(Country::class)->constrained();
            $table->foreignIdFor(LocationCategory::class)->constrained();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
