<?php

namespace wdmg\views;

/**
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 */

use yii\base\BootstrapInterface;
use Yii;


class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // Get the module instance
        $module = Yii::$app->getModule('views');

        // Get URL path prefix if exist
        $prefix = (isset($module->routePrefix) ? $module->routePrefix . '/' : '');

        // Add module URL rules
        $app->getUrlManager()->addRules(
            [
                $prefix . '<module:views>/' => '<module>/views/index',
                $prefix . '<module:views>/<controller>/' => '<module>/<controller>',
                $prefix . '<module:views>/<controller>/<action>' => '<module>/<controller>/<action>',
                $prefix . '<module:views>/<controller>/<action>' => '<module>/<controller>/<action>',
            ],
            true
        );
    }
}
