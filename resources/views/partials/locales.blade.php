<menu class='language_switcher pull-right'>
    <ul>
        <?php foreach (Config::get('app.locales') as $locale => $language): ?>
            <li class='<?= $locale == App::getLocale() ? 'current' : '' ?>'>
                <a href='<?= url(sprintf($changeLocaleUrl, $locale)) ?>'><img src='<?= url('themes/front/images/countries/'.$locale.'.png') ?>' /></a>
            </li>
        <?php endforeach; ?>
    </ul>
</menu>
