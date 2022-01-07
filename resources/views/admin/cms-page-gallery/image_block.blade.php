<div class="sortable-item" id="<?php echo $timestamp ?>">
    <div class="remove-blogContent text-right">
        <a href="#" class="fas fa fa-trash"></a>
    </div>
     <div class="form-group row">
        <?php if (!empty($content)) : ?>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    
                </div>
                <input id="existing_image<?php echo $timestamp ?>"
                       type="hidden" value="<?php echo $content ?> "
                       name="CmsPage[existingImages][<?php echo $timestamp ?>]"
                       class="form-order-data"
                >
            </div>
        <?php endif; ?>
       
		<label for="description" class="col-sm-2 col-form-label"for="CmsPage_body_<?php echo $timestamp ?>">Image Path</label>
		<div class="col-sm-10">
       <!--   <input id="CmsPage_image_<?php echo $timestamp ?>"
               type="hidden" value=""
               name="CmsPage[images][<?php echo $timestamp ?>]"
        > 
        <input class="form-order-data"
               placeholder="Image"
               name="CmsPage[images][<?php echo $timestamp ?>]"
               id="CmsPage_image_<?php echo $timestamp ?>"
               type="file"
               aria-invalid="false"
            <?= isset($content) ? 'value="' . $content . '"' : '' ?>
        >
 -->
     
<div class="image-manager-input">
	<div class="input-group ">
		<div class="custom-file">
			<input type="file" class="custom-file-input" id="articleform-image<?php echo $timestamp ?>_name"  name="images">
			 <input type="hidden" id="articleform_image<?php echo $timestamp ?>" class="form-control" name="CmsPage[images][<?php echo $timestamp ?>]" value="">
			<label class="custom-file-label" for="exampleInputFile">Choose file</label>
		</div>
		<div class="input-group-append">
			<span class="input-group-text">Upload</span>
		</div>

	</div>
	<div class="image-wrapper hide">
        <img id="articleform-image<?php echo $timestamp ?>_image" alt="images" class="img-responsive img-preview" src="">
      </div>
    </div>
</div>
{{--       <div class="image-manager-input">
          <div class="input-group">
            <input type="text" id="articleform-image<?php echo $timestamp ?>_name" class="form-control" name="images" readonly="">
            <input type="hidden" id="articleform_image<?php echo $timestamp ?>" class="form-control" name="CmsPage[images][<?php echo $timestamp ?>]" value="">
            <a href="#" class="input-group-addon btn btn-primary delete-selected-image hide" data-input-id="articleform_image<?php echo $timestamp ?>" data-show-delete-confirm="false"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></a>
            <a href="#" class="input-group-addon btn btn-primary open-modal-imagemanager" data-aspect-ratio="1.7777777777778" data-crop-view-mode="1" data-input-id="articleform_image<?php echo $timestamp ?>"><i class="fas fa-filter" aria-hidden="true"></i></a>


          </div>
      <div class="image-wrapper hide">
        <img id="articleform-image<?php echo $timestamp ?>_image" alt="images" class="img-responsive img-preview" src="">
      </div>
    </div> --}}
        <div class="help-block"></div>
    </div>
</div>

