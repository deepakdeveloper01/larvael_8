<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\CmsPageGallery;
use Validator;
use App\Http\Requests\CmsPageGallery\CreateRequest;
use App\Http\Requests\CmsPageGallery\UpdateRequest;
use App\Http\Requests\CmsPageGallery\CustomRequest;

class CmsPageGalleryController extends Controller
{
    public function index(Request $request){
        $CmsPages =  CmsPageGallery::orderBy('id','DESC')->paginate(5);
        if(!empty($request->id)){
            $CmsPages =$CmsPages = CmsPageGallery::where('cms_page_id',$request->id)->orderBy('id','DESC')->paginate(5);
        }    
        return view('admin.cms-page-gallery.index',compact('CmsPages'))
                ->with('i', ($request->input('page', 1) - 1) * 5);

    }

    public function create(CustomRequest $request, CmsPage $CmsPage)
    {   
        if($request->id && ($Page =CmsPage::where('id', '=', $request->id)->exists())){
            $CmsPage =$request->id;
            return view('admin.cms-page-gallery.create', compact('CmsPage'));   
        }       
        return redirect()->back()->with('danger','somthing went wrong');   
       
    }

    public function store(CreateRequest $request)
    {  
        
        $CmsPageGallery = CmsPageGallery::create($request->all());
        $image = $request->file('image_path');
        if(!empty($image)){
            $image_name = $request->cms_page_id.'-image-'.strtotime("now").'.'.$image->getClientOriginalExtension();
            $image->move(config('my_config.cms_page_gallery'), $image_name);
            $CmsPageGallery->image_path = $image_name;
            $CmsPageGallery->save();  
        }
       
        return redirect()->route('cms-pages-gallery.index',[$request->cms_page_id])->with('success','cms-page created successfully');
    }

    public function show(Request $request,$id){
       // dd($request->gallery_id);
        if($request->id && ($Page =CmsPageGallery::where('id', $request->gallery_id)->exists())){
            $CmsPageGallery = CmsPageGallery::findorfail($request->gallery_id);
           $CmsPage = $request->id;
            return view('admin.cms-page-gallery.show', compact('CmsPageGallery','CmsPage'));   
        }

        return redirect()->back()->with('danger','somthing went wrong');   
    }

    public function edit(CustomRequest $request, CmsPage $CmsPage){
        return view('admin.cms-page.edit',compact('CmsPage'));
    }


    public function update(UpdateRequest $request, CmsPage $CmsPage){

        $request['status'] = isset($request->status)?'1':'0';
        $request['show_slider']  =isset($request->show_gallery)?'1':'0';

        dd($request->all());
        
        $CmsPage->update($request->all());
        /*        if(!empty($image)){
            // deleting the previous image
            File::delete(config('autoguru.page_fimage').$pre_image);
            $page->featured_image = $page->slug.'-image-'.$page->id.'.'.$image->getClientOriginalExtension();
            $image->move(config('autoguru.page_fimage'), $page->featured_image);
            $page->save();
        }else{
            // rename the previous image file name
            if(File::exists(config('autoguru.page_fimage').$pre_image)){
                $new_name = str_replace($pre_slug,$page->slug,$pre_image);
                rename(config('autoguru.page_fimage').$pre_image,config('autoguru.page_fimage').$new_name);
                $page->featured_image = $new_name;
                $page->save();
            }
        }*/
        return redirect()->route('cms-pages.index')->withFlashMessage('Page added successfully!')->withFlashType('success');
    }


    public function destroy(CustomRequest $request, CmsPage $CmsPage){
       /* if(File::exists(config('autoguru.page_fimage').$page->featured_image)){
            File::delete(config('autoguru.page_fimage').$page->featured_image);
        }*/
        $CmsPage->delete();
        return redirect()->route('cms-pages.index')->withFlashMessage('Cms Page has been deleted successfully!')->withFlashType('success');
    }

    public function renderImageBlock(){
        $timestamp =strtotime("now");
         return View::make('admin.cms-page.image_block',compact('timestamp'));
    }

    public function renderTextBlock(){
        $timestamp =strtotime("now");
       return View::make('admin.cms-page.text_block',compact('timestamp'));

    }

     public function renderVideoBlock(){
        $timestamp =strtotime("now");
         return View::make('admin.cms-page.video_block',compact('timestamp'));
    }

}


