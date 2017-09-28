<div class="lw-dialog">
	<!--  main heading  -->
	<div class="lw-section-heading-block"> 
		@section('page-title') 
			<?=  __( 'Terms & Conditions' )  ?>
		@endsection
	    <h3 class="lw-header"><?=  __( 'Terms & Conditions' )  ?></h3>
    </div>
    <!--  /main heading  -->

	<?= getStoreSettings('term_condition') ?>
</div>
