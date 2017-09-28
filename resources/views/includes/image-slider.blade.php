<div class="owl-carousel owl-theme">

	@foreach ($relatedProductData as $relatedProduct)
		<div class="item" >
			<div class="lw-thumbnail">
				<img src="<?=  $product['productAssets'].'/product-'.$relatedProduct['id'].'/'.$relatedProduct['thumbnail']  ?>" class="img-responsive" alt="">
			</div>
			<div class="info">
				<div class="row">
					<div class=" text-center">
					<h5><?=  $relatedProduct['name']  ?></h5>
					<h5 class="price-text-color"><?=  $relatedProduct['related_product_price']  ?></h5>
					</div>
				</div>

				<div class="separator text-center">
					<i class="fa fa-list"></i> 
					<a  href="<?=  route('product.details', [$relatedProduct['id'], $relatedProduct['slugName']])  ?>" title="<?= __('View Details') ?>">
					<?=  __('More Details')  ?>
					</a>
				</div>
			</div>
		</div>
	@endforeach
</div>