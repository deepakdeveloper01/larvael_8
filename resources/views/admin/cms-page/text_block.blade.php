<div class="sortable-item" id="<?php echo $timestamp ?>">
    <div class="remove-blogContent text-right">
        <a href="#" class="fas fa fa-trash"></a>
    </div>
    <div class="form-group row">
		<label for="description" class="col-sm-2 col-form-label" for="CmsPage_body_<?php echo $timestamp ?>">Content</label>
		<div class="col-sm-10">
	   
	        <textarea
	                class="form-control addtiontextarea form-order-data"
	                id="CmsPage_body_<?php echo $timestamp ?>"
	                name="bodies[<?php echo $timestamp ?>]" row = "300"
	        ><?php echo $content ?? '' ?></textarea>
	    </div>
	</div>
</div>
