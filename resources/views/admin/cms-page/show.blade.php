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
        <h1><i class="fa fa-file"></i> Cms Page Management 
            <a class="btn btn-success" href="{{ route('cms-pages.create') }}" style="border-radius: 50%;"><i class="fa fa-plus"></i> </a>
         </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{!!route('home')!!}">Home</a></li>
          <li class="breadcrumb-item active"><a href="{!!route('cms-pages.index')!!}">Cms Page</a></li>
          <li class="breadcrumb-item">Page Info</li>
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
        <i class="fas fa-list"></i> Details of:- <strong>{!!$CmsPage->name!!}</strong> </h4>
      </div>
      <div class="card-body">
      <div class="row">
      	<div class="col-md-3"> Name </div>
      	<div class="col-sm-9">{!!($CmsPage->name)?$CmsPage->name:'--'!!}</div>

      	<div class="col-md-3"> Slug </div>
      	<div class="col-sm-9">{!!($CmsPage->slug)?$CmsPage->slug:'--'!!}</div>
      	<div class="col-md-3"> photo </div>
      	<div class="col-sm-9">
	      	<img src="{!!$CmsPage->image_path!!}" style="border-radius:10px; max-height: 350px; min-height: 250px;">	

      	</div>
      	<div class="col-md-3"> Status </div>
      	<div class="col-sm-9">
      		@if($CmsPage->status ==1)
      			<label class="badge badge-info"> Active</label>
      		@else
      			<label class="badge badge-warning">In- Active</label>
      		@endif
      	</div>
      	<div class="col-md-3"> Sort Order </div>
      	<div class="col-sm-9">{!!isset($CmsPage->sort_order)?$CmsPage->sort_order:'--'!!}</div>
      	<div class="col-md-3"> Gallery Status </div>
      	<div class="col-sm-9">
      		@if($CmsPage->show_gallery ==1)
      			<label class="badge badge-info"> Allowed</label>
      		@else
      			<label class="badge badge-warning">Not Allowed</label>
      		@endif
      	</div>
      	<div class="col-md-3"> Published </div>
      	<div class="col-sm-9">
      		@if($CmsPage->is_publish ==1)
      			<label class="badge badge-success"> Published</label>
      		@else
      			<label class="badge badge-danger">Not Published</label>
      		@endif
      	</div>
      	<div class="col-md-3"> Short Description </div>
      	<div class="col-sm-9">{!!($CmsPage->short_description)?$CmsPage->short_description:'--'!!}</div>
      	<div class="col-md-3"> Description </div>
      	<div class="col-sm-9">{!!($CmsPage->description)?$CmsPage->description:'--'!!}</div>

      </div>
      </div>  
    </div>  
    </div>
</div>
</div>

@endsection
