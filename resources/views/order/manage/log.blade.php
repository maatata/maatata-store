<div ng-controller="ManageOrderLogController as orderLogCtrl">

    <!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Order Log For' )  ?> [[ orderLogCtrl.order._id ]]</h3>
    </div>
    <!--  /main heading  -->
	
	<!--  /Order log Detail  -->   
    <div>
        <ul class="list-group">
		  <li class="list-group-item" ng-repeat="log in orderLogCtrl.orderLog">
				<div class="lw-order-description">
                    [[ log.created_at ]]<br>
                </div>[[ log.description ]]
		  </li>
		</ul>
    </div> 
	<!--  /Order log Detail  -->   
		<div class="lw-dotted-line"></div>
	<!--  action button  -->
    <div class="form-group lw-form-actions">
            <button type="button" ng-click="orderLogCtrl.close()" class="lw-btn btn btn-default pull-right" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
	<!--  /action button  -->
</div>