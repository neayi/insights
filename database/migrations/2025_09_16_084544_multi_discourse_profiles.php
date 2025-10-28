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
        // Creates table for Discourse profiles
        DB::statement(<<<SQL
            CREATE TABLE `discourse_profiles` (
                `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) UNSIGNED NOT NULL,
                `locale` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
                `ext_id` int(10) UNSIGNED NOT NULL,
                `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `synced_at` timestamp NULL DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_user_locale` (`user_id`, `locale`),
                INDEX `unique_ext_locale` (`ext_id`, `locale`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL);

        // Insert existing data into the new table
        DB::statement(<<<SQL
            INSERT INTO `discourse_profiles` (`user_id`, `locale`, `ext_id`, `username`, `synced_at`)
            SELECT
              u.id,
              COALESCE(u.default_locale, 'fr') AS locale,
              u.discourse_id AS ext_id,
              u.discourse_username,
              u.sync_at_discourse
            FROM users u
            WHERE u.discourse_id IS NOT NULL            
        SQL);

        // Drop former columns
        DB::statement(<<<SQL
            ALTER TABLE `users`
                DROP `discourse_id`,
                DROP `discourse_username`,
                DROP `sync_at_discourse`;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(<<<SQL
            ALTER TABLE `users`
                ADD `discourse_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                ADD `discourse_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                ADD `sync_at_discourse` datetime DEFAULT NULL;
        SQL);

        DB::statement(<<<SQL
            UPDATE users u
            JOIN discourse_profiles dp ON u.id = dp.user_id
            SET
                u.discourse_id = dp.ext_id,
                u.discourse_username = dp.username,
                u.sync_at_discourse = dp.synced_at;
        SQL);

        DB::statement(<<<SQL
            DROP TABLE discourse_profiles;
        SQL);
    }
};
