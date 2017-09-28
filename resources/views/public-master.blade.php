<!DOCTYPE html>
<html lang="<?php echo substr(CURRENT_LOCALE, 0, 2); ?>" class="lw-has-disabled-block">
<head>
	<title>
		<?= e( getStoreSettings('store_name') ) ?> : @yield('page-title')
	</title>
   	@include('includes.head-content') 
    <link href="<?= __yesset('dist/css/vendor-public*.css') ?>" rel="stylesheet">
    <link href="<?= __yesset('dist/css/application*.min.css') ?>" rel="stylesheet"> 
</head>

<body ng-app="PublicApp" ng-controller="PublicController as publicCtrl">
    {{-- Disabled loading block --}}
    <div class="lw-disabling-block">
        <div class="lw-processing-window lw-hidden">
            <div class="loader"><?=  __('Loading...')  ?></div>
            <div><?= __( 'Please wait while we are processing your request...' ) ?></div>
        </div>
    </div>
    {{-- /Disabled loading block --}}
    <noscript>
        <style>
        	.nojs-msg { 
        		width: 50%; 
        		margin:20px auto;
        	}
        </style>
        <div class="custom-noscript">
            <div class="bs-callout bs-callout-danger nojs-msg">
            <h4><?= __('Oh dear... we are sorry') ?></h4>
            <em><strong><?= __('Javascript') ?></strong> <?= __('is disabled in your browser, To use this application please enable javascript &amp; reload page again.') ?></em>
            </div>
        </div>
    </noscript>

    <div class="lw-small-screen-search-container visible-xs">
        @include('includes.search-panel')
    </div>

	<div class="lw-sidebar-overlay" data-toggle="offcanvas"></div>

	<!-- Static navbar -->
	@include('includes.top-menu')
	
<div>

@if(isDemoForAdmin())
<div class="alert alert-warning lw-row">
  <center><strong><?= __('Please Note: ') ?></strong><?= __('Demonstration purposes only. No Order will be executed.') ?></center>
</div>
@endif

<!-- container -->
<div class="container-fluid lw-page-main-container">
  	<div class="row row-offcanvas row-offcanvas-left hide-till-load lw-row">

  		@if ((isCurrentRoute('order.summary.view') == false 
  			or getStoreSettings('hide_sidebar_on_order_page') == 0)
  		and (isCurrentRoute('display.page.details') == false or isset($pageDetails) and $pageDetails['hideSidebar'] == 1)
  			)

			<div class="col-xs-8 col-sm-4 col-md-3 col-lg-3 sidebar-offcanvas ">
		        <div class="lw-sidebar-menu">        
		       		@include('includes.sidebar')
		        </div>
			</div>

			<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 lw-main-component-page-container">

		@else 

	        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lw-main-component-page-container">	

		@endif

            <div class="lw-main-component-page"> 

            	
				@if(session('success'))
					<!--  success message when email sent  -->
					<div class="alert alert-success">
						<?= session('message') ?>
					</div>
					<!--  /success message when email sent  -->
				@endif 
			
				@if (session('error'))
			        <!--  error message when user not successfully activated  -->
			        <div class="alert alert-danger alert-dismissible">
			            <span>
			                <?=  session('message')  ?>
			            </span>
			        </div>
			        <!--  /error message when user not successfully activated  -->
			    @endif

            	<!-- animated slideInRight -->
                <div class="lw-sub-component-page" id="elementtoScrollToID">
					@if(isset($pageRequested))
						<?php echo $pageRequested ; ?>
					@endif
                </div>
            </div>
        </div>
    	<!-- back-top-link-block -->
    	<div class="hidden lw-top-link-block">
		    <a href="#top" class="btn btn-default btn-xs" title="<?=  __('Go to top')  ?>" onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
		        <i class="fa fa-arrow-up"></i> <?=  __('Go to top')  ?>
		    </a>
		</div>
		<!-- /back-top-link-block -->
  	</div>
    <div class="lw-main-loader lw-show-till-loading">
        <div class="loader"><?=  __('Loading...')  ?></div>
    </div>
</div>

@push("vendorScripts")
    <script src="<?= __yesset('dist/js/vendor-public*.js') ?>"></script>
@endpush

@push("appScripts")
    <script src="<?= __yesset('dist/js/public-app*.min.js') ?>"></script> 
@endpush

@include('includes.foot-content')
</body>
</html>