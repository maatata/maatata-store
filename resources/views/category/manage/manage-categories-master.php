<div class="lw-sub-component-page" ng-app="categoryApp" ng-controller="CategoryController as categoryCtrl">
 	@include('includes.form-template')
    <div class="lw-component-content" ui-view></div>
</div>
<script src="<?= __yesset('dist/js/category-app*.min.js') ?>"></script>