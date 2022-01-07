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
            <a class="btn btn-success border_radius " href="{{ route('cms-pages.create') }}" ><i class="fa fa-plus"></i> </a>
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
      	<div class="col-md-3 font-weight-bold"> Name </div>
      	<div class="col-sm-9">{!!($CmsPage->name)?$CmsPage->name:'--'!!}</div>

      	<div class="col-md-3 font-weight-bold"> Slug </div>
      	<div class="col-sm-9">{!!($CmsPage->slug)?$CmsPage->slug:'--'!!}</div>
      	<div class="col-md-3 font-weight-bold"> photo </div>
      	<div class="col-sm-9">
	      	<img src="{!!$CmsPage->image_path!!}" style="border-radius:10px; max-height: 350px; min-height: 250px;" class="img-fluid">	

      	</div>
      	<div class="col-md-3 font-weight-bold"> Status </div>
      	<div class="col-sm-9">
      		@if($CmsPage->status ==1)
      			<label class="badge badge-info"> Active</label>
      		@else
      			<label class="badge badge-warning">In- Active</label>
      		@endif
      	</div>
      	<div class=" col-md-12 view-info"></div>
      	<div class="col-md-3 font-weight-bold"> Sort Order </div>
      	<div class="col-sm-9">{!!isset($CmsPage->sort_order)?$CmsPage->sort_order:'--'!!}</div>
      	<div class="col-md-3 font-weight-bold"> Gallery Status </div>
      	<div class="col-sm-9">
      		@if($CmsPage->show_gallery ==1)
      			<label class="badge badge-info"> Allowed</label>
      		@else
      			<label class="badge badge-warning">Not Allowed</label>
      		@endif
      	</div>
      	<div class="col-md-3 font-weight-bold"> Published </div>
      	<div class="col-sm-9">
      		@if($CmsPage->is_publish ==1)
      			<label class="badge badge-success"> Published</label>
      		@else
      			<label class="badge badge-danger">Not Published</label>
      		@endif
      	</div>
      	<div class="col-md-3 font-weight-bold"> Short Description </div>
      	<div class="col-sm-9">{!!($CmsPage->short_description)?$CmsPage->short_description:'--'!!}</div>
      	<div class="col-md-3 font-weight-bold"> Description </div>
      	<div class="col-sm-9">{!!($CmsPage->description)?$CmsPage->description:'--'!!}
      	</div>
      </div>
    	@if(!empty($CmsPage->CmsPageAdditionalContents) && count($CmsPage->CmsPageAdditionalContents)>0)
    		@foreach($CmsPage->CmsPageAdditionalContents as $cmsPageContent)
      		@switch($cmsPageContent->cms_page_content_type_d)
    				@case('1')
    					@include('admin.cms-page.text_block', array('timestamp' => $cmsPageContent->order,'content'=>$cmsPageContent->content))
            @break
            @case('2')
    					@include('admin.cms-page.text_block', array('timestamp' => $cmsPageContent->order,'content'=>$cmsPageContent->content))
            @break
            @case('3')
    					@include('admin.cms-page.video_block', array('timestamp' => $cmsPageContent->order,'content'=>$cmsPageContent->content))
            @break  	
            @default
          @endswitch 
    		@endforeach
      	@endif

	<div class="row"> 
		<div class="col-md-12">
			<div class="card card-purple">
				<div class="card-header">
					<h4 class="card-title">
						<i class="fa fa-image"></i> 
						<strong>Gallery</strong>
					</h4>
					<div class="pull-right" style="float: right;"><a href="{!!route("cms-pages-gallery.create",$CmsPage->id)!!}"><i class="fa fa-plus btn-success border_radius"></i> Add </a></div>
				</div>
				<div class="card-body">
					<table class="table table-striped ">
            <thead>
              <tr>
                <th width="10%">#</th>
                <th width="20%">Name</th>
                <th width="10%">Order</th>
                <th width="40%">Gallery Image</th>
                <th width="20%">Action</th>
              </tr>
            </thead>
            <tbody>
            @if(count($CmsPage->cms_page_gallery)>0)
            	@foreach($CmsPage->cms_page_gallery as $key=> $singleGallery)
							<tr>
                <td>{!!$key+1!!}.</td>
                <td>{!!$singleGallery->name!!}</td>
                 <td>{!!$singleGallery->sort_order!!}</td>
                <td>
                	<img src="{!!asset('uploads/cms_page_gallery/'.$singleGallery->image_path)!!}" style="max-height:100px;" class="img-fluid">
                  {{-- <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger" style="width: 85%"></div>
                  </div> --}}
                </td>
                <td>
                	<a href="{!! route('cms-pages-gallery.show',['id'=>$CmsPage->id,'gallery_id'=>$singleGallery->id]) !!}" class="btn btn-info btn-sm border_radius"><i class="fas fa-eye"></i></a>
              		<a href="{!! route('cms-pages-gallery.edit', ['id'=>$CmsPage->id,'gallery_id'=>$singleGallery->id]) !!}" class="btn btn-default btn-sm border_radius"><i class="fas fa-edit"></i></a>
              		<a href="{!! route('cms-pages.show', 1) !!}" class="btn btn-warning btn-sm border_radius"><i class="fas fa-times"></i></a>
              		<a href="{!! route('cms-pages.show', 1) !!}" class="btn btn-danger btn-sm border_radius"><i class="fas fa-trash"></i></a>
              
                </td>
              </tr>	
              @endforeach
            @else
            	<tr>
            		<td colspan="5" class="text-center text-danger"> No Gallery Images Found!</td>
            	</tr>
            @endif 
	          </tbody>
	        </table>					
				</div>
			</div>		
		</div>
	</div>
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
@section('js')
<script src="{!!asset('js/article.js')!!}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
  <script>
   var route_prefix = "/filemanager";
  </script>

  <!-- CKEditor init -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js"></script>
  <script>
    $('.custom-textarea').ckeditor({
      height: 100,
      filebrowserImageBrowseUrl: route_prefix + '?type=Images',
      filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
      filebrowserBrowseUrl: route_prefix + '?type=Files',
      filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
    });

  {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
    $('#image_path').filemanager('image', {prefix: route_prefix});
    // $('#lfm').filemanager('file', {prefix: route_prefix});
    var lfm = function(id, type, options) {
      let button = document.getElementById(id);

      button.addEventListener('click', function () {
        var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
        var target_input = document.getElementById(button.getAttribute('data-input'));
        var target_preview = document.getElementById(button.getAttribute('data-preview'));

        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=500,height=300');
        window.SetUrl = function (items) {
          var file_path = items.map(function (item) {
            return item.url;
          }).join(',');

          // set the value of the desired input to image url
          target_input.value = file_path;
          target_input.dispatchEvent(new Event('change'));

          // clear previous preview
          target_preview.innerHtml = '';

          // set or change the preview image src
          items.forEach(function (item) {
            let img = document.createElement('img')
            img.setAttribute('style', 'height: 5rem')
            img.setAttribute('src', item.thumb_url)
            target_preview.appendChild(img);

          });

          // trigger change event
          target_preview.dispatchEvent(new Event('change'));
        };
      });
    };

    lfm('lfm2', 'file', {prefix: route_prefix});
  </script>

  <style>
    .popover {
      top: auto;
      left: auto;
    }
  </style>

  <script>
    $('.addtiontextarea').ckeditor({
      height: 100,
      filebrowserImageBrowseUrl: route_prefix + '?type=Images',
      filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
      filebrowserBrowseUrl: route_prefix + '?type=Files',
      filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
    });
  </script>
  @endsection