<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPageAdditonalContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_page_additonal_content', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_page_id')->unsigned(); 
            
            $table->unsignedBigInteger('cms_page_content_type_d')->unsigned();      
            $table->text('content');          
            $table->string('order',255)->nullable(); 
            $table->boolean('status')->default(0)->comment('0=>In-Active,1=>Active,2=>Saved,');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');          
            $table->timestamps();
            $table->softDeletes(); // <-- This will add a deleted_at field

            $table->index('cms_page_id');
            $table->index('cms_page_content_type_d');
            $table->index('created_by');
            $table->index('updated_by');
            $table->foreign('cms_page_id')->references('id')->on('cms_pages')->onDelete('cascade');
            $table->foreign('cms_page_content_type_d')->references('id')->on('cms_page_contnet_types')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_page_additonal_content');
        $table->dropForeign('updated_by');
        $table->dropIndex('updated_by');
        $table->dropForeign('created_by');
        $table->dropIndex('created_by');
        
        $table->dropForeign('cms_page_content_type_d');
        $table->dropIndex('cms_page_content_type_d');
        $table->dropForeign('cms_page_id');
        $table->dropIndex('cms_page_id');

        $table->dropColumn('user_id');

    }

}
