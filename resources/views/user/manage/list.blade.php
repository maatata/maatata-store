<div ng-controller="ManageUsersController as manageUsersCtrl">
    <div class="lw-section-heading-block">
  
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __( 'Manage Users' ) ?></h3>
        <!--  /main heading  -->
    </div>
    
    <!--  User Tabs  -->
    <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageUsersTabs">
        <li role="presentation" class="active">
            <a href="#active" aria-controls="active" role="tab" data-toggle="tab" title="<?=  __('Active')  ?>"><?=  __('Active')  ?></a>
        </li>
        <li role="presentation">
            <a href="#deleted" aria-controls="deleted" role="tab" data-toggle="tab" title="<?=  __('Deleted')  ?>"><?=  __('Deleted')  ?></a>
        </li>
        <li role="presentation">
            <a href="#neverActivated" aria-controls="never_activated" role="tab" title="<?=  __('Never Activated')  ?>" data-toggle="tab"><?=  __('Never Activated')  ?></a>
        </li>
    </ul>
    <br>
    <!--  /User Tabs  -->

    <!--  Tab panes  -->
    <div class="tab-content lw-tab-content">

        <!--  Active Users Tab  -->
        <div role="tabpanel" class="tab-pane fade in active" id="active">
            <!--  datatable container  -->
            
                <table class="table table-striped table-bordered" id="activeUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __( 'Name' ) ?></th>
                        <th><?= __( 'Email' ) ?></th>
                        <th><?= __( 'Since' ) ?></th>
                        <th><?= __( 'Last Login' ) ?></th>
                        <th><?= __( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            <!--  /datatable container  -->
        </div>
        <!--  /Active Users Tab  -->
        
        <!--  Deleted Users Tab  -->
        <div role="tabpanel" class="tab-pane fade" id="deleted">
            <!--  datatable container  -->
                <table class="table table-striped table-bordered" id="deletedUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __( 'Name' ) ?></th>
                        <th><?= __( 'Email' ) ?></th>
                        <th><?= __( 'Deleted on' ) ?></th>
                        <th><?= __( 'Last Login' ) ?></th>
                        <th><?= __( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            <!--  /datatable container  -->
        </div>
        <!--  /Deleted Users Tab  -->

        <!--  Never Activated Users Tab  -->
        <div role="tabpanel" class="tab-pane fade" id="neverActivated">
            <!--  datatable container  -->
                <table class="table table-striped table-bordered" id="neverActivatedUsersTabList" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                        <th><?= __( 'Name' ) ?></th>
                        <th><?= __( 'Email' ) ?></th>
                        <th><?= __( 'Since' ) ?></th>
                        <th><?= __( 'Last Login' ) ?></th>
                        <th><?= __( 'Action' ) ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            <!--  /datatable container  -->
        </div>
        <!--  Never Activated Users Tab  -->

    </div>

</div>

<!--  user name column template  -->
<script type="text/template" id="userNameColumnTemplate"> 
     <%= __tData.name %>
</script>
<!--  user name column template  -->

<!--  user action column template  -->
<script type="text/template" id="userActionColumnTemplate"> 
    <% if(__tData.role !== 1) { %>
		
        <% if(__tData.status === 5) { %> <!--  5 (Deleted)  -->
            <a title="<?=  __('Restore')  ?>" class="lw-btn btn btn-default btn-xs" href="" ng-click='manageUsersCtrl.restore("<%- __tData.id %>", "<%- __tData.name %>")'> <?= __('Restore') ?></a>
        <% } else { %>
            <a title="<?=  __('Delete')  ?>" class="lw-btn btn btn-danger btn-xs delete-sw" href="" ng-click='manageUsersCtrl.delete("<%- __tData.id %>","<%- escape(__tData.name) %>")'><i class="fa fa-trash-o fa-lg"></i> <?=  __('Delete')  ?></a>
        <% } %>
		<% if(__tData.status != 4) { %> <!--  4 (Never Activated)  -->
         	<a title="<?=  __('Change Password')  ?>" class="lw-btn btn btn-default btn-xs" href="" ng-click='manageUsersCtrl.changePassword("<%- __tData.id %>","<%- escape(__tData.name) %>")'> <?= __('Change Password') ?></a>
			
			<a title="<?=  __('User Orders')  ?>" class="lw-btn btn btn-default btn-xs" href="" ui-sref="orders.active({userID:<%= __tData.id %>})"> <?= __('User Orders') ?></a>
        <% } %>

        <a title="<?=  __('User Details')  ?>" class="lw-btn btn btn-default btn-xs" href="" ng-click='manageUsersCtrl.getUserDetails(<%- __tData.id %>)'> <?= __('User Details') ?></a>

        <a title="<?=  __('Contact')  ?>" class="lw-btn btn btn-primary btn-xs" href="" ng-click='manageUsersCtrl.contactDialog(<%- __tData.id %>)'> <?= __('Contact') ?></a>

    <% } %>
</script>
<!--  /user action column template ui-sref="orders.active" -->