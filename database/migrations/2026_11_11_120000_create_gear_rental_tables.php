<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Gear rental (admin only) — docs/FUNCTIONS_DOCS/shop/gear_rental.md
 *
 * rental_warehouses      where rental gear is kept; one `general` = the default
 *                        (its prices / stock show on linked shop service pages)
 * rental_gear            the gear catalogue (EN / KA names, deposit, show on the shop)
 * rental_gear_prices     price per rental period (days → price), e.g. 1, 2, 3, 7 days
 * rental_warehouse_gear  stock: how many of each gear item a warehouse owns
 * rentals                one rental: renter (linked to a user when registered),
 *                        ID card photo (private storage), period, money, return
 * rental_items           gear lines of a rental (name + prices kept as they were)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('general')->default(false);
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('rental_gear', function (Blueprint $table) {
            $table->id();
            $table->string('us_name');
            $table->string('ka_name')->nullable();
            $table->string('category')->nullable();
            $table->string('code')->nullable()->index();          // inventory code / barcode
            $table->decimal('deposit', 10, 2)->nullable();          // suggested deposit per piece
            $table->boolean('show_in_shop')->default(true);         // listed on linked service pages
            $table->integer('sort_order')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('rental_gear_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_gear_id')->constrained('rental_gear')->cascadeOnDelete();
            $table->unsignedSmallInteger('days');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->unique(['rental_gear_id', 'days']);
        });

        Schema::create('rental_warehouse_gear', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_warehouse_id')->constrained('rental_warehouses')->cascadeOnDelete();
            $table->foreignId('rental_gear_id')->constrained('rental_gear')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();
            $table->unique(['rental_warehouse_id', 'rental_gear_id']);
        });

        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_warehouse_id')->nullable()->constrained('rental_warehouses')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->nullable()->index();
            $table->string('phone');
            $table->string('id_number')->nullable();                // personal / passport number
            $table->string('id_card_image')->nullable();            // path on the private `local` disk
            $table->dateTime('start_at');
            $table->unsignedSmallInteger('days');
            $table->dateTime('due_at');
            $table->dateTime('returned_at')->nullable();
            $table->string('status', 20)->default('active')->index();   // active | returned | cancelled
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('deposit', 10, 2)->default(0);
            $table->boolean('deposit_returned')->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->cascadeOnDelete();
            $table->foreignId('rental_gear_id')->nullable()->constrained('rental_gear')->nullOnDelete();
            $table->string('gear_name');                            // kept if the gear is deleted later
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);                   // price of one piece for the whole period
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });

        // Shop services: link a service page to other data (now: gear rental prices)
        Schema::table('services', function (Blueprint $table) {
            $table->string('link_type', 40)->nullable()->after('published');        // null | 'gear_rental'
            $table->unsignedBigInteger('link_id')->nullable()->after('link_type');  // e.g. rental warehouse (null = general)
            $table->json('link_options')->nullable()->after('link_id');             // free settings for future links
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['link_type', 'link_id', 'link_options']);
        });
        Schema::dropIfExists('rental_items');
        Schema::dropIfExists('rentals');
        Schema::dropIfExists('rental_warehouse_gear');
        Schema::dropIfExists('rental_gear_prices');
        Schema::dropIfExists('rental_gear');
        Schema::dropIfExists('rental_warehouses');
    }
};
