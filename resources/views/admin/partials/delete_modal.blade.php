<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-delete-{!! $data->id !!}" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
 
<div id="modal-delete-{!! $data->id !!}" class="modal fade text-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		{!! Form::open(['method' => 'DELETE', 'spellcheck'=>'true','route' => ["$name.destroy", $data->id]])!!}
	
		<div class="modal-header">
			<h4 class="modal-title">Delete Data</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
				
		<div class="modal-body">
			
				Are you sure want to delete this data?
		
			</p>
		</div>
		
		<div class="modal-footer justify-content-between">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-primary">Delete</button>
		</div>
	
		{!! Form::close() !!}
		</div>
	</div>
</div>