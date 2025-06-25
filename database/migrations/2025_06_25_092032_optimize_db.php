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
        // Table characteristics
        DB::statement('ALTER TABLE characteristics ADD UNIQUE INDEX unique_uuid (uuid);');
        DB::statement('ALTER TABLE characteristics ADD INDEX index_code_wiki (code, wiki);');
        DB::statement('ALTER TABLE characteristics ADD INDEX index_page_id_wiki (page_id, wiki);');
        DB::statement('ALTER TABLE characteristics ADD INDEX index_type_wiki (type, wiki);');

        // Table contexts
        DB::statement('ALTER TABLE contexts ADD UNIQUE INDEX unique_uuid (uuid);');
        DB::statement('ALTER TABLE contexts ADD INDEX index_department_number (department_number);');

        // Table interactions
        DB::statement('ALTER TABLE interactions ADD INDEX index_page_id_wiki (page_id, wiki);');
        DB::statement('ALTER TABLE interactions ADD INDEX index_user_id (user_id);');
        DB::statement('ALTER TABLE interactions ADD INDEX index_cookie_user_session_id (cookie_user_session_id);');

        // Table password_resets
        DB::statement('ALTER TABLE password_resets ADD UNIQUE INDEX unique_token (token);');

        // Table users
        DB::statement('ALTER TABLE users CHANGE wiki_token wiki_token VARCHAR(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE users ADD INDEX index_wiki_token (wiki_token);'); // This index is not unique because multiple users habe the same wiki_token (?)

        // Table user_characteristics
        DB::statement('ALTER TABLE user_characteristics ADD INDEX index_user_id (user_id);');
        DB::statement('ALTER TABLE user_characteristics ADD INDEX index_characteristic_id (characteristic_id);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Table characteristics
        DB::statement('ALTER TABLE characteristics DROP INDEX unique_uuid;');
        DB::statement('ALTER TABLE characteristics DROP INDEX index_code_wiki;');
        DB::statement('ALTER TABLE characteristics DROP INDEX index_page_id_wiki;');
        DB::statement('ALTER TABLE characteristics DROP INDEX index_type_wiki;');

        // Table contexts
        DB::statement('ALTER TABLE contexts DROP INDEX unique_uuid;');
        DB::statement('ALTER TABLE contexts DROP INDEX index_department_number;');

        // Table interactions
        DB::statement('ALTER TABLE interactions DROP INDEX index_page_id_wiki;');
        DB::statement('ALTER TABLE interactions DROP INDEX index_user_id;');
        DB::statement('ALTER TABLE interactions DROP INDEX index_cookie_user_session_id;');

        // Table password_resets
        DB::statement('ALTER TABLE password_resets DROP INDEX unique_token;');

        // Table users
        DB::statement('ALTER TABLE users DROP INDEX index_wiki_token;');

        // Table user_characteristics
        DB::statement('ALTER TABLE user_characteristics DROP INDEX index_user_id;');
        DB::statement('ALTER TABLE user_characteristics DROP INDEX index_characteristic_id;');
    }
};
