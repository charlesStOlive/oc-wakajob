<?php namespace Waka\Wakajob;

use Backend;
use Backend\Classes\Controller;
use Cms\Classes\ComponentBase;
use Event;
use Flash;
use Lang;
use Illuminate\Foundation\AliasLoader;
use Waka\Wakajob\Classes\BackendInjector;
use Waka\Wakajob\Classes\DependencyInjector;
use Waka\Wakajob\Classes\RouteResolver;
use Waka\Wakajob\Console\Optimize;
use Waka\Wakajob\FormWidgets\ListToggle;
use Waka\Wakajob\Console\QueueClearCommand;
use Waka\Wakajob\Classes\LaravelQueueClearServiceProvider;
use System\Classes\PluginBase;
use Waka\LaravelWakajob\LaravelWakajobServiceProvider;
use October\Rain\Translation\Translator;
//use Waka\Wakajob\FormWidgets\KnobWidget;
use \Waka\Utils\Models\Settings as UtilsSettings;

/**
 * Wakajob Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Wakajob',
            'description' => 'waka.wakajob::lang.labels.pluginName',
            'author'      => 'Waka',
            'icon'        => 'icon-cogs',
        ];
    }

    /**
     * @return array
     */
    public function registerComponents(): array
    {
        return [
            //Components\Messaging::class => 'wakajobFlashMessages',
        ];
    }

    /**
     * @return array
     */
    public function registerPermissions(): array
    {
        return [
            'waka.wakajob.access_settings' => [
                'tab'   => 'waka.wakajob::lang.permissions.tab',
                'label' => 'waka.wakajob::lang.permissions.access_settings',
            ],
            'waka.wakajob.access_jobs'     => [
                'tab'   => 'waka.wakajob::lang.permissions.tab',
                'label' => 'waka.wakajob::lang.permissions.access_jobs',
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerNavigation(): array
    {
        $showNotification = true;

        if (!UtilsSettings::get('activate_task_btn')) {
            return [];
        }

        return [
            'notification' => [
                'label' => Lang::get("waka.utils::lang.menu.job_list_s"),
                'url' => Backend::url('waka/wakajob/jobs'),
                'icon' => 'icon-refresh',
                'order' => 500,
                'counter' => 0,
                'permissions' => ['waka.jobList.*'],
                'counterLabel' => Lang::get('waka.utils::lang.joblist.btn_counter_label'),
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerSettings(): array
    {
        return [];
        // return [
        //     'messaging' => [
        //         'label'       => 'waka.wakajob::lang.settings.messaging-label',
        //         'description' => 'waka.wakajob::lang.settings.messaging-description',
        //         'category'    => 'Wakajob',
        //         'icon'        => 'icon-globe',
        //         'class'       => Models\Settings::class,
        //         'permissions' => ['waka.wakajob.access_settings'],
        //         'order'       => 500,
        //         'keywords'    => 'messages flash notifications',
        //     ],
        // ];
    }

    /**
     * Plugin register method
     */
    public function register(): void
    {
        $this->app->register(LaravelQueueClearServiceProvider::class);
        $this->commands(
            [
                Optimize::class,
                QueueClearCommand::class,
            ]
        );

        // $this->app->register(LaravelWakajobServiceProvider::class);

        // $this->app->singleton(
        //     'wakajob.route.resolver',
        //     function () {
        //         return new RouteResolver($this->app['config'], $this->app['log']);
        //     }
        // );

        // $this->app->singleton(
        //     'wakajob.backend.injector',
        //     function () {
        //         return new BackendInjector();
        //     }
        // );

        // $this->app->singleton(
        //     'wakajob.dependencyInjector',
        //     function () {
        //         return new DependencyInjector($this->app);
        //     }
        // );
    }

    /**
     * @return array
     */
    public function registerListColumnTypes(): array
    {
        return [
            'listtoggle' => [ListToggle::class, 'render'],
        ];
    }

    /**
     * @return array
     */
    // public function registerFormWidgets()
    // {
    //     return [
    //         KnobWidget::class => [
    //             'label' => 'waka.wakajob::lang.labels.knobFormWidget',
    //             'code' => 'knob'
    //         ]
    //     ];
    // }

    /**
     * Plugin boot method
     * @throws \ApplicationException
     */
    public function boot(): void
    {
        /**
         * POur le bouton des jobs
         */
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            $controller->addCss('/plugins/waka/utils/assets/css/notification.css');
            $user = \BackendAuth::getUser();
            if ($user->hasAccess('waka.jobList.*') && UtilsSettings::get('activate_task_btn')) {
                // $pluginUrl = url('/plugins/waka/wakajob');
                // \Block::append('body', '<script type="text/javascript" src="' . $pluginUrl . '/assets/js/backendnotifications.js"></script>');
            }
        });
    }
}
