<div ng-controller="ProductsFilterController as productsFilterCtrl">
	<!--  container  -->
	<div class="container">
		<!--  main heading  -->
	  	<div class="lw-section-heading-block">
	        <h3 class="lw-header"><?=  __( 'Filter' )  ?></h3>
	    </div>
	  	<!--  /main heading  -->

	  	<!--  tab heading contain price & brand  -->
		<ul class="nav nav-tabs">
			<!--  brand tab  -->
		    <li 
		    	ng-class="{ 'active' : productsFilterCtrl.pageType != 'brand' || productsFilterCtrl.brandExistStatus == true }" 
		    	ng-if="productsFilterCtrl.pageType != 'brand'" 
		    	ng-hide="!productsFilterCtrl.brandExistStatus">
	          	<a href="#brand" role="tab" data-toggle="tab">
	              	<?=  __('Brand')  ?>
	              	<span class="label label-warning" 
	              		  ng-if="productsFilterCtrl.selectedBrandID.length > 0" 
	              		  ng-bind="productsFilterCtrl.selectedBrandID.length"> 
	              	</span>
	          	</a>
	      	</li>
	      	<!-- / brand tab  -->
 
	      	<!--  price tab  -->
		    <li ng-class="{ 'active' : productsFilterCtrl.pageType == 'brand' || productsFilterCtrl.brandExistStatus == false}">
		    	<a href="#price" role="tab" data-toggle="tab"><?=  __('Price')  ?></a>
	      	</li>
	      	<!-- / price tab  -->

		</ul><br><br>
		<!--  /tab heading contain price & brand   -->

		<div>
			<!--  form action  -->
			<form action="[[productsFilterCtrl.currentUrl]]" novalidate  name="filter"  method="get">

			  	<div class="tab-content">

			  		<input type="hidden" ng-if="productsFilterCtrl.searchTerm" name="search_term" value="[[productsFilterCtrl.searchTerm]]">

					<!--  To show the brand section  -->
				    <div class="tab-pane fade " id="brand" ng-class="{ 'active in' : productsFilterCtrl.pageType != 'brand' || productsFilterCtrl.brandExistStatus == true }">

					    <div ng-if="productsFilterCtrl.brandPageType == false">

					    	<!--  Brand select all label  -->
					  		<label ng-hide="productsFilterCtrl.brandsData.length == ''"><input type="checkbox" ng-model="productsFilterCtrl.all_brands_selected" ng-change="productsFilterCtrl.selectAll()"><?=  __(' Select All')  ?></label>
							<!-- / Brand select all label  -->

						    <!--  Brand select all label  -->
							<ul class="list-group lw-select-filters">

								<li class="list-group-item" ng-repeat="brandData in productsFilterCtrl.brandsData track by $index">
									<!--  To show brand name  -->
									<label for="brandname-[[brandData.brandID]]" class="lw-label-font-normal">
                                        <!--  generate checkbox on row  -->
                                        <input id="brandname-[[brandData.brandID]]" type="checkbox" name="sbid[]" 
                                        value="[[brandData.brandID]]" 
                                        class="lw-checkbox-select" 
                                        ng-model="productsFilterCtrl.brandsData[$index]['exist']" 
                                        ng-click="productsFilterCtrl.select()" 
                                        ng-checked="productsFilterCtrl.selectedIDs[$index]['exist']">
                                        <!--  / generate checkbox on row  -->
										<span class="lw-filter-select-text" ng-bind="brandData.brandName"></span>
									</label>
									<!-- / To show brand name  -->

									<!--  To show product count related to brand  -->
									<span class="label label-warning pull-right" ng-bind="brandData.product_count"></span>
									<!-- / To show ....... brand  -->

								</li>

							</ul>
							<!-- / Brand select all label  -->
					  	</div>

				    </div>
				    <!-- / To show the brand section   -->

					<!--  To show the price filter slider  -->
				    <div class="tab-pane  fade" id="price" 
				    	 ng-class="{ 'active in' : productsFilterCtrl.pageType == 'brand' || productsFilterCtrl.brandExistStatus == false}">

				      	<!--  To append price slider  -->
						<div class="" ng-show="productsFilterCtrl.priceStatus == true">
							<div class="lw-price-slider">
								<div id="lwPriceSlider"></div>
							</div>
						</div>
						<!--  / To append price slider  -->

						<!--  To show the warning msg when the price not availble for filter  -->
						<div ng-show="productsFilterCtrl.priceStatus == false">
							<div class="text-center alert alert-warning"><?=  __("No price Filter.")  ?></div>
						</div>
						<!--  / To show ...... filter  -->
				    </div>
				    <!--  / To show the price filter slider  -->

					<div class="lw-dotted-line"></div>

				  	<!--  To show apply & clear filter & close button  -->
				  	<div class="lw-form-actions">

						<!--  set hidden min & max price  -->
						<input type="hidden"  class="lw-min-price" value="">
						<input type="hidden" class="lw-max-price"  value="">
						<!-- / set hidden min & max price  -->

						<!--  apply filter btn  -->
						<button ng-show="productsFilterCtrl.priceStatus" class="lw-btn btn btn-warning" title="<?=  __('Apply')  ?>" type="submit">
							<?=  __("Apply")  ?>
						</button>

						<button ng-show="!productsFilterCtrl.priceStatus" ng-disabled="true" class="lw-btn btn btn-warning" title="<?=  __('Apply')  ?>">
							<?=  __("Apply")  ?>
						</button>
						<!--  / apply filter btn  -->

						<!--  Clear filter btn  -->

						<a ng-show="!productsFilterCtrl.searchTerm" ng-click="productsFilterCtrl.clearFilter()" class="btn btn-default" title="<?=  __('Clear Filter')  ?>" href="[[productsFilterCtrl.currentUrl]]">
							<?=  __("Clear Filter")  ?>
						</a>

						<a ng-show="productsFilterCtrl.searchTerm" ng-click="productsFilterCtrl.clearFilter()" class="btn btn-default" title="<?=  __('Clear Filter')  ?>" href="[[productsFilterCtrl.currentUrl]]?search_term=[[productsFilterCtrl.searchTerm]]"><?=  __("Clear Filter")  ?></a>

						<!--  /Clear filter btn  -->

						<!--  Close filter dialog  -->
						<a ng-click="productsFilterCtrl.clearFilter()" class="btn lw-clearfilter-btn btn-default" title="<?=  __('Close')  ?>">
							<?=  __("Close")  ?>
						</a>
						<!--  /Close filter dialog  -->

					</div>
					<!-- / To show apply & clear filter & close button  -->
				</div>

			</form>
			<!--  form action  -->
		</div>
	</div>
	<!--  /container  -->
</div>

