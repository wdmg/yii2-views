<?php

namespace wdmg\views;

/**
 * Yii2 Views
 *
 * @category        Module
 * @version         0.0.7
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-views
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;

/**
 * Views module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\views\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = "views/index";

    /**
     * @var string, the name of module
     */
    public $name = "Views";

    /**
     * @var string, the description of module
     */
    public $description = "System of accounting user views";

    /**
     * @var string the module version
     */
    private $version = "0.0.7";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 10;

    public function bootstrap($app)
    {
        parent::bootstrap($app);

        // Configure views component
        $app->setComponents([
            'views' => [
                'class' => Views::className()
            ]
        ]);
    }
}