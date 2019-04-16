<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->registerCssFile("css/css.css",['depends' => ['dmstr\web\AdminLteAsset']]);
$this->title = <?= $generator->generateString('Update {modelClass}: ', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?> . $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Update') ?>;
?>

<div class="header_view">
    <div class="title">编辑</div>
    <ul class="button_list">
        <li><?= "<?= " ?>Html::a(<?= $generator->generateString('返回') ?>, ['index'], ['class' => 'btn btn-default']) ?></li>
    </ul>
</div>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update c_view">

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
