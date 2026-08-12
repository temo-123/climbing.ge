<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `trainings.id` / `training_plans.id` were slug-style varchar primary keys.
     * This converts both to normal auto_increment bigint PKs (matching every
     * other table in the app) while preserving the old slug as a unique
     * `slug` column, and remaps all child foreign keys to the new integer
     * ids. No rows are dropped/reinserted -- existing data is preserved.
     */
    public function up(): void
    {
        // 1. Drop FK constraints + unique keys that reference the string PKs
        //    so trainings.id / training_plans.id can be altered.
        Schema::table('training_steps', function (Blueprint $table) {
            $table->dropForeign('training_steps_training_id_foreign');
            $table->dropUnique('uniq_training_step_order');
        });
        Schema::table('training_translations', function (Blueprint $table) {
            $table->dropForeign('training_translations_training_id_foreign');
            $table->dropUnique('uniq_training_locale');
        });
        Schema::table('plan_session_trainings', function (Blueprint $table) {
            $table->dropForeign('plan_session_trainings_training_id_foreign');
        });
        Schema::table('plan_translations', function (Blueprint $table) {
            $table->dropForeign('plan_translations_plan_id_foreign');
            $table->dropUnique('uniq_plan_locale');
        });
        Schema::table('plan_sessions', function (Blueprint $table) {
            $table->dropForeign('plan_sessions_plan_id_foreign');
            $table->dropUnique('uniq_plan_day');
        });

        // 2. trainings: preserve old string id as `slug`, replace id with
        //    a fresh auto_increment bigint PK.
        $this->convertToIntegerPk('trainings');

        // 3. training_plans: same treatment.
        $this->convertToIntegerPk('training_plans');

        // 4. Remap child FK columns from the old slug values to the new
        //    integer ids.
        $this->remapForeignKey('training_steps', 'training_id', 'trainings');
        $this->remapForeignKey('training_translations', 'training_id', 'trainings');
        $this->remapForeignKey('plan_session_trainings', 'training_id', 'trainings');
        $this->remapForeignKey('plan_translations', 'plan_id', 'training_plans');
        $this->remapForeignKey('plan_sessions', 'plan_id', 'training_plans');

        // 5. Recreate unique keys + FK constraints on the now-integer columns.
        Schema::table('training_steps', function (Blueprint $table) {
            $table->unique(['training_id', 'step_order'], 'uniq_training_step_order');
            $table->foreign('training_id', 'training_steps_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('cascade');
        });
        Schema::table('training_translations', function (Blueprint $table) {
            $table->unique(['training_id', 'locale'], 'uniq_training_locale');
            $table->foreign('training_id', 'training_translations_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('cascade');
        });
        Schema::table('plan_session_trainings', function (Blueprint $table) {
            $table->foreign('training_id', 'plan_session_trainings_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('restrict');
        });
        Schema::table('plan_translations', function (Blueprint $table) {
            $table->unique(['plan_id', 'locale'], 'uniq_plan_locale');
            $table->foreign('plan_id', 'plan_translations_plan_id_foreign')
                ->references('id')->on('training_plans')->onDelete('cascade');
        });
        Schema::table('plan_sessions', function (Blueprint $table) {
            $table->unique(['plan_id', 'day_index'], 'uniq_plan_day');
            $table->foreign('plan_id', 'plan_sessions_plan_id_foreign')
                ->references('id')->on('training_plans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('training_steps', function (Blueprint $table) {
            $table->dropForeign('training_steps_training_id_foreign');
            $table->dropUnique('uniq_training_step_order');
        });
        Schema::table('training_translations', function (Blueprint $table) {
            $table->dropForeign('training_translations_training_id_foreign');
            $table->dropUnique('uniq_training_locale');
        });
        Schema::table('plan_session_trainings', function (Blueprint $table) {
            $table->dropForeign('plan_session_trainings_training_id_foreign');
        });
        Schema::table('plan_translations', function (Blueprint $table) {
            $table->dropForeign('plan_translations_plan_id_foreign');
            $table->dropUnique('uniq_plan_locale');
        });
        Schema::table('plan_sessions', function (Blueprint $table) {
            $table->dropForeign('plan_sessions_plan_id_foreign');
            $table->dropUnique('uniq_plan_day');
        });

        $this->remapForeignKeyBack('training_steps', 'training_id', 'trainings');
        $this->remapForeignKeyBack('training_translations', 'training_id', 'trainings');
        $this->remapForeignKeyBack('plan_session_trainings', 'training_id', 'trainings');
        $this->remapForeignKeyBack('plan_translations', 'plan_id', 'training_plans');
        $this->remapForeignKeyBack('plan_sessions', 'plan_id', 'training_plans');

        $this->convertToStringPk('trainings');
        $this->convertToStringPk('training_plans');

        Schema::table('training_steps', function (Blueprint $table) {
            $table->unique(['training_id', 'step_order'], 'uniq_training_step_order');
            $table->foreign('training_id', 'training_steps_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('cascade');
        });
        Schema::table('training_translations', function (Blueprint $table) {
            $table->unique(['training_id', 'locale'], 'uniq_training_locale');
            $table->foreign('training_id', 'training_translations_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('cascade');
        });
        Schema::table('plan_session_trainings', function (Blueprint $table) {
            $table->foreign('training_id', 'plan_session_trainings_training_id_foreign')
                ->references('id')->on('trainings')->onDelete('restrict');
        });
        Schema::table('plan_translations', function (Blueprint $table) {
            $table->unique(['plan_id', 'locale'], 'uniq_plan_locale');
            $table->foreign('plan_id', 'plan_translations_plan_id_foreign')
                ->references('id')->on('training_plans')->onDelete('cascade');
        });
        Schema::table('plan_sessions', function (Blueprint $table) {
            $table->unique(['plan_id', 'day_index'], 'uniq_plan_day');
            $table->foreign('plan_id', 'plan_sessions_plan_id_foreign')
                ->references('id')->on('training_plans')->onDelete('cascade');
        });
    }

    /**
     * Move the current varchar `id` into a new unique `slug` column, then
     * replace `id` with a fresh auto_increment bigint primary key.
     */
    protected function convertToIntegerPk(string $table): void
    {
        Schema::table($table, function (Blueprint $blueprint) use ($table) {
            $blueprint->string('slug', 64)->nullable()->after('id');
        });
        DB::statement("UPDATE `{$table}` SET `slug` = `id`");
        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->string('slug', 64)->nullable(false)->change();
        });
        DB::statement("ALTER TABLE `{$table}` ADD UNIQUE KEY `uniq_{$table}_slug` (`slug`)");
        DB::statement("ALTER TABLE `{$table}` DROP PRIMARY KEY, DROP COLUMN `id`");
        DB::statement("ALTER TABLE `{$table}` ADD COLUMN `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST");
    }

    /**
     * Reverse of convertToIntegerPk: drop the integer id, restore the
     * varchar id from `slug`, drop `slug`.
     */
    protected function convertToStringPk(string $table): void
    {
        DB::statement("ALTER TABLE `{$table}` DROP PRIMARY KEY, DROP COLUMN `id`");
        DB::statement("ALTER TABLE `{$table}` DROP KEY `uniq_{$table}_slug`");
        DB::statement("ALTER TABLE `{$table}` CHANGE COLUMN `slug` `id` VARCHAR(64) NOT NULL FIRST");
        DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
    }

    /**
     * Convert a varchar FK column (holding the old slug values) into a
     * bigint FK column holding the referenced table's new integer id.
     */
    protected function remapForeignKey(string $table, string $column, string $referencedTable): void
    {
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->unsignedBigInteger($column . '_new')->nullable()->after($column);
        });
        DB::statement(
            "UPDATE `{$table}` t JOIN `{$referencedTable}` r ON t.`{$column}` = r.`slug` ".
            "SET t.`{$column}_new` = r.`id`"
        );
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->dropColumn($column);
        });
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->renameColumn($column . '_new', $column);
        });
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->unsignedBigInteger($column)->nullable(false)->change();
        });
    }

    /**
     * Reverse of remapForeignKey: convert the bigint FK column back to the
     * referenced table's slug value (a varchar).
     */
    protected function remapForeignKeyBack(string $table, string $column, string $referencedTable): void
    {
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->string($column . '_old', 64)->nullable()->after($column);
        });
        DB::statement(
            "UPDATE `{$table}` t JOIN `{$referencedTable}` r ON t.`{$column}` = r.`id` ".
            "SET t.`{$column}_old` = r.`slug`"
        );
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->dropColumn($column);
        });
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->renameColumn($column . '_old', $column);
        });
        Schema::table($table, function (Blueprint $blueprint) use ($column) {
            $blueprint->string($column, 64)->nullable(false)->change();
        });
    }
};
