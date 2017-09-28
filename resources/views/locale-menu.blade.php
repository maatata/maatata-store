@if(config('locale.show_locale_menu') and getStoreSettings('show_language_menu'))
	<a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
	        <span><?php $availableLocale = config('locale.available');  echo array_key_exists(CURRENT_LOCALE, $availableLocale) ? $availableLocale[CURRENT_LOCALE] : $availableLocale[ env("LC_DEFAULT_LANG", "en_US") ]; ?></span> 
    </a>
    <ul>
        @foreach($availableLocale as $locale => $localeName)
        <?php if($locale == CURRENT_LOCALE) continue; ?>
      <li>
            <a href="<?= route('locale.change', [$locale]) .'?redirectTo='.base64_encode(Request::fullUrl());  ?>" class="lw-show-process-action lw-locale-change-action" title="<?= $localeName ?>"><?=  $localeName  ?></a>
        </li>

        @endforeach
    </ul>
@endif