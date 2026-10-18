<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the organization_legal_info site-data item (locale_sites): legal name,
 * legal form, registration number and address of the organization, shown in the
 * "Organization Contacts" block of the guide About page. Inserted EMPTY (the
 * About page hides the block until it's filled) — fill it in the admin site-info
 * page or with docs/BACKEND/sql-imports/donation_legal_texts.sql.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('locale_sites')->where('slug', 'organization_legal_info')->exists()) {
            return;
        }

        DB::table('locale_sites')->insert([
            'slug'       => 'organization_legal_info',
            'us_data'    => '',
            'ka_data'    => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('locale_sites')->where('slug', 'organization_legal_info')->delete();
    }
};
