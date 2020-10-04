<?php

namespace BristolSU\Support\Http\View;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Settings\SettingRepository;
use BristolSU\Support\User\Contracts\UserAuthentication;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class InjectSiteSettings
{

    /**
     * @var SettingRepository
     */
    private SettingRepository $settingRepository;
    /**
     * @var Repository
     */
    private Repository $config;

    public function __construct(SettingRepository $settingRepository, Repository $config)
    {
        $this->settingRepository = $settingRepository;
        $this->config = $config;
    }

    public function compose(View $view)
    {
        JavaScriptFacade::put([
          'site_settings' => $this->settingRepository->all(),
          'app_url' => $this->config->get('support.url'),
          'api_url' => $this->config->get('support.api_url')
        ]);
    }


}
