@if ((isCurrentRoute('order.summary.view') == false 
	or getStoreSettings('hide_sidebar_on_order_page') == 0))
<div>
@else 
<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 ">
@endif
@if(!empty($breadCrumb))
 <ol class="breadcrumb">
	 @if($breadCrumb['title'] != 'Home')
		<li><a href="<?=  asset('/')  ?>" title="<?=  __('Home')  ?>"><?=  __('Home')  ?></a></li>
		@if(!empty($breadCrumb['parents']))
			@foreach($breadCrumb['parents'] as $parent)
				
				<li>
					@if(isset($parent['target']) and !__isEmpty($parent['target']))

						<a href="<?=  $parent['url']  ?>" target="<?=  $parent['target']  ?>" title="<?=  $parent['name']  ?>"><?=  $parent['name']  ?></a>
					@else

						<a href="<?=  $parent['url']  ?>" title="<?=  $parent['name']  ?>"><?=  $parent['name']  ?></a>

					@endif
				</li>
			@endforeach
		@endif
	@endif
	<li class="active" title="<?=  $breadCrumb['title']  ?>"><?=  $breadCrumb['title']  ?></li>
</ol> 
@endif
</div>