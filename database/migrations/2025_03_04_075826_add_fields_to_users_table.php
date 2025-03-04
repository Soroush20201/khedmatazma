<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['regular', 'vip', 'admin'])->default('regular');
            }

            if (!Schema::hasColumn('users', 'score')) {
                $table->integer('score')->default(100);
            }

            if (!Schema::hasColumn('users', 'penalty_count')) {
                $table->integer('penalty_count')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'score')) {
                $table->dropColumn('score');
            }
            if (Schema::hasColumn('users', 'penalty_count')) {
                $table->dropColumn('penalty_count');
            }
        });
    }
};
