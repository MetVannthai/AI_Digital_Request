<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('pin')->constrained('departments')->nullOnDelete();
            }
        });

        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'sku')) {
                $table->string('sku')->nullable()->after('name');
            }

            if (! Schema::hasColumn('items', 'current_stock')) {
                $table->integer('current_stock')->default(0)->after('unit');
            }

            if (! Schema::hasColumn('items', 'reorder_level')) {
                $table->integer('reorder_level')->nullable()->after('min_stock');
            }

            if (! Schema::hasColumn('items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('reorder_level');
            }
        });

        Schema::table('requests', function (Blueprint $table) {
            if (! Schema::hasColumn('requests', 'requested_by')) {
                $table->foreignId('requested_by')->nullable()->after('tracking_code')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('requests', 'issued_by')) {
                $table->foreignId('issued_by')->nullable()->after('rejected_reason')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('requests', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('issued_by');
            }
        });

        Schema::table('stock_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_transactions', 'type')) {
                $table->enum('type', ['IN', 'OUT', 'ADJUSTMENT'])->default('IN')->after('item_id');
            }

            if (! Schema::hasColumn('stock_transactions', 'note')) {
                $table->text('note')->nullable()->after('reference_id');
            }
        });

        DB::statement('UPDATE items SET current_stock = current_balance WHERE current_stock IS NULL AND current_balance IS NOT NULL');
        DB::statement('UPDATE items SET sku = item_code WHERE sku IS NULL AND item_code IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transactions', 'note')) {
                $table->dropColumn('note');
            }
        });

        Schema::table('requests', function (Blueprint $table) {
            if (Schema::hasColumn('requests', 'issued_at')) {
                $table->dropColumn('issued_at');
            }

            if (Schema::hasColumn('requests', 'issued_by')) {
                $table->dropConstrainedForeignId('issued_by');
            }

            if (Schema::hasColumn('requests', 'requested_by')) {
                $table->dropConstrainedForeignId('requested_by');
            }
        });

        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('items', 'reorder_level')) {
                $table->dropColumn('reorder_level');
            }

            if (Schema::hasColumn('items', 'current_stock')) {
                $table->dropColumn('current_stock');
            }

            if (Schema::hasColumn('items', 'sku')) {
                $table->dropColumn('sku');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department_id')) {
                $table->dropConstrainedForeignId('department_id');
            }
        });
    }
};
