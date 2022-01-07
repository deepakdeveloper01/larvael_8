<div class="form-group row">
	<label for="name" class="col-sm-2 col-form-label">Name</label>
	<div class="col-sm-10">
		{!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Name', 'id'=>'name','maxlength'=>'255']) !!}
    {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
	</div>
</div>
{{-- <div class="form-group row">
	<label for="slug" class="col-sm-2 col-form-label">slug</label>
	<div class="col-sm-10">
    {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>'slug','maxlength'=>'255']) !!}
    {!! $errors->first('slug', '<span class="text-danger">:message</span>') !!}
	</div>
</div> --}}

<div class="form-group row">
	{!! Form::label('template_type_id', 'Template Type ',array('class'=>"col-sm-2 col-form-label")) !!}</label>
	<div class="col-sm-10">
		{!! Form::select('template_type_id',array('3'=>'Select One','0'=>'None','1'=>'box','2'=>'Concertina','3'=>'Full Width'),null, ['class' => 'form-control','style'=>'width: 350px;', "id"=>"template_type_id"]) !!}
    {!! $errors->first('template_type_id', '<span class="text-danger">:message</span>') !!}
	</div>
</div>
<div class="form-group row">
  {!! Form::label('show_gallery', 'Show Gallery',['class'=>"col-sm-2 col-form-label"]) !!}

  <div class="col-sm-10">
    <div class="icheck-success d-inline">
      {!! Form::checkbox('show_gallery',0,null,['class'=>'icheck-primary d-inline','id'=>'checkboxSuccess1']) !!}
      <label for="checkboxSuccess1"></label>
    </div>
    {!! $errors->first('show_gallery', '<span class="text-danger">:message</span>') !!}
  </div>
</div>
<div class="form-group row">
  {!! Form::label('sort_order', 'Sort Order' ,['class'=>"col-sm-2 col-form-label"]) !!}
  <div class="col-sm-10">
   
       {!! Form::number('sort_order', null, ['min'=>1,'max'=>100,'class'=>'form-control']) !!}
      {!! $errors->first('sort_order', '<span class="text-danger">:message</span>') !!}
  </div>
</div>
<div class="form-group row">
	<label for="image_path" class="col-sm-2 col-form-label">Image /Logo</label>
	<div class="col-sm-10">
		
		<div class="input-group">
          <span class="input-group-btn">
            <a id="image_path" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
              <i class="fa fa-picture-o"></i> Choose
            </a>
          </span>
          {!! Form::text('image_path', null, ['class' => 'form-control', 'id'=>'thumbnail','placeholder'=>'select Image']) !!}
      {!! $errors->first('image_path', '<span class="text-danger">:message</span>') !!}
        
        </div>
	</div>
</div>

<div class="form-group row">
	<label for="short_description" class="col-sm-2 col-form-label">Short Description</label>
	<div class="col-sm-10">
		{!! Form::textarea('short_description',null, ['class' => 'form-control','cols'=>'3', 'rows'=>"3", "id"=>"short_description",'placeholder'=>'Add Some Short Details']) !!}

    {!! $errors->first('short_description', '<span class="text-danger">:message</span>') !!}
		
	</div>
</div>
<div class="form-group row">
	<label for="description" class="col-sm-2 col-form-label">Content</label>
	<div class="col-sm-10">
		{!! Form::textarea('description',null, ['class' => 'form-control description custom-textarea','cols'=>'10', 'rows'=>"10", "id"=>"description",'placeholder'=>'Add Some Short Details']) !!}

    {!! $errors->first('description', '<span class="text-danger">:message</span>') !!}
	</div>
</div>
<div class="article_form_content"></div>
<div class="blog_form_buttons">
    <button type="button" class="btn btn-primary fas fa-images" id="insert-image">  Insert Image</button>
    <button type="button" class="btn btn-primary fas fa-edit" id="insert-text"> Insert Text</button>
    <button type="button" class="btn btn-primary fas fa-video-camera" id="insert-video"> Insert Video Url</button>
</div>

    
  </div>
 
    </div>
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