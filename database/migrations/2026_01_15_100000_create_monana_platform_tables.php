<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration creates all tables for Monana Platform (Cyber Cafe + Monana Food)
     */
    public function up(): void
    {
        // Update users table - add preferred_service
        Schema::table('users', function (Blueprint $table) {
            $table->enum('preferred_service', ['cyber', 'food'])->nullable()->after('is_admin');
        });

        // ================================================
        // CYBER CAFE TABLES
        // ================================================

        // Cyber Meal Slots (Asubuhi, Mchana, Usiku)
        Schema::create('cyber_meal_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // asubuhi, mchana, usiku
            $table->string('display_name'); // Asubuhi, Mchana, Usiku
            $table->time('order_start_time');
            $table->time('order_end_time');
            $table->string('delivery_time_label'); // "Kesho Asubuhi", "Leo Mchana", etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Cyber Menu Items (Chapati, Chai, Mayai, etc.)
        Schema::create('cyber_menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->foreignId('meal_slot_id')->nullable()->constrained('cyber_meal_slots')->nullOnDelete();
            $table->boolean('available_all_slots')->default(true);
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Cyber Orders
        Schema::create('cyber_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->foreignId('meal_slot_id')->nullable()->constrained('cyber_meal_slots')->nullOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', [
                'pending',
                'approved',
                'preparing',
                'ready',
                'on_delivery',
                'delivered',
                'cancelled',
                'rejected',
            ])->default('pending');
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_lat', 10, 8)->nullable();
            $table->decimal('delivery_lng', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->default('web'); // web, whatsapp
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scheduled_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // Cyber Order Items
        Schema::create('cyber_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cyber_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained('cyber_menu_items')->cascadeOnDelete();
            $table->string('item_name'); // Snapshot of name at order time
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ================================================
        // MONANA FOOD TABLES
        // ================================================

        // Food Products (Mchele, Tambi, Mayai, Maharage, etc.)
        Schema::create('food_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('unit')->default('kg'); // kg, pieces, liters, etc.
            $table->string('image')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('can_be_customized')->default(true); // For package customization
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Food Packages (Weekly, Monthly)
        Schema::create('food_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Weekly Package, Monthly Package
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->integer('duration_days'); // 7 for weekly, 30 for monthly
            $table->enum('duration_type', ['weekly', 'monthly']);
            $table->integer('deliveries_per_week')->default(5);
            $table->json('delivery_days')->nullable(); // [1,2,3,4,5] for Mon-Fri
            $table->time('customization_cutoff_time')->default('18:00:00');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Food Package Items (Default items in each package)
        Schema::create('food_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('food_packages')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('food_products')->cascadeOnDelete();
            $table->decimal('default_quantity', 8, 2);
            $table->boolean('is_required')->default(false); // Cannot be removed if true
            $table->timestamps();

            $table->unique(['package_id', 'product_id']);
        });

        // Food Package Rules (Item replacement pricing rules)
        Schema::create('food_package_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('food_packages')->cascadeOnDelete();
            $table->foreignId('from_product_id')->constrained('food_products')->cascadeOnDelete();
            $table->foreignId('to_product_id')->constrained('food_products')->cascadeOnDelete();
            $table->enum('adjustment_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('adjustment_value', 10, 2)->default(0); // + or - amount
            $table->boolean('is_allowed')->default(true);
            $table->timestamps();

            $table->unique(['package_id', 'from_product_id', 'to_product_id'], 'package_rule_unique');
        });

        // Food Subscriptions
        Schema::create('food_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('food_packages')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', [
                'pending',
                'active',
                'paused',
                'cancelled',
                'expired',
            ])->default('pending');
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_lat', 10, 8)->nullable();
            $table->decimal('delivery_lng', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->default('web'); // web, whatsapp
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('resumed_at')->nullable();
            $table->timestamps();
        });

        // Food Subscription Customizations (Daily customizations)
        Schema::create('food_subscription_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('food_subscriptions')->cascadeOnDelete();
            $table->date('delivery_date');
            $table->enum('action_type', ['pause', 'swap', 'remove', 'add'])->default('swap');
            $table->foreignId('original_product_id')->nullable()->constrained('food_products')->nullOnDelete();
            $table->foreignId('new_product_id')->nullable()->constrained('food_products')->nullOnDelete();
            $table->decimal('original_quantity', 8, 2)->nullable();
            $table->decimal('new_quantity', 8, 2)->nullable();
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'delivery_date'], 'sub_customization_date_idx');
        });

        // Food Orders (One-time custom orders)
        Schema::create('food_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', [
                'pending',
                'approved',
                'preparing',
                'ready',
                'on_delivery',
                'delivered',
                'cancelled',
                'rejected',
            ])->default('pending');
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_lat', 10, 8)->nullable();
            $table->decimal('delivery_lng', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->default('web'); // web, whatsapp
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('scheduled_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        // Food Order Items
        Schema::create('food_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('food_products')->cascadeOnDelete();
            $table->string('product_name'); // Snapshot of name at order time
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 2);
            $table->string('unit')->default('kg');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ================================================
        // UPDATE SHARED TABLES
        // ================================================

        // Update payments table - add service_type
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('service_type', ['cyber', 'food'])->default('cyber')->after('id');
            $table->foreignId('cyber_order_id')->nullable()->after('order_id')->constrained('cyber_orders')->nullOnDelete();
            $table->foreignId('food_order_id')->nullable()->after('cyber_order_id')->constrained('food_orders')->nullOnDelete();
            $table->foreignId('food_subscription_id')->nullable()->after('subscription_id')->constrained('food_subscriptions')->nullOnDelete();
        });

        // Update notifications table - add service_type
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('service_type', ['cyber', 'food', 'general'])->default('general')->after('user_id');
            $table->foreignId('cyber_order_id')->nullable()->after('order_id')->constrained('cyber_orders')->nullOnDelete();
            $table->foreignId('food_order_id')->nullable()->after('cyber_order_id')->constrained('food_orders')->nullOnDelete();
            $table->foreignId('food_subscription_id')->nullable()->after('subscription_id')->constrained('food_subscriptions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from notifications
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['cyber_order_id']);
            $table->dropForeign(['food_order_id']);
            $table->dropForeign(['food_subscription_id']);
            $table->dropColumn(['service_type', 'cyber_order_id', 'food_order_id', 'food_subscription_id']);
        });

        // Remove columns from payments
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['cyber_order_id']);
            $table->dropForeign(['food_order_id']);
            $table->dropForeign(['food_subscription_id']);
            $table->dropColumn(['service_type', 'cyber_order_id', 'food_order_id', 'food_subscription_id']);
        });

        // Drop food tables
        Schema::dropIfExists('food_order_items');
        Schema::dropIfExists('food_orders');
        Schema::dropIfExists('food_subscription_customizations');
        Schema::dropIfExists('food_subscriptions');
        Schema::dropIfExists('food_package_rules');
        Schema::dropIfExists('food_package_items');
        Schema::dropIfExists('food_packages');
        Schema::dropIfExists('food_products');

        // Drop cyber tables
        Schema::dropIfExists('cyber_order_items');
        Schema::dropIfExists('cyber_orders');
        Schema::dropIfExists('cyber_menu_items');
        Schema::dropIfExists('cyber_meal_slots');

        // Remove preferred_service from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_service');
        });
    }
};
