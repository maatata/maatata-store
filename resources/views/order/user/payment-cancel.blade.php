<div class="col-lg-12 text-center lw-order-success-section">
@if($orderUid)
        <h3>
            <?= __("Failed order Submission") ?>
        </h3>        
            <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
        <h5>
            <?= __( 'Order has been cancelled due to Payment Failed', [
                    '__orderLink__' => '<a href="'.route('my_order.details', $orderUid).'">'.__('order details page').'</a>'
            ] ) ?>
        </h5>
@else
</h3>        
    <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
<h5>
<div class="alert">
    <strong><?= __( 'Invalid Request!! ' ) ?></strong>
</div>
@endif
</div>