<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'title' => 'ETU',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'logo' => '<b>ETU</b>',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'ETU',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'admin',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */

    'menu' => [

        [
            'text' => 'Главная',
            'url'  => 'home',
            'icon' => 'fas fa-fw fa-home',
        ],
        [
            'text' => 'Контакты',
            'icon' => 'fas fa-fw fa-phone',
            'url'  => '/admin/contacts',
        ],
        [
            'text' => 'Новости',
            'icon' => 'fas fa-fw fa-info',
            'url'  => '/admin/news',
        ],
        [
            'text' => 'Руководители',
            'icon' => 'fas fa-fw fa-user',
            'url'  => '/admin/supervisor',
        ],
        [
            'text' => 'Партнеры',
            'icon' => 'fas fa-fw fa-users',
            'url'  => '/admin/partner',
        ],
        [
            'text' => 'Специальности',
            'icon' => 'fas fa-fw fa-book',
            'url'  => '/admin/specialty',
        ],
        [
            'text' => 'Вакансий',
            'icon' => 'fas fa-fw fa-address-book',
            'url'  => '/admin/vacancy',
        ],
        [
            'text' => 'Вопросы Блог ректора',
            'icon' => 'fas fa-fw fa-question',
            'url'  => '/admin/rectorsBlogQuestion',
        ],
        [
            'text' => 'Член ученого совета',
            'icon' => 'fas fa-fw fa-flask',
            'url'  => '/admin/academicCouncilMember',
        ],
        [
            'text' => 'Скидки',
            'icon' => 'fas fa-fw fa-table',
            'url'  => '/admin/discount',
        ],
        [
            'text' => 'Стоимости',
            'icon' => 'fas fa-fw fa-credit-card',
            'url'  => '/admin/cost',
        ],
        [
            'text' => 'Общежитие',
            'icon' => 'fas fa-fw fa-bed',
            'url'  => '/admin/dormitory',
        ],
        [
            'text' => 'Студенческие клубы',
            'icon' => 'fas fa-fw fa-users',
            'url'  => '/admin/studentClub',
        ],
        [
            'text' => 'Школы Бакалавриат',
            'icon' => 'fas fa-fw fa-graduation-cap',
            'url'  => '/admin/bachelorSchool',
        ],
        [
            'text' => 'Специальности Магистратуры',
            'icon' => 'fas fa-fw fa-graduation-cap',
            'url'  => '/admin/mastersSpecialty',
        ],
        [
            'text'    => 'Навигационное меню',
            'icon'    => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'Категорий навигационного меню',
                    'icon' => 'fas fa-fw fa-bars',
                    'url'  => '/admin/navbar',
                ],
                [
                    'text'    => 'Пункты навигационного меню',
                    'icon'    => 'fas fa-fw fa-share',
                    'submenu' => [
                        [
                            'text' => 'Об университете',
                            'icon' => 'fas fa-fw fa-university',
                            'url'  => '/admin/university',
                        ],
                        [
                            'text' => 'Поступление',
                            'icon' => 'fas fa-fw fa-graduation-cap',
                            'url'  => '/admin/enrollment',
                        ],
                        [
                            'text' => 'Студентам',
                            'icon' => 'fas fa-fw fa-users',
                            'url'  => '/admin/students',
                        ],
                        [
                            'text' => 'Школы',
                            'icon' => 'fas fa-fw fa-book',
                            'url'  => '/admin/schools',
                        ],
                        [
                            'text' => 'Наука',
                            'icon' => 'fas fa-fw fa-flask',
                            'url'  => '/admin/science',
                        ],
                    ]
                ]
            ]
        ],
        [
            'text'    => 'Страницы',
            'icon'    => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'Главная страница',
                    'icon' => 'fas fa-fw fa-file',
                    'url'  => '/admin/mainPage',
                ],
                ['text'    => 'Об Университете',
                'icon'    => 'fas fa-fw fa-share',
                'submenu' => [
                    [
                        'text' => 'О нас',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/aboutUs',
                    ],
                    [
                        'text' => 'Аккредитация',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/accreditation',
                    ],
                    [
                        'text' => 'Партнеры',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/partnersPage',
                    ],
                    [
                        'text' => 'Руководство',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/authority',
                    ],
                    [
                        'text' => 'Карьера',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/careerPage',
                    ],
                    [
                        'text' => 'Блог ректора',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/rectorsBlogPage',
                    ],
                    [
                        'text' => 'Наука',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/scienceAboutPage',
                    ],
                    [
                        'text' => 'Ученый совет',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/academicCouncilPage',
                    ],
                    [
                        'text' => 'Инфраструктура',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/infrastructure',
                    ]
                ]
                ],
                ['text'    => 'Наука',
                'icon'    => 'fas fa-fw fa-share',
                'submenu' => [
                    [
                        'text' => 'Наука и инноваций',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/scienceInnovationPage',
                    ],
                    [
                        'text' => 'Студенческая наука',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/studentScience',
                    ],
                    [
                        'text' => 'Научные издания',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/scientificPublicationPage',
                    ],
                    [
                        'text' => 'Летний лагерь',
                        'icon' => 'fas fa-fw fa-home',
                        'url'  => '/admin/summerSchoolPage',
                    ]
                ]
                ],
                ['text'    => 'Поступление',
                'icon'    => 'fas fa-fw fa-share',
                'submenu' => [
                    [
                        'text' => 'Приемная комиссия',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/admissionsCommitteePage',
                    ],
                    [
                        'text' => 'Бакалавриат',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/bachelorSchool',
                    ],
                    [
                        'text' => 'Магистратура',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/masterPage',
                    ],
                    [
                        'text' => 'Языковые курсы',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/languageCoursesPage',
                    ],
                    [
                        'text' => 'Major + Minor',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/majorMinorPage',
                    ],
                    [
                        'text' => 'Level Up',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/levelUpPage',
                    ],
                    [
                        'text' => 'Олимпиада',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/olympicsPage',
                    ],
                    [
                        'text' => 'Университет Линкольн',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/lincolnUniversityPage',
                    ],
                    [
                        'text' => 'Иностранные студенты',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/internationalStudentsPage',
                    ],
                ]
                ],
                ['text'    => 'Студентам',
                'icon'    => 'fas fa-fw fa-share',
                'submenu' => [
                    [
                        'text' => 'Академическая политика',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/academicPolicyPage',
                    ],
                    [
                        'text' => 'Академический календарь',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/academicCalendarPage',
                    ],
                    [
                        'text' => 'Библиотека',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/libraryPage',
                    ],
                    [
                        'text' => 'Этический кодекс',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/ethicsCodePage',
                    ],
                    [
                        'text' => 'Центр карьеры',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/careerCenterPage',
                    ],
                    [
                        'text' => 'Военная кафедра',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/militaryDepartmentPage',
                    ],
                    [
                        'text' => 'Медицинское обслуживание',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/medicalCarePage',
                    ],
                    [
                        'text' => 'Дом студента',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/studentHousePage',
                    ],
                    [
                        'text' => 'Путеводитель первокурсника',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/travelGuidePage',
                    ],
                    [
                        'text' => 'Студенческие клубы',
                        'icon' => 'fas fa-fw fa-file',
                        'url'  => '/admin/studentClubPage',
                    ]
                ]
                ]
            ]
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'change_password',
            'url'  => 'admin/edit',
            'icon' => 'fas fa-fw fa-lock',
        ],
        // ['header' => 'labels'],
        // [
        //     'text'       => 'important',
        //     'icon_color' => 'red',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'warning',
        //     'icon_color' => 'yellow',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'information',
        //     'icon_color' => 'cyan',
        //     'url'        => '#',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    */

    'livewire' => false,
];