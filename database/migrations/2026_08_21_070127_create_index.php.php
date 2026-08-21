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
        // Products
        DB::statement(
            'CREATE INDEX idx_products_category
             ON products(category_id)'
        );

        DB::statement(
            'CREATE INDEX idx_products_brand
             ON products(brand_id)'
        );

        DB::statement(
            'CREATE INDEX idx_products_name
             ON products(product_name)'
        );

        DB::statement(
            'CREATE INDEX idx_products_barcode
             ON products(barcode)'
        );


        // Product Serials
        DB::statement(
            'CREATE INDEX idx_product_serials_product
             ON product_serials(product_id)'
        );

        DB::statement(
            'CREATE INDEX idx_product_serials_status
             ON product_serials(status)'
        );


        // Sales
        DB::statement(
            'CREATE INDEX idx_sales_customer
             ON sales(customer_id)'
        );

        DB::statement(
            'CREATE INDEX idx_sales_user
             ON sales(user_id)'
        );

        DB::statement(
            'CREATE INDEX idx_sales_date
             ON sales(sale_date)'
        );


        // Sale Items
        DB::statement(
            'CREATE INDEX idx_sale_items_sale
             ON sale_items(sale_id)'
        );

        DB::statement(
            'CREATE INDEX idx_sale_items_product
             ON sale_items(product_id)'
        );


        // Purchases
        DB::statement(
            'CREATE INDEX idx_purchases_supplier
             ON purchases(supplier_id)'
        );

        DB::statement(
            'CREATE INDEX idx_purchases_date
             ON purchases(purchase_date)'
        );


        // Purchase Items
        DB::statement(
            'CREATE INDEX idx_purchase_items_purchase
             ON purchase_items(purchase_id)'
        );


        // Stock Movements
        DB::statement(
            'CREATE INDEX idx_stock_movements_product
             ON stock_movements(product_id)'
        );

        DB::statement(
            'CREATE INDEX idx_stock_movements_created
             ON stock_movements(created_at)'
        );


        // Repairs
        DB::statement(
            'CREATE INDEX idx_repairs_customer
             ON repairs(customer_id)'
        );

        DB::statement(
            'CREATE INDEX idx_repairs_status
             ON repairs(status)'
        );

        DB::statement(
            'CREATE INDEX idx_repairs_serial
             ON repairs(serial_number)'
        );


        // Warranties
        DB::statement(
            'CREATE INDEX idx_warranties_serial
             ON product_warranties(serial_id)'
        );


        // Expenses
        DB::statement(
            'CREATE INDEX idx_expenses_date
             ON expenses(expense_date)'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Products
        DB::statement(
            'DROP INDEX IF EXISTS idx_products_category'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_products_brand'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_products_name'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_products_barcode'
        );


        // Product Serials
        DB::statement(
            'DROP INDEX IF EXISTS idx_product_serials_product'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_product_serials_status'
        );


        // Sales
        DB::statement(
            'DROP INDEX IF EXISTS idx_sales_customer'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_sales_user'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_sales_date'
        );


        // Sale Items
        DB::statement(
            'DROP INDEX IF EXISTS idx_sale_items_sale'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_sale_items_product'
        );


        // Purchases
        DB::statement(
            'DROP INDEX IF EXISTS idx_purchases_supplier'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_purchases_date'
        );


        // Purchase Items
        DB::statement(
            'DROP INDEX IF EXISTS idx_purchase_items_purchase'
        );


        // Stock Movements
        DB::statement(
            'DROP INDEX IF EXISTS idx_stock_movements_product'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_stock_movements_created'
        );


        // Repairs
        DB::statement(
            'DROP INDEX IF EXISTS idx_repairs_customer'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_repairs_status'
        );

        DB::statement(
            'DROP INDEX IF EXISTS idx_repairs_serial'
        );


        // Warranties
        DB::statement(
            'DROP INDEX IF EXISTS idx_warranties_serial'
        );


        // Expenses
        DB::statement(
            'DROP INDEX IF EXISTS idx_expenses_date'
        );
    }
};
