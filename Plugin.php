<?php namespace Waka\Wakajob;

use Backend;
use Backend\Classes\Controller;
use Cms\Classes\ComponentBase;
use Event;
use Flash;
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
use Waka\Wakajob\FormWidgets\KnobWidget;

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
        return [
            'wakajob' => [
                'label'       => 'waka.wakajob::lang.labels.wakajob',
                'url'         => Backend::url('waka/wakajob/jobs'),
                'icon'        => 'icon-gears',
                'iconSvg'     => 'plugins/waka/wakajob/assets/img/gear.svg',
                'order'       => 500,
                'permissions' => ['waka.wakajob.*'],
                'sideMenu'    => [
                    'jobs' => [
                        'label'       => 'waka.wakajob::lang.labels.jobs',
                        'icon'        => 'icon-gears',
                        'url'         => Backend::url('waka/wakajob/jobs'),
                        'permissions' => ['waka.wakajob.access_jobs'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerSettings(): array
    {
        return [
            'messaging' => [
                'label'       => 'waka.wakajob::lang.settings.messaging-label',
                'description' => 'waka.wakajob::lang.settings.messaging-description',
                'category'    => 'Wakajob',
                'icon'        => 'icon-globe',
                'class'       => Models\Settings::class,
                'permissions' => ['waka.wakajob.access_settings'],
                'order'       => 500,
                'keywords'    => 'messages flash notifications',
            ],
        ];
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
        //$translator = $this->app->make('translator');

        // $this->app->when(Classes\TranslApiController::class)
        //     ->needs(Translator::class)
        //     ->give(
        //         function () use ($translator) {
        //             return $translator;
        //         }
        //     );

        // $this->app->make('events')->listen(
        //     'cms.page.initComponents',
        //     function ($controller) {
        //         foreach ($controller->vars as $variable) {
        //             if ($variable instanceof ComponentBase) {
        //                 $this->app->make('wakajob.dependencyInjector')->injectDependencies($variable);
        //             }
        //         }
        //     }
        // );

        // $aliasLoader = AliasLoader::getInstance();
        // $aliasLoader->alias('Resolver', Facades\Resolver::class);

        // $injector = $this->app->make('wakajob.backend.injector');
        // $injector->addCss('/plugins/waka/wakajob/assets/css/animate.css');

        // Event::listen(
        //     'backend.list.extendColumns',
        //     function ($widget) {
        //         foreach ($widget->config->columns as $name => $config) {
        //             if (empty($config['type']) || $config['type'] !== 'listtoggle') {
        //                 continue;
        //             }
        //             // Store field config here, before that unofficial fields was removed
        //             ListToggle::storeFieldConfig($name, $config);
        //             $column = [
        //                 'clickable' => false,
        //                 'type'      => 'listtoggle',
        //             ];
        //             if (isset($config['label'])) {
        //                 $column['label'] = $config['label'];
        //             }
        //             // Set this column not clickable
        //             // if other column with same field name exists configs are merged
        //             $widget->addColumns(
        //                 [
        //                     $name => $column,
        //                 ]
        //             );
        //         }
        //     }
        // );
        /**
         * Switch a boolean value of a model field
         * @return void
         */
        Controller::extend(
            function ($controller) {
                $controller->addDynamicMethod(
                    'index_onSwitchInetisListField',
                    function () use ($controller) {
                        $field = post('field');
                        $id = post('id');
                        $modelClass = post('model');
                        if (empty($field) || empty($id) || empty($modelClass)) {
                            Flash::error('Following parameters are required : id, field, model');

                            return null;
                        }
                        $model = new $modelClass;
                        $item = $model::find($id);
                        $item->{$field} = !$item->{$field};
                        $item->save();

                        return $controller->listRefresh($controller->primaryDefinition);
                    }
                );
            }
        );
    }
}
