<div>
	@if (!empty($pageDetails))
		
		@if($pageDetails['status'] == 2)
	 	<div class="alert alert-warning" role="alert">
	    	<?=  __("The <strong>:__pageName__</strong> page is inactive & will not display in public until you change status to active",[
	    		':__pageName__' => $pageDetails['title']
	    	])  ?>
	    	<a title="<?= __('Edit Page') ?>" 
	            href="<?=  ngLink('manage.app','page_edit', [], [':pageID' => $pageDetails['id']])  ?>" 
	            class="btn btn-default btn-xs"> 
	            <?=  __("Edit")  ?> 
	            <i class="fa fa-pencil-square-o"></i>
	        </a>
	    </div>
	    @endif	

	    <div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading">@section('page-title', $pageDetails['title']) <?=  $pageDetails['title']  ?></h3>
	        <!-- /main heading -->
	    </div>
	    
	   @section('description', str_limit(strip_tags($pageDetails['description'])), 20) 
	   <div class="lw-image-width">
	   		<?=  $pageDetails['description']  ?>
	   </div>
	@endif	
</div>
