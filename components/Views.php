<?php

namespace wdmg\views\components;


/**
 * Yii2 Views
 *
 * @category        Component
 * @version         0.0.12
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-views
 * @copyright       Copyright (c) 2019 - 2020 W.D.M.Group, Ukraine
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

    public function get($context = 'default', $target = null) {

        if (is_null($context))
            return null;

        if (is_null($target) && ($request = Yii::$app->request->resolve())) {

            // Request route
            if (isset($request[0]))
                $target = $request[0];

        }

        $query = $this->model::find()->where(['context' => $context, 'target' => $target]);
        if ($query->exists())
            return intval($query->sum('counter'));
        else
            return null;
    }

    public function set($context = 'default', $target = null, $returnCounter = true) {

        if (is_null($context))
            return null;

        $params = [];
        $user_id = null;
        if (!Yii::$app->getUser()->isGuest)
            $user_id = Yii::$app->getUser()->getId();

        if ($request = Yii::$app->getRequest()) {
            $params['request']['url'] = $request->getAbsoluteUrl();
        }

        if (is_null($target) && ($request = Yii::$app->request->resolve())) {

            // Request route
            if (isset($request[0])) {
                $target = $request[0];
                $params['request']['route'] = $request[0];
            }

            // Request query params
            if (isset($request[1]))
                $params['request']['query'] = $request[1];

        }

        if ($user_id)
            $query = $this->model::find()->where(['context' => $context, 'target' => $target, 'user_id' => $user_id]);
        else
            $query = $this->model::find()->where(['context' => $context, 'target' => $target, 'user_id' => null]);

        if ($query->exists()) {
            $model = $query->one();
            if ($model->updateCounters(['counter' => 1])) {
                if ($returnCounter) {
                    $count = $model->counter;

                    $query = $this->model::find()->where(['context' => $context, 'target' => $target]);
                    if ($user_id) {
                        $query->andWhere(['OR',
                            ['!=', 'user_id', $user_id],
                            ['user_id' => null],
                        ]);
                        if ($counter = $query->sum('counter')) {
                            $count += $counter;
                        }
                    } else {
                        $query->andWhere(['!=', 'user_id', null]);
                        if ($counter = $query->sum('counter')) {
                            $count += $counter;
                        }
                    }
                    return intval($count);
                }
                return true;
            }
        } else {
            $model = $this->model;
            $model->context = trim($context);
            $model->target = trim($target);
            $model->params = $params;
            $model->user_id = $user_id;

            if ($model->validate()) {
                if ($model->save()) {
                    if ($returnCounter) {
                        return $model->counter;
                    }
                    return true;
                }
            }
        }

        return false;
    }
}

?>