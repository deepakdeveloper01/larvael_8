<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByCmsPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->integer('created_by')->nullable(false)->after('is_publish');
            $table->integer('updated_by')->nullable(false)->after('created_by');;
            $table->integer('deleted_by')->nullable(false)->after('updated_by');;
            $table->integer('published_by')->nullable(false)->after('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->text('created_by')->nullable(false)->change();
            $table->text('updated_by')->nullable(false)->change();
            $table->text('deleted_by')->nullable(false)->change();
            $table->text('published_by')->nullable(false)->change();
            
        });
    }
}