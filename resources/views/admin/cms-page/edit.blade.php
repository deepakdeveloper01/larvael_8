@extends('admin.layouts.admin_layout')
@section('content')
@section('custom_css')
<link rel="shortcut icon" type="image/png" href="{{ asset('vendor/laravel-filemanager/img/72px color.png') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
          <li class="breadcrumb-item"><a href="{!!route('cms-pages.index')!!}">Cms Page</a></li>
          <li class="breadcrumb-item active">Create</li>
        </ol>
      </div>
    </div>
    <hr/>          
	<div class="row">
	<!-- left column -->
		<div class="col-md-12">
			@include('admin.partials.flashes')
			<div class="card card-info">
				<div class="card-header">
					<h2 class="card-title">
						<i class="fas fa-plus"></i> Add New
					</h2>
					&nbsp;
					<a class="fas fa-reply-all btn-warning btn-sm" href="{!!route('cms-pages.index')!!}">	Back</a>
				</div>
				<div class="card-body">
				    {!! Form::model($CmsPage, ['method' => 'PATCH','files'=>true, 'route' => ['cms-pages.update', $CmsPage->id,'id'=>"cms-page-form",'class'=>'cms-page-form']]) !!}
				  	@include('admin.cms-page.form')
			
	               <div class="card-footer">
	                <button type="submit" id="submitCmsPageForm" class="btn btn-primary"><i class="fas fa-edit"> </i>  Update </button>
	                 <button type="reset" class="btn btn-danger"><i class="fas fa-times"> </i> Cancel</button>
	              </div>
	            </div>
           {!! Form::close() !!}	
        </div>
      </div>
    </div>
</div>



{{--  here will be shown the listing of all data--}}

@endsection
