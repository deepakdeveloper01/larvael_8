<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\CmsPageContentType;
use App\Models\CmsPageAdditionalContent;
use Log;
use Auth;
use Illuminate\Http\Request;

class CmsPage extends Model
{
   
    use SoftDeletes; 
    protected $table        = 'cms_pages';
    protected $primaryKey   = 'id';
    public $timestamps   = true;

    protected $fillable     = [
                                'template_type_id', 'name', 'slug', 'image_path',
                                'short_description', 'description', 'status',
                                'sort_order', 'show_gallery', 'is_publish',
                                'created_by', 'updated_by', 'deleted_by'
                            ];
    public $images;
    public $existingImages = [];
    public $bodies;
    public $videos;
    public $thumbnail;
    protected $uploadedImages = [];



    public function CmsPageAdditionalContents()
    {
        return $this->hasMany('App\Models\CmsPageAdditionalContent');
    }
    // Boot the model.
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($CmsPage) {
             Log::info('Creating event call: '.$CmsPage); 
            $CmsPage->slug = $CmsPage->createSlug($CmsPage->name);
            $CmsPage->created_by = Auth::id();
            $CmsPage->updated_by =Auth::id();
           
           // $CmsPage->save();
        });
              
        /*static::creatingcreating(function($item) {            
            Log::info('Creating event call: '.$item); 
            $item->slug = Str::slug($item->name);

        });*/

       static::created(function($CmsPage) {           

           Log::info('Created event call: '.$CmsPage);
           $CmsPage->slug = $CmsPage->createSlug($CmsPage->name);
           $CmsPage->save();
           //dd(request()->bodies);
           $CmsPage->saveText(request()->bodies,$CmsPage);

        });


        /*static::updating(function($item) {            
           // CmsPageAdditionalContent::deleteAll(['cms_page_id' => $this->id]);
            Log::info('Updating event call: '.$item); 
            $item->slug = Str::slug($item->name);

            //$this->saveImages();
            $this->saveText();
           // $this->saveVideos();
        });*/

        static::updated(function($item) {  
            Log::info('Updated event call: '.$item);
             $item->CmsPageAdditionalContents()->delete();  

            //$this->saveImages();
            $item->saveText(request()->bodies,$item);    
        });

        static::deleted(function($item) {  
         $item->CmsPageAdditionalContents()->delete();          
            
            Log::info('Deleted event call: '.$item); 

        });
    }

    private function saveText($CmsPage,$CmsPageDATA)
    {
        echo ('text fucntion called');
        //dd($CmsPage);
        if (!empty($CmsPage) && \is_array($CmsPage)) {
            foreach ($CmsPage as $key => $body) {
                CmsPageAdditionalContent::createContent(
                    2,
                    $body,
                    $CmsPageDATA->id,
                    $key
                );
            }
        }
    }

    

    /** 
     * Write code on Method
     *
     * @return response()
    */

    private function createSlug($title){
        $title = 'Laravel 8 Image Upload';
        if (static::whereSlug($slug = Str::slug($title),'%')->exists()) {

            $max = static::whereName($title)->latest('id')->skip(1)->value('slug'); 
            //dd($max);
            if (!empty($max) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            $original = $slug;

               $count = 2;

               while (static::whereSlug($slug)-> exists()) {

                  $slug = "{$original}-".$count++;
               }
            //return "{$slug}-2";
        }
        return $slug;
    }                   
}