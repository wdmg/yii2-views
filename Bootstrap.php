<?php

namespace wdmg\views;

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
                $prefix . '<module:views>/' => '<module>/default/index',
                $prefix . '<module:views>/<controller>/' => '<module>/<controller>',
                $prefix . '<module:views>/<controller>/<action>' => '<module>/<controller>/<action>',
                $prefix . '<module:views>/<controller>/<action>' => '<module>/<controller>/<action>',
            ],
            true
        );
    }
}
