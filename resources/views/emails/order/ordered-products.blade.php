<table cellspacing="0" cellpadding="0" width="600" class="free-text" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
<tbody>
        <tr style="font-family: Helvetica, Arial, sans-serif;">
              <td class="button" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 30px 0; text-align: center;">
                <div style="font-family: Helvetica, Arial, sans-serif;">
                <a href="<?= $orderDetailsUrl ?>" style="-webkit-text-size-adjust: none; background-color: #ff6f6f; border-radius: 5px; color: #ffffff; display: inline-block; font-family: 'Cabin', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: regular; line-height: 45px; mso-hide: all; text-align: center; text-decoration: none !important; width: 155px;"><?= ( 'Order Details' ) ?></a></div>
              </td>
            </tr>
            <tr style="font-family: Helvetica, Arial, sans-serif;">
                <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: left; width: 253px;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;">
                    @if($orderData['address']['sameAddress'] != false ) <?= ( 'Shipping/Billing Address' ) ?>
                    @else 
                        <?= ( 'Shipping Address' ) ?>
                    @endif
                    </span>
                    <br style="font-family: Helvetica, Arial, sans-serif;">
                    <strong style="font-family: Helvetica, Arial, sans-serif;"><?= $orderData['fullName'] ?> </strong>
                    <?php $type = $orderData['address']['shippingAddress']['type']; ?>
                    <br style="font-family: Helvetica, Arial, sans-serif;"> 
                    <address style="font-family: Helvetica, Arial, sans-serif;">
                      <strong><?= e( $type ) ?></strong><br>
                      <?= e( $orderData['address']['shippingAddress']['addressLine1'] ) ?>
                      <br style="font-family: Helvetica, Arial, sans-serif;"> 
                      <?= e( $orderData['address']['shippingAddress']['addressLine2'] ) ?>
                      <br style="font-family: Helvetica, Arial, sans-serif;"> 
                      <?= e( $orderData['address']['shippingAddress']['city'].', '.$orderData['address']['shippingAddress']['state'].', '.$orderData['address']['shippingAddress']['country'] ) ?><br>
                      <?= e( $orderData['address']['shippingAddress']['pinCode'] ) ?>
                    </address>
                    <br style="font-family: Helvetica, Arial, sans-serif;"> 
                    @if($orderData['address']['sameAddress'] == false ) 
                         <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;">
                            <?= ( 'Billing Address' ) ?>
                        </span>
                        <br style="font-family: Helvetica, Arial, sans-serif;"> <?php $type = $orderData['address']['billingAddress']['type']; ?>
                        <br style="font-family: Helvetica, Arial, sans-serif;"> 
                        <address style="font-family: Helvetica, Arial, sans-serif;">
                          <strong><?= e( $type ) ?></strong><br>
                          <?= e( $orderData['address']['billingAddress']['addressLine1'] ) ?>
                          <br style="font-family: Helvetica, Arial, sans-serif;"> 
                          <?= e( $orderData['address']['billingAddress']['addressLine2'] ) ?>
                          <br style="font-family: Helvetica, Arial, sans-serif;"> 
                          <?= e( $orderData['address']['billingAddress']['city'].', '.$orderData['address']['billingAddress']['state'].', '.$orderData['address']['billingAddress']['country'] ) ?><br>
                          <?= e( $orderData['address']['billingAddress']['pinCode'] ) ?>
                        </address>
                    @endif
                  </td>
                </tr>
                <tr style="font-family: Helvetica, Arial, sans-serif;">
                  <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: left; width: 253px;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Placed on' ) ?></span><br style="font-family: Helvetica, Arial, sans-serif;"> <?=  $orderData['orderPlacedOn']  ?>
                    <br style="font-family: Helvetica, Arial, sans-serif;">
                    <br style="font-family: Helvetica, Arial, sans-serif;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Order ID' ) ?></span>
                    <br style="font-family: Helvetica, Arial, sans-serif;"> <a href="<?= $orderDetailsUrl ?>"><?= $orderData['orderUID']  ?></a>
                    <br style="font-family: Helvetica, Arial, sans-serif;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Payment Status' ) ?></span>
                    <br style="font-family: Helvetica, Arial, sans-serif;"> <?= $orderData['formatedPaymentStatus']  ?>
                    <br style="font-family: Helvetica, Arial, sans-serif;">
                    <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( 'Payment Method' ) ?></span>
                    <br style="font-family: Helvetica, Arial, sans-serif;"> <?= $orderData['formatedPaymentMethod']  ?>
                  </td>
                </tr>
              </tbody>
        </table>
         <br style="font-family: Helvetica, Arial, sans-serif;">
          <br style="font-family: Helvetica, Arial, sans-serif;">
        <table cellspacing="0" cellpadding="0" width="600" class="free-text" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
        <tbody>
      <tr style="font-family: Helvetica, Arial, sans-serif;">
        <td align="center" valign="top" width="100%" style="background-color: #ffffff; border-bottom: 1px solid #e5e5e5; border-collapse: collapse; border-top: 1px solid #e5e5e5; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; text-align: center;">
          <center style="font-family: Helvetica, Arial, sans-serif;">
            <table cellpadding="0" cellspacing="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif; text-align:center; margin:15px 0 0px 0; ">
              <tbody>
              <tr>
                  <td><span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0; "> <br> <?= ( 'Ordered Items' ) ?></span></td>
              </tr>
             </tbody>
            </table>            
            <table cellpadding="0" cellspacing="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif; ">
              <tbody>
                <tr style="font-family: Helvetica, Arial, sans-serif;">
                  <td class="item-table" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 50px 20px; text-align: center; width: 560px;">
                    <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
                      <tbody>
                        <tr style="font-family: Helvetica, Arial, sans-serif;">
                          <td class="title-dark" width="300" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: left;">
                            <?= ('Item Description') ?>
                          </td>
                          <td class="title-dark" width="163" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: center;">
                            <?= ('Qty') ?>
                          </td>
                          <td class="title-dark" width="97" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: right;">
                            <?= ('Price') ?>
                          </td>
                          <td class="title-dark" width="97" style="border-bottom: 1px solid #cccccc; border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 700; line-height: 21px; padding-bottom: 5px; text-align: right;">
                            <?= ('Total') ?>
                          </td>
                        </tr>
                        @foreach($orderData['orderProducts']['products'] as $items) 
                        <tr style="font-family: Helvetica, Arial, sans-serif;">
                          <td class="item-col item" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top; width: 300px;">
                            <table cellspacing="0" cellpadding="0" width="100%" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
                              <tbody>
                                <tr style="font-family: Helvetica, Arial, sans-serif;">
                                  <td class="mobile-hide-img" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; text-align: left; width: 125px;">
                                    <a href="<?=  $items['detailsURL'] ?>"><img width="110" src="<?= $items['imagePath'] ?>" alt="<?= $items['productName'] ?>" style="-ms-interpolation-mode: bicubic; border: 1px solid #e6e6e6; border-radius: 4px; font-family: Helvetica, Arial, sans-serif; max-width: 600px; outline: none; text-decoration: none;"> </a>
                                  </td>
                                  <td class="product" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; text-align: left; vertical-align: top; width: 175px;">
                                    <span style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-weight: bold;"><?= e($items['productName']) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                                    @if(!empty($items['option']))
                                    @foreach($items['option'] as $option) 
                                        <span><strong><?= e($option['optionName']) ?> : </strong><?= e($option['valueName']) ?><br></span>
                                    @endforeach
                                    @endif
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                          <td class="item-col quantity" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: center; vertical-align: top;">
                            <?= e($items['quantity']) ?>
                          </td>
                          <td class="item-col" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: right; vertical-align: top;">
                            <?= e($items['formatedProductPrice']) ?>
                          </td>
                          <td class="item-col" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: right; vertical-align: top;">
                            <?= e($items['formatedTotal']) ?>
                          </td>
                        </tr>
                        @endforeach
                        <tr style="font-family: Helvetica, Arial, sans-serif;">
                          <td class="item-col item mobile-row-padding" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top; width: 300px;"></td>
                          <td class="item-col quantity" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                          <td class="item-col price" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                          <td class="item-col price" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: left; vertical-align: top;"></td>
                        </tr>


                        <tr style="font-family: Helvetica, Arial, sans-serif;">
                          <td colspan="3" class="item-col quantity" style="border-collapse: collapse; border-top: 1px solid #cccccc; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-right: 10px; padding-top: 20px; text-align: right; vertical-align: top;">
                            <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"><?= ( 'Subtotal' ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            @if(!empty($orderData['orderDiscount']))
                                <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"><?= ( 'Discount' ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            @endif
                            @if(!empty($orderData['taxes']))
                                @foreach($orderData['taxes'] as $tax)
                                    <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">
                                    	@if(!empty($tax['label']))
                                    		<?= e( $tax['label'] ) ?>
                                    	@else
                                    		<?=  ('N/A')  ?>
                                    	@endif	
                                    </span><br style="font-family: Helvetica, Arial, sans-serif;">
                                @endforeach
                            @endif
                            <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;">
                            	<?=  ('Shipping')  ?>
                            	</span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            <span class="total-space" style="color: #4d4d4d; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-weight: bold; padding-bottom: 8px;"><?=  ('Total Payable Amount')  ?></span>
                          </td>
                          <td class="item-col price" style="border-collapse: collapse; border-top: 1px solid #cccccc; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding-top: 20px; text-align: right; vertical-align: top;">
                            <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px;"><?= e( $orderData['orderProducts']['formatedSubtotal'] ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            @if(!empty($orderData['orderDiscount']))
                                <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px; text-align:right;">- <?= e( $orderData['formatedOrderDiscount'] ) ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            @endif
								
                            @if(!empty($orderData['taxes']))
                                @foreach($orderData['taxes'] as $tax)

                                   	<span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px; text-align:right;">
                                   		+ <?= e( $tax['formatedTaxAmount'] ) ?></span> 
                                   	<br style="font-family: Helvetica, Arial, sans-serif;">
                                @endforeach
                            @endif
                            <span class="total-space" style="display: inline-block; font-family: Helvetica, Arial, sans-serif; padding-bottom: 8px; text-align:right;">
                            	@if (!empty($orderData['shippingAmount']))
                                   + <?=  $orderData['formatedShippingAmount']  ?>
                                @else
                                  <?=  ('Free') ?>
                                @endif
                            </span> <br style="font-family: Helvetica, Arial, sans-serif;">
                            <span class="total-space" style="color: #4d4d4d; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-weight: bold; padding-bottom: 8px;"><?= e($orderData['formatedTotalOrderAmount']) ?> <?= e( $orderData['currencyCode'] ) ?></span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>

              </tbody>
            </table>
            </center>
             </td>
                </tr>
                </tbody>
        </table>