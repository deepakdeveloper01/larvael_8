@extends('admin.layouts.admin_layout')
@section('content')
@section('custom_css')
 <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')!!}">
  <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')!!}">
  <link rel="stylesheet" href="{!!asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')!!}">
@endsection  
<div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><i class="fa fa-file"></i> Cms Page Gallery
            <a class="btn btn-success border_radius " href="{{ route('cms-pages.create') }}" ><i class="fa fa-plus"></i> </a>
         </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{!!route('home')!!}">Home</a></li>
          <li class="breadcrumb-item "><a href="{!!route('cms-pages.index')!!}">Cms Page</a></li>
          <li class="breadcrumb-item active"><a href="{!!route('cms-pages-gallery.index',$CmsPage)!!}">Gallery</a></li>
          <li class="breadcrumb-item ">Gallery Info</li>
        </ol>
      </div>
    </div>
    <hr/> 
   
<div class="row">
  <div class="col-md-12">
    @include('admin.partials.flashes')
    <div class="card card-dark">
      <div class="card-header">
        <h4 class="card-title">
        <i class="fas fa-list"></i> Details of:- <strong>{!!$CmsPageGallery->name!!}</strong> </h4>
        <div class="pull-right" style="float: right;"><a href="{!!route('cms-pages-gallery.index',$CmsPage)!!}"><i class="fa fa-reply-all"></i>  Gallery List</a></div>
      </div>
      <div class="card-body">
      <div class="row">
      	<div class="col-md-3"> Name </div>
      	<div class="col-sm-9">{!!($CmsPageGallery->name)?$CmsPageGallery->name:'--'!!}</div>

      	
      	<div class="col-md-3"> photo </div>
      	<div class="col-sm-9">
	      	<img src="{!!asset('uploads/cms_page_gallery/'.$CmsPageGallery->image_path)!!}" style="border-radius:10px; max-height: 350px; min-height: 250px;">	

      	</div>
      	<div class="col-md-3"> Status </div>
      	<div class="col-sm-9">
      		@if($CmsPageGallery->status ==1)
      			<label class="badge badge-info"> Active</label>
      		@else
      			<label class="badge badge-warning">In- Active</label>
      		@endif
      	</div>
      	<div class=" col-md-12 view-info"></div>
      	<div class="col-md-3"> Sort Order </div>
      	<div class="col-sm-9">{!!isset($CmsPageGallery->sort_order)?$CmsPageGallery->sort_order:'--'!!}</div>
      

      </div>
	

      </div>  
    </div>  
    </div>
</div>
</div>

@endsection
@section('custom_css')
<style type="text/css">
.view-info{
	padding: 5px;
}
</style>
@endsection