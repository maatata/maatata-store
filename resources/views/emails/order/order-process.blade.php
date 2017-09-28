@if(!empty($description))
	<div>
		<strong><?=  ('Additional  Note')  ?> :</strong>
		<?=  $description  ?>
	</div>
@endif

<p><?=  'Your order cancellation for order '  ?> <?=  $orders['_uid']  ?> <?=  'has been rejected. Now the order status is processing..'  ?></p>
