<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `order_products` MODIFY `item_price` DECIMAL(10,2) NULL');
        DB::statement('ALTER TABLE `order_products` MODIFY `total_price` DECIMAL(10,2) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `order_products` MODIFY `item_price` INT NULL');
        DB::statement('ALTER TABLE `order_products` MODIFY `total_price` INT NULL');
    }
};
