<table cellspacing="0" cellpadding="0" width="600" class="free-text" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
  <tbody>
    <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="header-lg" style="border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 700; line-height: normal; padding: 35px 0 0; text-align: center;">
        @if($mailForAdmin)
        <?= ( 'New Order Received but Payment is not completed.' ) ?>
        @else
        <?= ( 'Thank you for your order but Payment is not completed.' ) ?>
        @endif
      </td>
    </tr>
    <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 10px 60px 0px; text-align: center; width: 100% !important;">  
       <br style="font-family: Helvetica, Arial, sans-serif;">                  
        @if($mailForAdmin)
        You have received new order but PayPal payment status is not completed due to following reasons, further investigation or action may required.
        @else
        We have received your order but unfortunately Payment Status at PayPal is not showing as completed, further investigation/action may required. <br> We will get back to you soon, if needed please feel free to contact us.
        @endif
        <br style="font-family: Helvetica, Arial, sans-serif;">
        <br style="font-family: Helvetica, Arial, sans-serif;">
        <br style="font-family: Helvetica, Arial, sans-serif;">
      </td>
    </tr>
@if($mailForAdmin)
    {{-- Payment not Completed --}}
     @if(in_array('ERR_IPN_NOT_COMPLETED', $requestResponse))
     <tr style="font-family: Helvetica, Arial, sans-serif;">
      <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">
            <?= ( 'Payment Status : ') ?> <strong><?= $ipnData['payment_status'] ?></strong><br>
            @if($ipnData['pending_reason'])                            
                <?= ( 'Payment Pending Reason : ' )  ?> <strong><?= $ipnData['pending_reason'] ?></strong>
                 @if($ipnData['pending_reason'] == 'multi_currency')                            
                    <strong>Suggested Action: </strong> You need to accept this currency using your PayPal Account
                @endif
            @endif
      </td>
      </tr>
      @endif
      {{-- Payment not Completed --}}
          {{-- Receiver Email Mismatch --}}
         @if(in_array('ERR_IPN_EMAIL_MISMATCH', $requestResponse))
         <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">
                <?= ( 'Payment Receiver email does not match') ?> <br>
                <?= ( 'Receiver Email Showing : ' )  ?> <strong><?= $ipnData['receiver_email'] ?></strong>
          </td>
          </tr>
          @endif
          {{-- Receiver Email Mismatch --}}
          {{-- Currency Mismatch --}}
         @if(in_array('ERR_IPN_CURRENCY_MISMATCH', $requestResponse))
         <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">
                <?= ( 'Currency does not matched with currency while order has been placed.') ?> <br>
                <?= ( 'Currency Showing : ' )  ?> <strong><?= $ipnData['mc_currency'] ?></strong>
          </td>
          </tr>
          @endif
          {{-- Currency Mismatch --}}
          {{-- Amount Mismatch --}}
         @if(in_array('ERR_IPN_AMOUNT_MISMATCH', $requestResponse))
         <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">
                <?= ( 'Amount does not matched with order amount.') ?> <br>
                <?= ( 'Amount Showing : ' )  ?> <strong><?= $ipnData['mc_gross'] ?> <?= $ipnData['mc_currency'] ?></strong> 
          </td>
          </tr>
          @endif
          {{-- Amount Mismatch --}}
          {{-- TXN Already exists --}}
         @if(in_array('ERR_IPN_TXN_EXIST', $requestResponse))
         <tr style="font-family: Helvetica, Arial, sans-serif;">
          <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">
                <?= ( 'May this TXN already been processed.') ?>
          </td>
          </tr>
          @endif
          {{-- TXN Already exists --}}
        <tr style="font-family: Helvetica, Arial, sans-serif;">
              <td class="free-text" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-collapse: collapse; border-radius: 5px; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 12px 15px 15px; text-align: center;">                    
                <span class="header-sm" style="color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 700; line-height: 1.3; padding: 5px 0;"><?= ( "Additional Information from PayPal Request" ) ?></span><br><br>
                @foreach($ipnData as $ipnDataKey => $ipnDataValue)
                    <strong style="font-family: Helvetica, Arial, sans-serif;"><?= $ipnDataKey ?> : </strong> <span style="font-family: Helvetica, Arial, sans-serif;"><?= $ipnDataValue ?></span> <br style="font-family: Helvetica, Arial, sans-serif;">
                @endforeach
              </td>
        </tr>
         @endif
 </tbody>
</table>
@include("emails.order.ordered-products")