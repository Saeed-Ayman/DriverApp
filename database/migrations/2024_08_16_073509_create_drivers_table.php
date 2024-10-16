<?php

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('description');
            $table->string('whatsapp');
            $table->string('country');
            $table->string('government');
            $table->string('slug');
            $table->timestamps();

            $table->foreignIdFor(Country::class)->constrained();
            $table->foreignIdFor(City::class)->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
