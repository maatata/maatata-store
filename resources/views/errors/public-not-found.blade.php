<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
            	<i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
                <h1>
                    <?= __('Ooops...! Not Found') ?></h1> 
                    @section('page-title') 
			        	<?= __('404 Not Found') ?>
			        @endsection
                <h2>
                    <?= __('404') ?></h2>
                <div class="error-details">
                   <?= __(' Sorry, but the item you are looking for does not exist..!') ?>
                </div>
                <div class="error-actions">
                    <a href="<?= route('home.page') ?>" title="<?= __('Continue Shopping') ?>" class="btn btn-primary btn-md"><span class="glyphicon glyphicon-home"></span>
                        <?= __('Continue Shopping') ?> </a>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
	.error-template {padding: 40px 15px;text-align: center;}
	.error-actions {margin-top:15px;margin-bottom:15px;}
	.error-actions .btn { margin-right:10px; }
</style>