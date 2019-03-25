<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\views\models\Views */

$this->title = Yii::t('app/modules/views', 'Update Views: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/views', 'Views'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app/modules/views', 'Update');
?>
<div class="views-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
