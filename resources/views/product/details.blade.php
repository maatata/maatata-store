<div ng-controller="ProductDetailsController as productDetailsCtrl">

	@if(!empty($product))
		<div class="lw-section-heading-block">
	        <!--   main heading  -->
	        <h3 class="lw-section-heading"><?=  $product['name']  ?>  
            <!--  show edit button for admin  -->
            @if(isAdmin())
                <a title="<?= __('Edit') ?>" 
                    href="<?=  ngLink('manage.app','product_edit', [], [ ':productID' => $product['id']])  ?>" 
                    class="btn btn-default btn-xs"> 
                    <?=  __("Edit")  ?> 
                    <i class="fa fa-pencil-square-o"></i>
                </a>
            @endif
            </h3>
            <!--  /show ... admin  -->
	        @section('page-title') 
	        	<?= $product['name'] ?>
	        @endsection
	        <!--  /main heading  -->
	    </div>

	    <!--  This msg display only to admin, when product is invalid  -->
	    @if(isAdmin())
			
			@if(__isEmpty($isActiveCategory))
			    <div class="alert alert-warning" role="alert">
			    	<?=  __("As this product's of <strong>category</strong> is inactive this product is not publicly viewable")  ?>
			    </div>	
		    @endif

		    @if($product['status'] == 2)
			    <div class="alert alert-warning" role="alert">
			    	<?=  __("This product is inactive & will not display in public until you change status to active")  ?>
			    </div>	
		    @endif

		    @if($product['isBrandInValid'] == true)
		    
			    <div class="alert alert-warning" role="alert">

			    	<?=  __("As this product's __brandName__ brand is inactive .This product is not publicly viewable.", ['__brandName__' => $product['brand']['name'] ])  ?> <a title="<?= __('Edit Brand') ?>" 
	                    href="<?=  ngLink('manage.app','brand_edit', [], [':brandID' => $product['brand']['id']])  ?>" 
	                    class="btn btn-default btn-xs"> 
	                    <?=  __("Edit")  ?> 
	                    <i class="fa fa-pencil-square-o"></i>
	                </a>
			    </div>	
		    @endif

		    <div ng-if="productDetailsCtrl.productDetails.out_of_stock == 1" class="alert alert-warning" role="alert">
		    	<?=  __("Out of Stock") ?>
		    </div>	

	    @endif
	    <!--  /This msg display only to admin, when product is invalid  -->

		<div class="lw-image-width">
		    <div class="row">	
			<div class="item-container">	
				<div class="container-fluid">						
					<!--  product images slider  -->
					<div  class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
						<!-- Place somewhere in the <body> of your page -->
						@if (!empty($images))
						    <?php $isThumbnailImage = true; ?>
							<div class="lw-main-product-slider">
								@foreach ($images as $imageKey => $image)
								  	<div style="background: url('<?=  getProductImageURL($image['products_id'], $image['file_name'])  ?>') center no-repeat;  display: inline-block; <?php if($isThumbnailImage === true) { echo 'background-size: initial!important;'; $isThumbnailImage = false; }  ?>" class="item"></div>
								@endforeach
							</div>
						@endif
					</div>
					<!--  / product images slider  -->

					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<!--  To display product information like price & categories  -->
							<div><?= __('ID') ?> : <?= $product['product_id'] ?></div>
								
							<!--  product categories section  -->
							<div>
								@if (!empty($categories))
									<?= __('Available in these categories') ?> :
									@foreach ($categories as $category)
									 	<a title="<?= $category['name'] ?>" href="<?= e( $category['categoryUrl'] ) ?>">
								 			<?= $category['name'] ?> 
										 	@if (end($categories) !== $category),
										 	@endif
									 	</a>
									@endforeach 

	                                <!--  get categories keywords for metadata  -->
		                                @section('keywordDescription')
		                                	<?= getKeywords($categories) ?>
		                                @endsection
	                                <!--  / get categories keywords for metadata  -->

								@endif
							</div>
							<!--  /product categories section  -->
                            
                            <!-- Brand Information -->
                            
                            @if (!__isEmpty($product['brand']))
                            <div class="lw-brand-logo">
                                <a title="<?= __('View __brandName__ Brand Products', ['__brandName__' => $product['brand']['name']]) ?>" href="<?=  route('product.related.by.brand', [$product['brand']['id'], str_slug($product['brand']['name'])])  ?>" class=""><img class="lw-thumb-logo lw-brand-thumb" src="<?= $product['brand']['logoImageURL'] ?>" alt=""></a>
                            </div> 
                            @endif
                           
                            <!-- Brand Information -->

						<!--  / To display product information like price & categories  -->
						<hr>

						<!--  Include add to cart functionality form  -->
						<div ng-show="productDetailsCtrl.pageStatus">
							<!--  Form for add to cart section -->
							<form 
								class="lw-form lw-ng-form" 
								name="productDetailsCtrl.[[ productDetailsCtrl.ngFormName ]]" 
								novalidate>
								
								<!--   Show the product options -->
							    <div ng-repeat="option in productDetailsCtrl.productDetails.option track by $index" ng-switch="option.optionValueExist">
							        <span  ng-switch-when="true"><lw-form-field field-for="options[option.name]" label="[[option.optionName]] : ">

										<select 
							                ng-init="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name] = option.option_values[0]"  

							                ng-model="productDetailsCtrl.productData.options[productDetailsCtrl.productDetails.id][option.name]" 

							                class="form-control" 

							                name="options[option.name]" 

							                ng-options='(value.addon_price != 0 ? (value.name+" (+"+value.addon_price_format+")") : value.name) for value in option.option_values'
											
							                ng-change="productDetailsCtrl.updateCartRow(productDetailsCtrl.productDetails.id, true)"

							            ></select>
									</lw-form-field>
									</span>
							    </div>
							    <!--  / show the product options -->

							    <!--  price details table  -->
							    <div ng-switch="productDetailsCtrl.optionLength" >
							    	<table  ng-switch-when="true" class='table table-bordered table-striped table-hover table-rounded'>
							            <tbody>
							                <tr>
							                    <td><strong><?=  __('Base Price')  ?> </strong></td>
							                    <td class="text-right">
							                       <span ng-bind="productDetailsCtrl.productDetails.priceDetails.base_price"></span>
							                        <!--  To show old price  -->
								                	<span ng-if="productDetailsCtrl.productDetails.old_price">
								                		<small><strike class="lw-price">[[productDetailsCtrl.productDetails.oldPrice]]   <?= getStoreSettings('currency') ?> </strike> </small>
								                	</span>
								                	<!--  To show old price   -->
								                </td>
							                </tr>
							                <tr ng-repeat="productOption in productDetailsCtrl.productDetails.priceDetails.option">
							                    <td>[[ productOption.optionName ]] <em ng-bind="productOption.name"></em> </td>
							                    <td class="text-right">
							                    	<sapn ng-show="productOption.addon_price != 0">+ [[productOption.addon_price_format]]</sapn>
							                    	<sapn ng-show="productOption.addon_price == 0"> - </sapn>
							                    </td>
							                </tr>
							            </tbody>
							        </table>
							    </div>
							    
							    <!--  / price details table  -->

							    <!--  Price  -->
							    <div><h3 class="lw-effective-price">
							        <small title="<?= __( 'Price based on options selections' ) ?>"><?= __( 'Price :' ) ?></small>
							        <span ng-bind="productDetailsCtrl.productDetails.basePriceWithAddonPrice"></span> 
							        
							        <?= getStoreSettings('currency') ?>

							        <!--  To show old price  -->
				                	<span ng-if="productDetailsCtrl.productDetails.old_price">
				                		<small ng-if="productDetailsCtrl.optionLength == false"><strike class="lw-price">[[productDetailsCtrl.productDetails.oldPrice]]   <?= getStoreSettings('currency') ?> </strike> </small>
				                	</span>
				                	<!--  To show old price   -->
							    </h3></div>
							    <!--  /Price  -->


							    <!--  show quantity field if product available else show out of stock   -->
							    <div ng-switch="productDetailsCtrl.productDetails.out_of_stock">
									
									<!--  Quantity show when the product is in stock -->
									<div ng-switch-when="0">

							            <lw-form-field field-for="quantity" label="<?= __( 'Quantity' ) ?>">
							                
							                <div class="input-group">
							                   <!--  decrement of qunatity btn  -->
							                    <span class="input-group-btn ">
							                        <button title="<?=  __('Decrement')  ?>" type="button" class="btn btn-default btn-number lw-vxs-hidden" ng-click="productDetailsCtrl.getQtyAction(false, productDetailsCtrl.productData.quantity)">
							                            <i class="glyphicon glyphicon-minus"></i>
							                        </button>
							                    </span>
							                    <!-- / decrement of qunatity btn  -->

							                    <input style="text-align:center" type="number" 
							                      class="lw-form-field form-control"
							                      name="quantity"
							                      ng-required="true"
							                      min="1" 
							                      max="99999"
							                      ng-model="productDetailsCtrl.productData.quantity" />
							                    
							                    <!--  show add & Update cart btn  -->
							                    <span class="input-group-btn" ng-switch="productDetailsCtrl.productData.isCartExist">

							                        <!--  increment of qunatity btn  -->
							                        <button title="<?=  __('Increment')  ?>" type="button" ng-click="productDetailsCtrl.getQtyAction(true, productDetailsCtrl.productData.quantity)" class="btn btn-default btn-number lw-vxs-hidden">
							                          <i class="glyphicon glyphicon-plus"></i>
							                        </button>
							                        <!-- / increment of qunatity btn  -->
							                        <!--  Add cart btn  -->
							                        <button 
							                            ng-switch-when="false" 
							                            class="btn btn-primary lw-btn-process" 
							                            title="<?=  __('Add to Cart')  ?>" 
							                            type="submit" ng-click="productDetailsCtrl.addToCart()">
							                            <i class="fa fa-cart-plus"></i> <?=  __("Add to Cart")  ?>
							                        </button>
							                        <!--  / Add cart btn  -->

							                        <!--  Update cart btn  -->
							                        <button ng-switch-when="true"  
							                            title="<?= __('Update Cart') ?>" 
							                            type="submit" 
							                            class="btn btn-primary lw-btn-process" 
							                            ng-click="productDetailsCtrl.addToCart()"> 
							                            <i class="fa fa-cart-plus"></i> <?=  __("Update Cart")  ?>
							                            <span></span>
							                        </button>
							                        <!--  Update cart btn   -->
							                    </span>                                    
							                    <!--  /show add & Update cart btn  -->
							                </div>
							                
							            </lw-form-field>
									</div>
							        <!--  /Quantity show when the product is in stock  -->

							      	<!--  show out of stock alert msg  -->
							      	@if(!isAdmin())
							        <div ng-switch-when="1" class="alert alert-warning">
							            <?=  __('Out of Stock')  ?>
							        </div>
							        @endif
									<!--  / show out of stock alert box  -->
							        
							    </div>
							    <!-- / show ... else show out of stock   -->
							</form>
							<!--  Form for add to cart section -->
						</div>
						<!--  /Include add to cart functionality form  -->
					</div>
				</div> 
			</div><br>

			<!--  description, youtube_video_code,  related products, recent view products  -->
			<div class="col-lg-12">
                <!--  description  -->
                <div class="panel panel-default">

                  <div class="panel-heading"><strong><?=  __('Product Description')  ?></strong></div>

                   <div class="panel-body">
                        <?=  $product['description']  ?> 
                   </div>

                   	<!--  set description for metadata  -->
                    	@section('description', str_limit(strip_tags($product['description'])), 20)
                    <!--  set description for metadata  -->

                </div>
				<!--  / description  -->

				<!--  specification  -->
				@if (!empty($specifications))
                <div class="panel panel-default table-responsive">
                  <div class="panel-heading"><strong><?=  __('Product Specifications')  ?></strong></div>
					<table class="table table-bordered">
                            <tbody>
                                @foreach($specifications as $specification)
                                  <tr>
                                        <td><?= $specification['name'] ?></td>
                                        <td><?= $specification['value'] ?></td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
				@endif
				<!--  /specification  -->

                <!--  youtubeVideoCode  -->
                @if (!empty($youtubeVideoCode))   
                    <div class="panel panel-default">
                  	<div class="panel-heading"><strong><?=  __('Releated Video')  ?></strong></div>
	                   	<div class="panel-body">     
		                   <div class="col-sm-12 col-md-9 col-lg-6">            
				                <div class="embed-responsive embed-responsive-16by9">
				                    <iframe class="embed-responsive-item" allowfullscreen src="<?= e( getYoutubeUrl($youtubeVideoCode) ) ?>"></iframe>
				                </div>
		                	</div>
	                	</div>
                	</div>
                @endif
                <!--  / youtubeVideoCode  -->

				<!--  related products image slider -->
				@if (!empty($relatedProductData))
					<div>
						<h4><?=  __('Related Products')  ?></h4>
					</div>
					<hr>
					<div class="lw-inline-products-owl">
					  	@foreach ($relatedProductData as $relatedProduct)
					  		<div class="text-center">
						  		<a href="<?=  route('product.details', [$relatedProduct['id'], $relatedProduct['slugName']])  ?>">
						  			<div style="background: url('<?=  getProductImageURL($relatedProduct['id'], $relatedProduct['thumbnail'])  ?>') center no-repeat;  display: inline-block;" class="item">
						  			</div>
						  		</a>
					  			<div>
									<span><a  href="<?=  route('product.details', [$relatedProduct['id'], $relatedProduct['slugName']])  ?>" title="<?= __('View Details') ?>"><?=  $relatedProduct['name']  ?></a></span><br>
									<span class="price-text-color"><?=  $relatedProduct['related_product_price']  ?></span>
								</div>
								<!-- / show product details btn  -->
					  		</div>
					  	@endforeach
					</div>
				@endif
				<!--  /related products image slider  -->
		   		
			</div>
			<!--  / description, youtube_video_code,  related products, recent view products  -->
		</div>
	</div>  
	@endif
	@push('appScripts')
		<script type="text/javascript">
    		$(document).ready(function() {

    		    //this script for related products & recent view products
    			$(".lw-inline-products-owl").owlCarousel({
    			    autoPlay          : false,
    			    navigation        : true,
    			    touchDrag         : true,
                    pagination        : false,
    			    mouseDrag         : true,
    			});

    			//this script add for main product image slider
    	 		$(".lw-main-product-slider").owlCarousel({
    			    navigation        : true, 
    			    slideSpeed        : 300,
                    autoPlay          : false,
    			    paginationSpeed   : 400,
    			    singleItem	      : true,
    			    touchDrag         : true,
    				mouseDrag         : true
    			});
    		});
		</script>
	@endpush
</div>