[![Progress](https://img.shields.io/badge/required-Yii2_v2.0.13-blue.svg)](https://packagist.org/packages/yiisoft/yii2)
[![Github all releases](https://img.shields.io/github/downloads/wdmg/yii2-views/total.svg)](https://GitHub.com/wdmg/yii2-views/releases/)
[![GitHub version](https://badge.fury.io/gh/wdmg/yii2-views.svg)](https://github.com/wdmg/yii2-views)
![Progress](https://img.shields.io/badge/progress-in_development-red.svg)
[![GitHub license](https://img.shields.io/github/license/wdmg/yii2-views.svg)](https://github.com/wdmg/yii2-views/blob/master/LICENSE)

# Yii2 User views
System of accounting user views

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.13 and newest
* [Yii2 Users](https://github.com/wdmg/yii2-users) module (optionaly)

# Installation
To install the module, run the following command in the console:

`$ composer require "wdmg/yii2-views"`

After configure db connection, run the following command in the console:

`$ php yii views/init`

And select the operation you want to perform:
  1) Apply all module migrations
  2) Revert all module migrations

# Migrations
In any case, you can execute the migration and create the initial data, run the following command in the console:

`$ php yii migrate --migrationPath=@vendor/wdmg/yii2-views/migrations`

# Configure
To add a module to the project, add the following data in your configuration file:

    'modules' => [
        ...
        'views' => [
            'class' => 'wdmg\views\Module',
            'routePrefix' => 'admin'
        ],
        ...
    ],

If you have connected the module not via a composer add Bootstrap section:

`
$config['bootstrap'][] = 'wdmg\views\Bootstrap';
`

# Routing
Use the `Module::dashboardNavItems()` method of the module to generate a navigation items list for NavBar, like this:

    <?php
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
            'label' => 'Modules',
            'items' => [
                Yii::$app->getModule('views')->dashboardNavItems(),
                ...
            ]
        ]);
    ?>

# Status and version [in progress development]
* v.0.0.3 -  Refactoring migrations and models