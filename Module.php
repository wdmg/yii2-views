<?php

namespace wdmg\views;

/**
 * Yii2 Views
 *
 * @category        Module
 * @version         1.1.0
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-views
 * @copyright       Copyright (c) 2019 - 2023 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use wdmg\helpers\ArrayHelper;
use Yii;
use wdmg\base\BaseModule;
use wdmg\views\components\Views;

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
     * @var int hits cache lifetime, `0` - for not use cache
     */
    public $cacheExpire = 3600 * 24 * 7; // 1 week.

    /**
     * @var string the module version
     */
    private $version = "1.1.0";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 10;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set version of current module
        $this->setVersion($this->version);

        // Set priority of current module
        $this->setPriority($this->priority);

    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($options = null)
    {
        $items = [
            'label' => $this->name,
            'url' => [$this->routePrefix . '/'. $this->id],
            'icon' => 'fa fa-fw fa-eye',
            'active' => in_array(\Yii::$app->controller->module->id, [$this->id])
        ];

	    if (!is_null($options)) {

		    if (isset($options['count'])) {
			    $items['label'] .= '<span class="badge badge-default float-right">' . $options['count'] . '</span>';
			    unset($options['count']);
		    }

		    if (is_array($options))
			    $items = ArrayHelper::merge($items, $options);

	    }

	    return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);

        // Configure views component
        $app->setComponents([
            'views' => [
                'class' => Views::class
            ]
        ]);
    }
}