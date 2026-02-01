<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop foreign key lama
            try {
                $table->dropForeign(['product_id']);
            } catch (\Exception $e) {
                // Skip if doesn't exist
            }
            
            // Set product_id nullable
            $table->unsignedBigInteger('product_id')->nullable()->change();
            
            // Add foreign key dengan SET NULL
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('SET NULL');
            
            // Add snapshot columns
            if (!Schema::hasColumn('carts', 'product_title')) {
                $table->string('product_title')->nullable()->after('product_id');
            }
            
            if (!Schema::hasColumn('carts', 'product_photo')) {
                $table->string('product_photo')->nullable()->after('product_title');
            }
            
            if (!Schema::hasColumn('carts', 'product_summary')) {
                $table->text('product_summary')->nullable()->after('product_photo');
            }
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'product_summary')) {
                $table->dropColumn('product_summary');
            }
            
            if (Schema::hasColumn('carts', 'product_photo')) {
                $table->dropColumn('product_photo');
            }
            
            if (Schema::hasColumn('carts', 'product_title')) {
                $table->dropColumn('product_title');
            }
            
            $table->dropForeign(['product_id']);
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }
};