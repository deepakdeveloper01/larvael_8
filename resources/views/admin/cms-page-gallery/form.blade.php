
<div class="form-group row">
	<label for="name" class="col-sm-2 col-form-label">Name</label>
	<div class="col-sm-10">
		{!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Name', 'id'=>'name','maxlength'=>'255']) !!}
    {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
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
    <input type="file" name="image_path" class="form-control" id ="thumbnail" onchange="readURL(this)">
    {{-- {!! Form::file('image_path', null, ['class' => 'form-control', 'id'=>'thumbnail','placeholder'=>'select Image','onclick'=>'readURL($this)']) !!} --}}
    <div class="hide_div" style="max-height: 200px; display: none;">
      <img id="blah" src="" style="max-height: 200px; padding: 10px;">
    </div>
          
    {!! $errors->first('image_path', '<span class="text-danger">:message</span>') !!}

  </div>
</div>
<div class="form-group row">
	<label for="short_description" class="col-sm-2 col-form-label">Short Description</label>
  <div class="col-sm-10">
  	{!! Form::textarea('short_description',null, ['class' => 'form-control','cols'=>'3', 'rows'=>"3", "id"=>"short_description",'placeholder'=>'Add Some Short Details']) !!}
    {!! $errors->first('short_description', '<span class="text-danger">:message</span>') !!}
	</div>

  <input type="hidden" name="cms_page_id" value="{!!$CmsPage!!}">
</div>
@section('js')
<script type="text/javascript">
function readURL(input) {
  var url = input.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if ((input.files && input.files[0]) && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
      var reader = new FileReader();
      reader.onload = function(e) {
          $('.hide_div').show();
          $('#blah').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
  } else {
      alert('file type not support! Please change the file')
  }
}   
  </script>
  @endsection