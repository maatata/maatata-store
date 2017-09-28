<div ng-controller="ManageUserDetailsDialog as manageUserDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'User Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
   	<div class="panel panel-default">
	  <!-- Default panel contents -->
	  <div class="panel-heading"><strong ng-bind="manageUserDetailCtrl.userDetails.fullName"></strong></div>
		<div class="table-responsive">
		  	<!--  Table  -->
			<table class="table table-bordered" border="1">
				<!--  user email -->
				<tr>
					<th>
						<?= __('Email') ?>
					</th>
					<td>
						<!-- email Id -->
			    		<span ng-if="manageUserDetailCtrl.userDetails.email != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.email"></span>
			    		</span>
		    			<span ng-if="manageUserDetailCtrl.userDetails.email == ''">
		    				<?= __('NA') ?>
		    			</span>
		    			<!-- /email Id  -->
					</td>
				</tr>
				<!--  /user email -->
				<!--  last login  -->
				<tr>
					<th>
						<?= __('Last login') ?>
					</th>
					<td>
						<!-- last login by user -->
			    		<span ng-if="manageUserDetailCtrl.userDetails.lastLogin != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.lastLogin"></span>
			    		</span>
		    			<span ng-if="manageUserDetailCtrl.userDetails.lastLogin == ''">
		    				<?= __('NA') ?>
		    			</span>
		    			<!-- /last login by user -->
					</td>
				</tr>
				<!--  /last login  -->
				<!--  last IP  -->
				<tr>
					<th>
						<?= __('Last logged in IP') ?>
					</th>
					<td>
						<!-- last ip of user -->
			    		<span ng-if="manageUserDetailCtrl.userDetails.lastIp != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.lastIp"></span>
			    		</span>
		    			<span ng-if="manageUserDetailCtrl.userDetails.lastIp == ''">
		    				<?= __('NA') ?>
		    			</span>
		    			<!-- /last ip of user -->
					</td>
				</tr>
				<!--  /last IP  -->
				<!--  last login  -->
				<tr>
					<th>
						<?= __('Created On') ?>
					</th>
					<td>
						<!-- user created on -->
			    		<span ng-if="manageUserDetailCtrl.userDetails.creationDate != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.creationDate"></span>
			    		</span>
		    			<span ng-if="manageUserDetailCtrl.userDetails.creationDate == ''">
		    				<?= __('NA') ?>
		    			</span>
		    			<!-- /user created on -->
					</td>
				</tr>
				<!--  /last login  -->
				<!--  user email id  -->
		    	<tr>
		    		<th>
		    			<?= __('Last Order Placed On') ?>
		    		</th>
		    		<td>
		    			<!-- Last order placed on -->
			    		<span ng-if="manageUserDetailCtrl.userDetails.lastOrder != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.lastOrder"></span>
			    		</span>
		    			<span ng-if="manageUserDetailCtrl.userDetails.lastOrder == ''">
		    				<?= __('NA') ?>
		    			</span>
		    			<!-- /Last order placed on  -->
		    		</td>
		    	</tr>
		    	<tr>
		    		<th><?=  __('Last Order ID')  ?></th>
		    		<td>
		    			<!-- Last Order UID  --> 
		    			<span ng-if="!manageUserDetailCtrl.userDetails.lastOrderUID">
			    			<?=  __('NA')  ?>
			    		</span>
			    		<span ng-if="manageUserDetailCtrl.userDetails.lastOrderUID != ''">
			    			<span ng-bind="manageUserDetailCtrl.userDetails.lastOrderUID"></span>
			    		</span>
			    		<!-- /Last Order UID  --> 
		    		</td>
		    	</tr>
		    	<tr>
		    		<!-- Total orders count  --> 
		    		<th><?=  __('Total Orders')  ?></th>
		    		<td ng-bind="manageUserDetailCtrl.userDetails.totalOrder"></td>
		    		<!-- /Total orders count  --> 
		    	</tr>
		  </table>
	  </div>
		<!--  /Table  -->  
	</div>

	<div class="lw-dotted-line"></div>
	<!--  close button  -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="manageUserDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!--  /close button  -->

</div>