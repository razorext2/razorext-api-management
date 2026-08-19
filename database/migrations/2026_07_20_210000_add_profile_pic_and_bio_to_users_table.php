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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'profile_pic')) {
                $table->string('profile_pic')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('users', 'bio')) {
                $table->string('bio', 20)->nullable()->after('profile_pic');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_pic', 'bio']);
        });
    }
};
