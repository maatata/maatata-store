<div class="col-lg-12 text-center lw-order-success-section">
        <h3>
            <?= __("Thanks for your order") ?>
        </h3>
         @if($payment_status == 'Completed')
		  <i class="fa fa-check-square-o fa-5x lw-success"></i>
          <h5>
              <?= __( 'We will notify you when your order get processed') ?>
          </h5>
        @elseif($payment_status == 'Pending')
            <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
            <h5>
                <?= __( 'We have received your order but unfortunately Payment is not showing as completed, further investigation may required. <br> We will get back to you soon, if needed please feel free to contact us.' ) ?>
            </h5> 
        @endif

        <h4>
            <?= __( 'For the further referance please quote Order ID __orderUID__', [
                '__orderUID__' => '<a href="'. route('my_order.details', $invoice) .'">'.$invoice.'</a>'
        ]) ?>
        </h4>
</div>
