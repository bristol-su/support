<?php

namespace BristolSU\Support\Theme;

use BristolSU\Support\Settings\Concerns\RegistersSettings;
use BristolSU\Support\Theme\Settings\AppearanceCategory;
use BristolSU\Support\Theme\Settings\ChosenTheme;
use BristolSU\Support\Theme\Settings\ThemeGroup;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\View\Compilers\BladeCompiler;
use Twigger\Blade\Foundation\AssetStore;
use Twigger\Blade\Foundation\ThemeLoader;
use Twigger\Blade\Foundation\ThemeStore;
use Twigger\Blade\Themes\Bootstrap\BootstrapThemeServiceProvider;
use Twigger\Blade\ThemeServiceProvider as BladeThemeServiceProvider;

class ThemeServiceProvider extends BladeThemeServiceProvider
{
    use RegistersSettings;

    public function register()
    {
        parent::register();
        $this->app->register(BootstrapThemeServiceProvider::class);
    }

    public function boot()
    {
        $this->app['config']->set('themes.theme', null);
        $this->app['config']->set('themes.tag-prefix', 'portal');

        $this->registerSettings()
            ->category(new AppearanceCategory())
            ->group(new ThemeGroup())
            ->registerSetting(new ChosenTheme(app(ThemeStore::class)));

        parent::boot();

        try {
            $theme = ChosenTheme::getValue();
            app(ThemeLoader::class)->load($theme);
        }
        catch (QueryException $e) {
            // This will occur if the database hasn't yet been migrated
        }

    }

}
