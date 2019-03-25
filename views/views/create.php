<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\views\models\Views */

$this->title = Yii::t('app/modules/views', 'Create Views');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/views', 'Views'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="views-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
