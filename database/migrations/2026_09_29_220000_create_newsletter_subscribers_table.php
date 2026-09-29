<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old newsletter table if it's empty/unused and create proper one
        // We'll just use the existing 'newsletter' table but update its structure
        // If newsletter table doesn't have 'email' column, we alter it

        if (!Schema::hasColumn('newsletter', 'email')) {
            Schema::table('newsletter', function (Blueprint $table) {
                $table->string('email')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('newsletter', 'subscribed_at')) {
            Schema::table('newsletter', function (Blueprint $table) {
                $table->timestamp('subscribed_at')->nullable()->after('newsletter_email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('newsletter', function (Blueprint $table) {
            if (Schema::hasColumn('newsletter', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('newsletter', 'subscribed_at')) {
                $table->dropColumn('subscribed_at');
            }
        });
    }
};
