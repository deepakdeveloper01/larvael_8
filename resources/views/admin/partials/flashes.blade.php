@if(Session::has('flash_message'))
<div class="alert flash-message text-left alert-{!! Session::get('flash_type', 'info') !!} alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  {!! Session::get('flash_message') !!} </div>
@endif 

@if(!empty(Session::get('errors')))
<div class="alert text-left alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
 <strong>Whoops!</strong> There were some problems with your input.</div>
@endif 