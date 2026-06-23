<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        $popularBrands = [
            ['name' => 'Toyota', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Lexus', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'BMW', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Mercedes-Benz', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Audi', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Volkswagen', 'sort_order' => 6, 'is_active' => true],
            ['name' => 'Hyundai', 'sort_order' => 7, 'is_active' => true],
            ['name' => 'Kia', 'sort_order' => 8, 'is_active' => true],
            ['name' => 'Nissan', 'sort_order' => 9, 'is_active' => true],
            ['name' => 'Honda', 'sort_order' => 10, 'is_active' => true],
            ['name' => 'Mazda', 'sort_order' => 11, 'is_active' => true],
            ['name' => 'Subaru', 'sort_order' => 12, 'is_active' => true],
            ['name' => 'Mitsubishi', 'sort_order' => 13, 'is_active' => true],
            ['name' => 'Suzuki', 'sort_order' => 14, 'is_active' => true],
            ['name' => 'Ford', 'sort_order' => 15, 'is_active' => true],
            ['name' => 'Chevrolet', 'sort_order' => 16, 'is_active' => true],
            ['name' => 'Renault', 'sort_order' => 17, 'is_active' => true],
            ['name' => 'Peugeot', 'sort_order' => 18, 'is_active' => true],
            ['name' => 'Citroen', 'sort_order' => 19, 'is_active' => true],
            ['name' => 'Volvo', 'sort_order' => 20, 'is_active' => true],
            ['name' => 'Porsche', 'sort_order' => 21, 'is_active' => true],
            ['name' => 'Jaguar', 'sort_order' => 22, 'is_active' => true],
            ['name' => 'Land Rover', 'sort_order' => 23, 'is_active' => true],
            ['name' => 'Cadillac', 'sort_order' => 24, 'is_active' => true],
            ['name' => 'Jeep', 'sort_order' => 25, 'is_active' => true],
            ['name' => 'Chrysler', 'sort_order' => 26, 'is_active' => true],
            ['name' => 'Dodge', 'sort_order' => 27, 'is_active' => true],
            ['name' => 'RAM', 'sort_order' => 28, 'is_active' => true],
            ['name' => 'Ferrari', 'sort_order' => 29, 'is_active' => true],
            ['name' => 'Lamborghini', 'sort_order' => 30, 'is_active' => true],
            ['name' => 'Maserati', 'sort_order' => 31, 'is_active' => true],
            ['name' => 'Bentley', 'sort_order' => 32, 'is_active' => true],
            ['name' => 'Rolls-Royce', 'sort_order' => 33, 'is_active' => true],
            ['name' => 'Aston Martin', 'sort_order' => 34, 'is_active' => true],
            ['name' => 'McLaren', 'sort_order' => 35, 'is_active' => true],
            ['name' => 'Bugatti', 'sort_order' => 36, 'is_active' => true],
            ['name' => 'Tesla', 'sort_order' => 37, 'is_active' => true],
            ['name' => 'Lada', 'sort_order' => 38, 'is_active' => true],
            ['name' => 'UAZ', 'sort_order' => 39, 'is_active' => true],
            ['name' => 'GAZ', 'sort_order' => 40, 'is_active' => true],
            ['name' => 'Geely', 'sort_order' => 41, 'is_active' => true],
            ['name' => 'Chery', 'sort_order' => 42, 'is_active' => true],
            ['name' => 'Great Wall', 'sort_order' => 43, 'is_active' => true],
            ['name' => 'Haval', 'sort_order' => 44, 'is_active' => true],
            ['name' => 'BYD', 'sort_order' => 45, 'is_active' => true],
        ];

        foreach ($popularBrands as $brand) {
            DB::table('car_brands')->insert([
                'name' => $brand['name'],
                'sort_order' => $brand['sort_order'],
                'is_active' => $brand['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('car_brands');
    }
};