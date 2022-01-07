<div class="sortable-item" id="<?php echo $timestamp ?>">
    <div class="remove-blogContent text-right">
        <a href="#" class="fas fa fa-trash"></a>
    </div>
    <div class="form-group row">
		<label for="description" class="col-sm-2 col-form-label"for="CmsPage_body_<?php echo $timestamp ?>">Video URL</label>
		<div class="col-sm-10">
  	        <input class="form-control form-order-data" id="ArticleForm_body_<?php echo $timestamp ?>" name="CmsPage[videos][<?php echo $timestamp ?>]"
                value="<?php echo $content ?? '' ?>">
        	<div class="help-block">For youtube videos please use the 'share' url, for example: https://youtu.be/dQw4w9WgXcQ</div>
    	</div>
	</div>
</div>	


