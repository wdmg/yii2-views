<?php

namespace wdmg\views\components;


/**
 * Yii2 Views
 *
 * @category        Component
 * @version         0.0.7
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-views
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;

class Views extends Component
{

    protected $model;

    /**
     * Initialize the component
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->model = new \wdmg\views\models\Views;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($param) {
        return parent::__get($param);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($param, $value) {
        return parent::__set($param, $value);
    }

}

?>