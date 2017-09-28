 	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" property="og:description" content="@yield('description')">
    <meta name="keywordDescription" property="og:keywordDescription" content="@yield('keywordDescription')">
    <meta name="keywordName" property="og:keywordName" content="@yield('keywordName')">
    <meta name="keyword" content="@yield('keyword')">
    <meta name="title" content="@yield('page-title')">
    <meta name="store" content="<?= __("Maatata") ?>">
    <style>
 	.lw-page-content, .hide-till-load, .lw-main-loader{
 		display: none;
 	}

    .lw-zero-opacity {
        -webkit-opacity: 0;
        -moz-opacity:0
        -o-opacity:0
        opacity:0: 0;
    }

    .lw-hidden {
        display: none;
    }

    .lw-show-till-loading {
        display: block;
    }
    .loader:before,
    .loader:after,
    .loader {
      border-radius: 50%;
      width: 2.5em;
      height: 2.5em;
      -webkit-animation-fill-mode: both;
      animation-fill-mode: both;
      -webkit-animation: load7 1.8s infinite ease-in-out;
      animation: load7 1.8s infinite ease-in-out;
    }
    .loader {
      color: #f6827f;
      font-size: 10px;
      margin: 80px auto;
      position: relative;
      text-indent: -9999em;
      -webkit-transform: translateZ(0);
      -ms-transform: translateZ(0);
      transform: translateZ(0);
      -webkit-animation-delay: -0.16s;
      animation-delay: -0.16s;
    }
    .loader:before {
      left: -3.5em;
      -webkit-animation-delay: -0.32s;
      animation-delay: -0.32s;
    }
    .loader:after {
      left: 3.5em;
    }
    .loader:before,
    .loader:after {
      content: '';
      position: absolute;
      top: 0;
    }
    @-webkit-keyframes load7 {
      0%,
      80%,
      100% {
        box-shadow: 0 2.5em 0 -1.3em;
      }
      40% {
        box-shadow: 0 2.5em 0 0;
      }
    }
    @keyframes load7 {
      0%,
      80%,
      100% {
        box-shadow: 0 2.5em 0 -1.3em;
      }
      40% {
        box-shadow: 0 2.5em 0 0;
      }
    }

    div.lw-store-header, div.lw-current-logo-conatiner {
      background-color: #<?= getStoreSettings('logo_background_color') ?>;
    }
</style>	
<link href="<?= __yesset('dist/css/vendor-first*.css') ?>" rel="stylesheet">
<link href="<?= __yesset('dist/css/vendor-second*.css') ?>" rel="stylesheet">
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">