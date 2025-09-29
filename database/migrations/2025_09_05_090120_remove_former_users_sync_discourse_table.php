<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP TABLE users_sync_discourse;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(<<<SQL
            CREATE TABLE `users_sync_discourse` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                `sync` tinyint(1) NOT NULL DEFAULT '0',
                `sync_at` datetime DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `users_sync_discourse_user_id_unique` (`user_id`),
                UNIQUE KEY `users_sync_discourse_uuid_unique` (`uuid`)
            ) ENGINE=InnoDB AUTO_INCREMENT=854 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL);
    }
};
