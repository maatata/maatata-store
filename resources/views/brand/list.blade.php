<div>
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __( 'Shop by Brands' ) ?>
	        	@section('page-title') 
		        	<?= __('Shop by Brands') ?>
		        @endsection
	        </span>
        </h3>
        <!-- /main heading -->
    </div>
    
   <!-- brand logo -->
  	@if(!empty($brands))
  	<div>
  		@foreach($brands as $brand)
  		<ul class="list-group">
                <li class="list-group-item text-center lw-product-box lw-brand-box">
                    <a class="lw-product-thumbnail lw-show-process-action" href="<?=  route('product.related.by.brand', [$brand['id'], str_slug($brand['name'])])  ?>"><img class="lw-brand-thumb" src="<?= e( $brand['logoURL'] ) ?>"/></a>
                </li>
            </ul>
    	@endforeach
    	<!--  get brands keywords for metadata  -->
            @section('keywordName')
            	<?= getKeywords($brands) ?>
            @endsection
        <!--  / get brands keywords for metadata  -->
    </div>
    @else
    	<div class="alert alert-info"><?=  __('No brands available here.')  ?></div>
    @endif
  <!-- /brand logo -->
	
</div>