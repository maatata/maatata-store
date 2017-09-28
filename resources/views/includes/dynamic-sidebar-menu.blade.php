<?php 
	function buildSidebarMenu($data) {

		$menuMarkup = '';

		if (!empty($data)) {

			foreach ($data AS $category) {

				$title  = ucwords($category['name']);
				$catID = $category['id'];

				$categoriesRoute = categoriesProductRoute($catID, $title);
				$path = (Request::url()) == $categoriesRoute ? 'active' : '';

				if (!empty($category['children'])) {
					
					$menuMarkup .= "<li class='".$path."'><input type='checkbox' value='".$catID."' name='selected_categories[]' class='categories-checkbox' /> ".$title."<ul>";
					$menuMarkup .= buildSidebarMenu($category['children']);
					$menuMarkup .= "</li></ul>";

				} else {

					$menuMarkup .= "<li class='".$path."'><input type='checkbox' value='".$catID."' name='selected_categories[]' class='categories-checkbox' /> ".$title."</li>";

				}
			}
		}

		return $menuMarkup;
	}
?>

<?= buildSidebarMenu($menuData['sideBarCategoriesMenuData']) ?>

@push('appScripts')
<script  type="text/javascript">
$(document).ready(function(){
	$(document).on('click', '.apply-selected-categories', function(event){
		var selected_categories = $('.categories-checkbox:checked').map(function(){
            return $(this).val();
        }).get();

		$.ajax({
        	type: "GET",
	      	url: "<?= route('products') ?>",
	      	data: {
	        	categoryID: selected_categories
	      	},
	      	cache: false
	    }).done(function (data) {
	      	$('.lw-sub-component-page').html(data);
	    });
	});
});
</script>
@endpush