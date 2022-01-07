<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Auth;
class CmsPageAdditionalContent extends Model
{

    use SoftDeletes;
    protected $table        = 'cms_page_additonal_content';
    protected $primaryKey   = 'id';
    public $timestamps   = true;

    protected $fillable     = [
                                'cms_page_id', 'cms_page_content_type_d', 'content', 'order', 'status', 
                                'created_by', 'updated_by', 'deleted_by'
                            ];

    public static function createContent($contentTypeId, $content, $articleId, $order): bool
    {
        $articleContent = new static(
            [
                'cms_page_id'     => $articleId,
                'order'         => $order,
                'cms_page_content_type_d' => $contentTypeId,
                'content'       => $content,
                'created_by' =>auth()->user()->id,
                'status'=>1,
            ]
        );

        return $articleContent->save();
    }

}
