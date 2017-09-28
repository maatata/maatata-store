<table cellspacing="0" cellpadding="0" width="600" class="w320" style="border-collapse: collapse !important; font-family: Helvetica, Arial, sans-serif;">
              <tbody>
                <tr style="font-family: Helvetica, Arial, sans-serif;">
                  <td class="header-lg" style="border-collapse: collapse; color: #4d4d4d; font-family: Helvetica, Arial, sans-serif; font-size: 32px; font-weight: 700; line-height: normal; padding: 35px 0 0; text-align: center;">
                    
                    @if(isset($orderData['oldPaymentStatus']) and ($orderData['oldPaymentStatus'] == 4))
                        Payment Confirmed
                    @else
                        @if($mailForAdmin)
                        New Order Received
                        @else
                        Thank you for your order!
                        @endif
                    @endif
                  </td>
                </tr>
                <tr style="font-family: Helvetica, Arial, sans-serif;">
                  <td class="free-text" style="border-collapse: collapse; color: #777777; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 21px; padding: 10px 60px 0px; text-align: center; width: 100% !important;">                    
                    @if($mailForAdmin)
                    You have received new order. You can see order details below or by clicking button.
                    @else
                    We'll notify you once we process your order. You can see order details below or by clicking button.
                    @endif
                  </td>
                </tr>
              </tbody>
            </table>
            @include("emails.order.ordered-products")