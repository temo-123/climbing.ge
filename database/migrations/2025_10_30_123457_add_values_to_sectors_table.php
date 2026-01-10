<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValuesToSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->boolean('is_helmet')->nullable()->default(false)->after('for_family');

            $table->text('ka_description')->nullable()->after('name');
            $table->text('us_description')->nullable()->after('name');

            $table->dropColumn('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropColumn('is_helmet');

            $table->dropColumn('ka_description');
            $table->dropColumn('us_description');

            $table->text('text')->nullable()->after('name');
        });
    }
}
