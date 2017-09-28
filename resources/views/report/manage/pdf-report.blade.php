<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style type="text/css">
	body {
		font-family: 'roboto', sans-serif;
	}
	
	@page {
		margin: 0;	
	} 
	
	.lw-pdf-cover-page {
		z-index: 1; 
		background-size: contain;
		page-break-after: always;
		margin: 0;
		padding: 0;
		height: 100%;
	}

	.lw-pdf-title {
		padding-right:30px;
		padding-left: 50px;
		top:40px;
		bottom:0px;
        margin-top:148px;
        font-size:72px;
	}

	.lw-pdf-cover-bottom-text {
		text-align:right;
		padding-right: 20px;
		font-size:20px;
		color:#fff;
		line-height:25px;
		top:990px;
		bottom:0px;
		position: fixed;
	}
	
	.lw-pdf-description {
		position: relative;
		padding:0px 30px 80px 30px;
		font-size:14px;
		line-height:22px;
		display: block;
		top: 50px;
	}

	img {
		max-width: 80%;
		max-height: 80%;
	}

	div.lw-footer {
		position: fixed;
		width: 100%;
		border: 0px solid #888;
		overflow: hidden;
		padding: 0.1cm;
	}
	div.lw-footer {
		padding-top: 35px;
		padding-bottom: 10px;
		bottom: 10px;
		left: 20px;
		right: 20px;
		border-top-width: 1px;
		height: 0.5cm;
	}
	
	
	table, td, th {
	    border: 1px solid black;
    	border-collapse: collapse;
    	width:100%;
    	border-spacing: 15px;
    	padding: 5px;
	}

	.lw-pdf-page-number {
	  text-align: left;
	  font-size: 12px;
	}
	
	.lw-pdf-page-number:before {
	  content: counter(page);
	}

	hr {
	  page-break-after: always;
	  border: 0;
	}

</style>
</head>
<body>

	<!--  store Logo  -->
	<div class="form-group lw-current-logo-conatiner" ng-show="generalSettingsCtrl.editData.logoURL" style="background: #<?=  getStoreSettings('logo_background_color')  ?>">

		<div style="text-align: left;margin-top:10px;">
			<img style="max-height:150px;max-width: 150px;" src="<?=  getStoreSettings('logo_image')  ?>" />
		</div>
	</div>
	<!--  /store Logo  -->
	
	<!--  discription start here  -->
	<div class="lw-pdf-description">

		<!--  store name and address  -->
		<div>
			<strong><?=  getStoreSettings('store_name')  ?></strong><br>
			<?=  getStoreSettings('contact_address')  ?>
		</div>

		<!--  store name and address  -->
		<!--  table for invoce  -->
		<table style="width:100%">
			<thead>
				<tr>
					<!--  main heading  -->
					<th colspan="2"><strong>INVOICE<br><small class="lw-italic-text"> <?=  $orderDetails['currentDateTime']  ?></small></strong></th>
					<!--  /main heading  -->
				</tr>
			</thead>

			<tbody>
				<tr>
					<!--  order ID and placed on  -->
					<td>
						Order ID :<?=  $orderDetails['data']['order']['orderUID']  ?>
					</td>
					<td>
						Order placed on :
						<?=  $orderDetails['data']['order']['formatedOrderPlacedOn']  ?>
					</td>
					<!--  order ID and placed on  -->
				</tr>
				<tr>
					<td colspan="2">
						<!--  order by and name of user  -->
						<strong>Customer Information :</strong><br>
						<span>
							Name:<?=  $orderDetails['data']['user']['fullName']  ?><br>
							Email :<?=  $orderDetails['data']['user']['email']  ?>
						</span>
						<!--  /order by and name of user  -->
					</td>
				</tr>
				<tr>
					<td>
						<strong>
							<!--  shipping and billing address  -->
							Shipping Address
							
						</strong><br>

						<!--  shipping address  -->
						@if(!empty($orderDetails['data']['address']['shippingAddress']))
							<?=  $orderDetails['data']['address']['shippingAddress']['type']  ?><br>
							<?=  $orderDetails['data']['address']['shippingAddress']['addressLine1']  ?><br>
							<?=  $orderDetails['data']['address']['shippingAddress']['addressLine2']  ?><br>
							City :
							<?=  $orderDetails['data']['address']['shippingAddress']['city']  ?><br>
							State :
							<?=  $orderDetails['data']['address']['shippingAddress']['state']  ?><br>
							Country :
							<?=  $orderDetails['data']['address']['shippingAddress']['country']  ?><br>
							Pin Code :
							<?=  $orderDetails['data']['address']['shippingAddress']['pinCode']  ?><br><br>
						@endif
						<!--  /shipping address  -->
					</td>
					<td>
						<!--  billing address  -->
						<strong>Billing Address</strong><br>

						@if($orderDetails['data']['address']['sameAddress'] == true)
							Same as Shipping Address
						@endif

						@if(!empty($orderDetails['data']['address']['billingAddress']) and $orderDetails['data']['address']['sameAddress'] == false)
							<?=  $orderDetails['data']['address']['billingAddress']['type']  ?><br>
							<?=  $orderDetails['data']['address']['billingAddress']['addressLine1']  ?><br>
							<?=  $orderDetails['data']['address']['billingAddress']['addressLine2']  ?><br>
							City :
							<?=  $orderDetails['data']['address']['billingAddress']['city']  ?><br>
							State :
							<?=  $orderDetails['data']['address']['billingAddress']['state']  ?><br>
							Country :
							<?=  $orderDetails['data']['address']['billingAddress']['country']  ?><br>
							Pin Code :
							<?=  $orderDetails['data']['address']['billingAddress']['pinCode']  ?><br><br>
						@endif
						<!--  /billing address  -->
					</td>
					<!--  /shipping and billing address  -->
				</tr>
				<tr>
					<!--  order status and payment method  -->
					<td>
						Order Status : <?=  $orderDetails['data']['order']['formatedOrderStatus']  ?>
					</td>
					<td>
						Payment Method :
						<?=  $orderDetails['data']['order']['formatedPaymentMethod']  ?>
					</td>
					<!--  /order status payment method and payment Status  -->
				</tr>
				<tr>
					<td>
						Payment Status :
						<?=  $orderDetails['data']['order']['formatedPaymentStatus']  ?>
					</td>
					<td>
						Completed On :
						<?=  $orderDetails['data']['order']['paymentCompletedOn']  ?>
					</td>
				</tr>
			</tbody>
		</table>
		<!--  /table for invoce  -->

		<!-- table for products and its option -->
		<table style="width:100%" border="0.1">
			<thead>
				<tr>
					<th>Sr. No.</th>
					<th>Item Description</th>
					<th>Qty</th>
					<th>Subtotal</th>
					<th>Price</th>
				</tr>
			</thead>

			<tbody>
				<?php
					$i = 1;
				?>
				@if(!empty($orderDetails['data']['orderProducts']['products']))
					@foreach($orderDetails['data']['orderProducts']['products'] as $product)
					<tr>
						<td width="70%"><?=  $i  ?></td>
						<td>
						<!--  product name, option and addon price  -->
							<span><?=  $product['productName']  ?></span><br>
							@if(!empty($product['option']))
								@foreach($product['option'] as $option)
								<div>
									<span><?=  $option['optionName']  ?></span><br>
									<span><?=  $option['valueName']  ?></span>
									<span>(<?=  $option['formatedOptionPrice']  ?>)</span>
								</div>
								@endforeach
							@endif
						</td>
						<!--  /product name, option and addon price  -->

						<!--  qty, price and total  -->
						<td style="text-align:center"><?=  $product['quantity']  ?></td>
						<td style="text-align:right"><?=  $product['formatedProductPrice']  ?></td>
						<td style="text-align:right"><?=  $product['formatedTotal']  ?></td>
						<!--  /qty, price and total  -->
					</tr>
					<?php
						$i++;
					?>
					@endforeach
				@endif
					<tr>
						<!--  cart total  -->
						<td colspan="4" style="text-align:center">Cart Total :</td>
						<td style="text-align:right"><strong><?=  $orderDetails['data']['orderProducts']['formatedSubtotal']  ?>
						<?=  $orderDetails['data']['order']['currencyCode']  ?></strong></td>
						<!--  /cart total  -->
					</tr>
			</tbody>
		</table><br>
		<!-- /table for products and its option -->
			
		<!-- table for coupon, shipping and tax -->
		<table style="width:100%" border="0.1">

			@if (!empty($orderDetails['data']['coupon']))  
			<tr>
				<td>
					<!--  dicount amount and detail  -->
					Discount :<br>
					Coupon code : <?=  $orderDetails['data']['coupon']['code']  ?><br>
					Coupon title : <?=  $orderDetails['data']['coupon']['title']  ?><br>
					Coupon description : <?=  $orderDetails['data']['coupon']['description']  ?>
					<!--  /dicount amount and detail  -->
				</td>
				<td style="text-align:right"> - <?=  $orderDetails['data']['order']['formatedOrderDiscount']  ?></td>
			@endif
			@if (!empty($orderDetails['data']['order']))
				<tr>
					<td style="text-align:right">
						<!--  shipping amount and detail  -->
						Shipping :<br>
					</td>
					<td style="text-align:right">
						@if(!__isEmpty( $orderDetails['data']['order']['shippingAmount']))
							+ <?=  $orderDetails['data']['order']['formatedShippingAmount']  ?>
						@else
							<?= 'Free' ?>
						@endif
					</td>
				</tr>
			@endif
			@if (!empty($orderDetails['data']['taxes']))
			<tr>
				<td>
					<!--  tax amount and detail  -->
					Total tax Amount :<br>
					@if(!empty($orderDetails['data']['taxes']))
						@foreach($orderDetails['data']['taxes'] as $tax)
							Label: <?=  $tax['label']  ?><br>
							Notes: <?=  $tax['notes']  ?><br>
							Tax amount: <?=  $tax['formatedTaxAmount']  ?><br><br>
						@endforeach
					@endif
					<!--  /tax amount and detail  -->
				</td>
				<!--  total tax  -->
				<td style="text-align:right">
					@foreach($orderDetails['data']['taxes'] as $tax)
						+ <?=  $tax['formatedTaxAmount']  ?>
					@endforeach
				</td>
				<!--  /total tax  -->
			</tr>
			@endif
		  <tr>
		  	<!--  total payable amount  -->
		    <td style="text-align:right">
		    	<?=  'Total payable amount :'  ?>
		    </td> 
		  	<!--  /total payable amount  -->
			
			<td style="text-align:right"><strong><?=  $orderDetails['data']['order']['formatedTotalOrderAmount']  ?>
		    <?=  $orderDetails['data']['order']['currencyCode']  ?></strong></td>
		  </tr>
		</table>
		<!-- table for coupon, shipping and tax -->

		
		<div class="lw-footer">
		  <div  class="lw-pdf-page-number"> / Page</div>
		</div>
	</div>
	<!--  /discription end here  -->
	
</body>
</html>