<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateCmsPageGallery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_page_gallery', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('cms_page_id')->unsigned(); 
            $table->string('name',255);
          
            $table->string('image_path','255')->nullable();
            $table->text('short_description','500')->nullable();
            $table->boolean('status')->default(0)->comment('0=>In-Active, 1=>Active,2=>Saved');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_page_gallery');
            $table->dropForeign('cms_page_id');
            $table->dropIndex('cms_page_id');
    }
}
