<?php

namespace wdmg\views\components;


/**
 * Yii2 Views
 *
 * @category        Component
 * @version         1.0.1
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-views
 * @copyright       Copyright (c) 2019 - 2021 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

class Views extends Component
{

    protected $module;
    protected $model;

    /**
     * Initialize the component
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->module = Yii::$app->getModule('views');
        if (is_null($this->module))
            $this->module = Yii::$app->getModule('admin/views');

        $this->model = new \wdmg\views\models\Views;

        parent::init();
    }

    /**
     * Returns the count of views of the object/target.
     *
     * @param string $context
     * @param null $target
     * @return int|null
     * @throws \yii\console\Exception
     * @throws \yii\web\NotFoundHttpException
     */
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

    /**
     * Sets the counter of the number of views of the object / target, and also checks for
     * uniqueness of viewing if necessary.
     *
     * @param string $context
     * @param null $target
     * @param bool $returnCounter
     * @param bool $onlyHits
     * @return bool|int|null
     * @throws \yii\console\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function set($context = 'default', $target = null, $returnCounter = false, $onlyHits = true) {

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

        if ($onlyHits && ($cache = Yii::$app->getCache()) && ($session = Yii::$app->session->getId())) {

            $isPresent = false;
            if ($cache->exists(md5('views-counter'))) {
                $data = $cache->get(md5('views-counter'));
                if ($data && ($session = Yii::$app->session->getId())) {
                    if (isset($data[$session])) {
                        $hits = $data[$session];
                        foreach ($hits as $hit) {
                            if (($hit['context'] == $context) && ($hit['target'] == $target)) {
                                $isPresent = true;
                                break;
                            }
                        }
                    }
                }
            }

            if ($isPresent) {
                if ($returnCounter)
                    return $this->get($context, $target);
                else
                    return false;
            }
        }

        if ($user_id)
            $query = $this->model::find()->where(['context' => $context, 'target' => $target, 'user_id' => $user_id]);
        else
            $query = $this->model::find()->where(['context' => $context, 'target' => $target, 'user_id' => null]);

        if ($query->exists()) {
            $model = $query->one();
            if ($model->updateCounters(['counter' => 1])) {

                if ($onlyHits)
                    $this->cachingHits($context, $target);

                if ($returnCounter)
                    return $this->get($context, $target);
                else
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

                    if ($onlyHits)
                        $this->cachingHits($context, $target);

                    if ($returnCounter)
                        return $this->get($context, $target);
                    else
                        return true;
                }
            }
        }

        return false;
    }

    /**
     * Adds browsing information to the cache using the visitor session identifier.
     *
     * @param null $context
     * @param null $target
     * @return array|mixed|null
     */
    private function cachingHits($context = null, $target = null) {

        if (is_null($context) || is_null($target))
            return null;

        $data = [];
        if (intval($this->module->cacheExpire) !== 0 && ($cache = Yii::$app->getCache()) && ($session = Yii::$app->session->getId())) {

            if ($cache->exists(md5('views-counter')))
                $data = $cache->get(md5('views-counter'));

            if (isset($data[$session])) {
                $data[$session] = ArrayHelper::merge($data[$session], [
                    [
                        'context' => $context,
                        'target' => $target
                    ]
                ]);
            } else {
                $data[$session] = [
                    [
                        'context' => $context,
                        'target' => $target
                    ]
                ];
            }

            $cache->set(md5('views-counter'), $data, intval($this->module->cacheExpire));
        }

        return $data;
    }
}

?>