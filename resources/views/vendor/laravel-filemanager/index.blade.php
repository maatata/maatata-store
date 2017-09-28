<!DOCTYPE html>
<html lang="<?php echo substr(CURRENT_LOCALE, 0, 2); ?>" class="lw-has-disabled-block">
<head>
<title>
    <?= e( getStoreSettings('store_name') ) ?> : <?= __('Manage Store') ?> -  <?= __( 'File Manager' )   ?>
</title>
@include('includes.head-content')
  <link rel="stylesheet" href="<?= url('dist/css/jquery-ui.min.css') ?>">
  <link rel="stylesheet" href="<?= url('dist/css/cropper.min.css') ?>">
<link rel="stylesheet" href="<?= url('vendor-public/laravel-filemanager/css/lfm.css') ?>">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><?= __( 'File Manager' )   ?></a>
    </div>
    <div id="navbar"  class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right" id="nav-buttons">
            <li class="yes-up-folder-btn-container">
              <a href="#!" id="to-previous">
                <i class="fa fa-arrow-left"></i> {{ Lang::get('laravel-filemanager::lfm.nav-back') }}
              </a>
            </li>
            <li>
              <a href="#!" id="add-folder">
                <i class="fa fa-plus"></i> {{ Lang::get('laravel-filemanager::lfm.nav-new') }}
              </a>
            </li>
            <li>
              <a href="#!" id="upload" data-toggle="modal" data-target="#uploadModal">
                <i class="fa fa-upload"></i> {{ Lang::get('laravel-filemanager::lfm.nav-upload') }}
              </a>
            </li>
            <li>
              <a href="#!" id="thumbnail-display">
                <i class="fa fa-picture-o"></i> {{ Lang::get('laravel-filemanager::lfm.nav-thumbnails') }}
              </a>
            </li>
            <li>
              <a href="#!" id="list-display">
                <i class="fa fa-list"></i> {{ Lang::get('laravel-filemanager::lfm.nav-list') }}
              </a>
            </li>
          </ul>
        </div>
  </div>
</nav>
<div class="container-fluid">    
        @if ($errors->any())
        <div class="row">
        <div class="alert alert-danger" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            </div>
        @endif
        <div class="row">
        <div class="col-sm-3 col-md-2 col-xs-4 sidebar yes-sidebar">
          <div id="tree1" class=""></div>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-xs-offset-4 col-md-10 col-md-offset-2 main yes-fill">
             <div id="content" class="yes-content yes-fill">
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">{{ Lang::get('laravel-filemanager::lfm.title-upload') }}</h4>
        </div>
        <div class="modal-body">
          <form action="{{ route('yesteamtech.lfm.upload') }}" role='form' id='uploadForm' name='uploadForm' method='post' enctype='multipart/form-data'>
            <div class="form-group" id="attachment">
              <label for='upload' class='control-label'>{{ Lang::get('laravel-filemanager::lfm.message-choose') }}</label>
              <div class="controls">
                <div class="input-group" style="width: 100%">
                  <input type="file" id="upload" name="upload">
                </div>
              </div>
            </div>
            <input type='hidden' name='working_dir' id='working_dir' value='{{$working_dir}}'>
            <input type='hidden' name='show_list' id='show_list' value='0'>
            <input type='hidden' name='type' id='type' value='{{$file_type}}'>
            <input type='hidden' name='_token' value='{{csrf_token()}}'>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('laravel-filemanager::lfm.btn-close') }}</button>
          <button type="button" class="btn btn-primary" id="upload-btn">{{ Lang::get('laravel-filemanager::lfm.btn-upload') }}</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="fileViewModal" tabindex="-1" role="dialog" aria-labelledby="fileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="fileLabel">{{ Lang::get('laravel-filemanager::lfm.title-view') }}</h4>
        </div>
        <div class="modal-body" id="fileview_body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ Lang::get('laravel-filemanager::lfm.btn-close') }}</button>
        </div>
      </div>
    </div>
  </div>  
    <script src="<?= __yesset('dist/js/vendor-first*.js') ?>"></script> 
    <script src="<?= __yesset('dist/js/vendor-file-manager*.js') ?>"></script>
    @include('laravel-filemanager::script');
</body>
</html>
