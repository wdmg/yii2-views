[![Progress](https://img.shields.io/badge/required-Yii2_v2.0.40-blue.svg)](https://packagist.org/packages/yiisoft/yii2)
[![Github all releases](https://img.shields.io/github/downloads/wdmg/yii2-views/total.svg)](https://GitHub.com/wdmg/yii2-views/releases/)
[![GitHub version](https://badge.fury.io/gh/wdmg/yii2-views.svg)](https://github.com/wdmg/yii2-views)
![Progress](https://img.shields.io/badge/progress-ready_to_use-green.svg)
[![GitHub license](https://img.shields.io/github/license/wdmg/yii2-views.svg)](https://github.com/wdmg/yii2-views/blob/master/LICENSE)

<img src="./docs/images/yii2-views.png" width="100%" alt="Yii2 User views" />

# Yii2 User views counting
System of accounting user views.
                                                     
This module is an integral part of the [Butterfly.СMS](https://butterflycms.com/) content management system, but can also be used as an standalone extension.

Copyrights (c) 2019-2021 [W.D.M.Group, Ukraine](https://wdmg.com.ua/)

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.40 and newest
* [Yii2 Base](https://github.com/wdmg/yii2-base) module (required)
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
            'routePrefix' => 'admin',
            'cacheExpire' => 3600 // hits cache lifetime, `0` - for not use cache
        ],
        ...
    ],
    
# Usage examples
To accountштп the number of views of an object, use the component method `Yii::$app->views-set()` module, into which you need to transfer the context and identifier. The same parameters are a condition for unique selection/filtering when receiving a view counter in the method `Yii::$app->views-get()` of component.

**Store and get the views count**

    <?php
        if ($views = Yii::$app->views) {
            $count = $views->set(
                'page-views', // context (string, human-readable description of the section)
                'site/index', // target of object (string|null, by default is resolved route)
                true // flag, true - for return actualy count of views,
                true // flag, true - for register only unique views
            );
            echo $count;
        }
    ?>
    
**Only get the count of views**

    <?php
        if ($views = Yii::$app->views) {
            $count = $views->get(
                'page-views', // context (string, human-readable description of the section)
                'site/index', // target of object (string|null, by default is resolved route)
            );
            echo $count;
        }
    ?>
    
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

# Status and version [ready to use]
* v.1.0.1 - Update dependencies, README.md
* v.1.0.0 - Added component for set/get views counter
* v.0.0.12 - Update README.md and dependencies
* v.0.0.11 - Fixed deprecated class declaration
* v.0.0.10 - Added extra options to composer.json and navbar menu icon
* v.0.0.9 - Added choice param for non interactive mode