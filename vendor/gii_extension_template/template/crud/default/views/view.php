<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->registerCssFile("css/css.css",['depends' => ['dmstr\web\AdminLteAsset']]);
$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view c_view">

    <div class="header_view">
        <div class="title">详情</div>
        <ul class="button_list">
            <li><?= "<?= " ?>Html::a(<?= $generator->generateString('编辑') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?></li>
            <li>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('删除') ?>, ['delete', <?= $urlParams ?>], [
                'class' => 'btn btn-danger',
                'data' => [
                'confirm' => <?= $generator->generateString('你确定你要删除这个项目吗?') ?>,
                'method' => 'post',
                ],
                ]) ?>
            </li>
            <li><?= "<?= " ?>Html::a(<?= $generator->generateString('返回') ?>, ['index', <?= $urlParams ?>], ['class' => 'btn btn-default']) ?></li>
        </ul>
    </div>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

</div>
