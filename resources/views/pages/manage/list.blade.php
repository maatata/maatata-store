<div ng-controller="ManagePagesListController as managePagesListCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><span ng-if="!managePagesListCtrl.parent == true"><?= __( 'Manage Pages' ) ?> </span>
            <span  ng-if="managePagesListCtrl.parent == true"><a ui-sref="pages({parentPageID:''})"><?= __( 'Manage Pages' ) ?> » </a>[[managePagesListCtrl.parentPage.title]] <a title="<?= __('Edit') ?>" class="btn btn-default btn-xs" ui-sref="pages.edit({pageID:managePagesListCtrl.parentPageID})"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a></span></h3>
        <!-- /main heading -->
        <!-- button -->
        <div class="lw-section-right-content">
            <a title="<?=  __('Add New Page')  ?>" ui-sref="pages.add" class="lw-btn btn btn-default btn-sm"><i class="fa fa-plus"></i> <?=  __('Add New Page')  ?></a>
        </div>
        <!-- /button -->
    </div>

    <!-- datatable manage pages -->
    <div class="">
    	<table id="lwPagesTable" class=" table table-bordered" width="100%">
    		<thead class="page-header">
    			<tr>
                    <th><?= __('List Order') ?></th>
    				<th ><?= __('Title') ?></th>
    				<th><?= __('Type') ?></th>
    				<th><?= __('Created') ?></th>
    				<th><?= __('Updated') ?></th>
    				<th><?= __('Show in menu') ?></th>
    				<th><?= __('Status') ?></th>
    				<th><?= __('Action') ?></th>
    			</tr>
    		</thead>
    		<tbody></tbody>
    	</table>
    </div>
    <!-- /datatable manage pages -->

    <div ui-view></div>
    
</div>

<!-- pages list Order template -->
<script type="text/template" id="pagesColumnListOrderTemplate">

   <span class="fa fa-arrows-v"></span>
   
</script>
<!-- /pages list Order template -->

<!-- pages list row add to menu column _template -->
<script type="text/template" id="pagesColumnAddToMenuTemplate">

   <span><%-__tData.add_to_menu %></span>
   
</script>
<!-- /pages list row add to menu column _template -->

<!-- pages list row formated type column _template -->
<script type="text/template" id="pagesColumnTypeTemplate">

   <span><%-__tData.formated_type %></span>
   
</script>
<!-- /pages list row formated type column _template -->

<!-- pages list row formatted created at column _template -->
<script type="text/template" id="pagesColumnTimeTemplate">

   <span><%-__tData.formatted_created_at %></span>
   
</script>
<!-- /pages list row formatted created at column _template -->

<!-- pages list row formatted updated at column _template -->
<script type="text/template" id="pagesColumnUpdatedTimeTemplate">

   <span><%-__tData.formatted_updated_at %></span>
   
</script>
<!-- /pages list row formatted updated at column _template -->

<!-- pages list row active column _template -->
<script type="text/template" id="pagesColumnActiveTemplate">

   <span><%-__tData.active %></span>

</script>
<!-- /pages list row active column _template -->

<!-- pages list row Title column _template -->
<script type="text/template" id="pagesColumnTitleTemplate">

	<% if(__tData.type == 3) { %> 
		<%- __tData.title %>&nbsp&nbsp
	<%} else { %>

		<a ui-sref="pages({parentPageID:'<%- __tData.id %>'})" title="<?= __('Sub-pages') ?>" class="custom-page-title"><%- __tData.title %></a> | 

		<% if(__tData.type == 1) { %> 

	   		<a target="_blank" href="<%- __tData.external_page %>" title="<?= __('External-page') ?>"><i class="fa fa-external-link"></i></a>

		<%} else { %>

	   		<a href="<%- __tData.link.url %>" target="_blank" title="<?= __('External-link') ?>"><i class="fa fa-external-link"></i></a>
	   		
		<% } %>

	<% } %>

</script>
<!-- /pages list row Title column _template -->

<!-- pages list row Action column _template -->
<script type="text/template" id="pagesColumnActionTemplate">

	<% if(__tData.type !== 3) { %> 
	
	   <a title="<?= __('Edit') ?>" class="btn btn-default btn-xs" ui-sref="pages.edit({pageID:<%- __tData.id %>})"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a> 
		<% if(__tData.id !== 1) { %> 
	   		<a title="<?= __('Delete') ?>" class="btn btn-danger btn-xs delete-sw" href="" ng-click="managePagesListCtrl.delete('<%- __tData.id %>', '<%- escape(__tData.title) %>')"><i class="fa fa-trash-o fa-lg"></i> <?= __('Delete') ?></a> 
	   	<% } %>

    <% } %>

</script>
<!-- /pages list row Action column _template -->
