<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use wdmg\widgets\SelectInput;
use wdmg\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel wdmg\views\models\ViewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $this->context->module->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-header">
    <h1>
        <?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small>
    </h1>
</div>
<div class="views-index">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{summary}<br\/>{items}<br\/>{summary}<br\/><div class="text-center">{pager}</div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'context',
            'target',
            [
                'attribute' => 'counter',
                'label' => Yii::t('app/modules/views','Views'),
                'format' => 'html',
                'filter' => SelectInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'range',
                    'items' => $searchModel->getViewsRangeList(true),
                    'options' => [
                        'id' => 'views-range',
                        'class' => 'form-control'
                    ]
                ]),
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function($data) {

                    $class = 'label';
                    $views = intval($data->views);

                    if ($views >= 1000 && $views < (1000 * 10))
                        $class .= ' label-info';
                    elseif ($views >= (1000 * 10) && $views < (1000 * 100))
                        $class .= ' label-primary';
                    elseif ($views >= (1000 * 100) && $views < (1000 * 1000))
                        $class .= ' label-success';
                    elseif ($views >= (1000 * 1000) && $views < (1000 * 1000 * 10))
                        $class .= ' label-warning';
                    elseif ($views >= (1000 * 1000 * 10))
                        $class .= ' label-danger';
                    else
                        $class .= ' label-default';

                    return Html::tag('span', StringHelper::integerAmount($views, 2, true), [
                        'title' => Yii::t('app/modules/views','{views} views', [
                            'views' => $views
                        ]),
                        'class' => $class,
                        'data-toggle' => 'tooltip'
                    ]);
                }
            ],
            //'user_id',
            //'params',
            'created_at',
            'updated_at',
        ],
    ]); ?>
    <hr/>
    <?php Pjax::end(); ?>
</div>

<?php $this->registerJs(<<< JS
    $(function () { 
       $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
            html:true
        });
    });
JS
); ?>

<?php echo $this->render('../_debug'); ?>
