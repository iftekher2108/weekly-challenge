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
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_active');
            $table->index('company_id');
            $table->index(['role', 'is_active']);
            $table->index(['company_id', 'is_active']);
        });

        // Companies table indexes
        Schema::table('companies', function (Blueprint $table) {
            $table->index('status');
            $table->index('name');
            $table->index(['status', 'name']);
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('company_id');
            $table->index(['user_id', 'company_id']);
            $table->index(['parent_id', 'company_id']);
            $table->index('title');
        });

        // Tasks table indexes
        Schema::table('tasks', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('cat_id');
            $table->index('company_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('progress');
            $table->index(['user_id', 'status']);
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'due_date']);
            $table->index(['user_id', 'company_id']);
            $table->index(['cat_id', 'company_id']);
            $table->index(['status', 'due_date']);
            $table->index(['company_id', 'status', 'due_date']);
        });

        // Company_user pivot table indexes
        Schema::table('company_user', function (Blueprint $table) {
            $table->index('role');
            $table->index(['user_id', 'role']);
            $table->index(['company_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['company_id', 'is_active']);
        });

        // Companies table indexes
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['name']);
            $table->dropIndex(['status', 'name']);
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['user_id', 'company_id']);
            $table->dropIndex(['parent_id', 'company_id']);
            $table->dropIndex(['title']);
        });

        // Tasks table indexes
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['cat_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['progress']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['company_id', 'due_date']);
            $table->dropIndex(['user_id', 'company_id']);
            $table->dropIndex(['cat_id', 'company_id']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['company_id', 'status', 'due_date']);
        });

        // Company_user pivot table indexes
        Schema::table('company_user', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['user_id', 'role']);
            $table->dropIndex(['company_id', 'role']);
        });
    }
};
