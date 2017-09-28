<div ng-controller="ProductOptionEditController as editOptionCtrl" class="lw-dialog">
	<!-- main heading -->
    <div class="lw-section-heading-block">
         <h3 class="lw-header"> <?= __("Edit Option") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="editOptionCtrl.[[ editOptionCtrl.ngFormName ]]" 
        novalidate>

        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="name"
              ng-required="true" 
              autofocus
              ng-model="editOptionCtrl.optionData.name" />
        </lw-form-field>
        <!-- /Name -->
		<div class="lw-dotted-line"></div>           
		<!-- button -->
        <div class="lw-form-actions">
            <button type="submit" ng-click="editOptionCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <button type="submit" ng-click="editOptionCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /button -->

    </form>
	<!-- /form action -->
</div>
