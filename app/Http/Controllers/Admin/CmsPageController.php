<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\CmsPage;
use DB;
use Validator;
use App\Models\CmsPageContentType;
use App\Models\CmsPageAdditionalContent;
use App\Http\Requests\CmsPage\CreateRequest;
use App\Http\Requests\CmsPage\UpdateRequest;
use App\Http\Requests\CmsPage\CustomRequest;
use File;
use View;

class CmsPageController extends Controller
{
    public function index(Request $request){
        $CmsPages =  CmsPage::orderBy('id','DESC')->get();
        return view('admin.cms-page.index',compact('CmsPages'))->with('i', ($request->input('page', 1) - 1) * 10);
       // dd($cmsPage);
    }

    public function create(CustomRequest $request, CmsPage $CmsPage)
    {
        $CmsPageContentType = CmsPageContentType::all()->pluck('name','id');
        return view('admin.cms-page.create',compact('CmsPageContentType'));
    }

    public function store(CreateRequest $request)
    {  
//        dd($request->all());
        $CmsPage = CmsPage::create($request->all());
        if (!empty($request->bodies) && \is_array($CmsPage)) {
            foreach ($request->bodies as $key => $body) {
                CmsPageAdditionalContent::create([  2,
                    $body,
                    $CmsPage->id,
                    $key
                ]);
            }
        }
       
        return redirect()->route('cms-pages.index')->with('success','cms-page created successfully');
    }

    public function show($id){
        $CmsPage = CmsPage::findorfail($id);
       
        return view('admin.cms-page.show')->with(compact('CmsPage'));
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


