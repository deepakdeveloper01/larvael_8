<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateCmsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('slug',255)->unique()->comment('name of the page will use on url');
            $table->string('image_path','255')->nullable();
            $table->text('short_description','500')->nullable();
            $table->text('description')->nullable(); 
            $table->boolean('status')->default(0)->comment('0=>In-Active,1=>Active,2=>Saved,');
             $table->boolean('show_gallery')->default(0)->comment('if 0=>not show else 1 than show gallery');
            $table->boolean('is_publish')->default(0)->comment('0=>Un-published,1=>Published');

            $table->timestamps();
            $table->softDeletes(); // <-- This will add a deleted_at field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_pages');
    }
}
