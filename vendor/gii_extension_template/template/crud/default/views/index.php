<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile("css/css.css",['depends' => ['dmstr\web\AdminLteAsset']]);
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <?php if (!empty($generator->searchModelClass)): ?>
        <?= "    <?php " . ($generator->indexWidgetType === 'grid' ? " " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php endif; ?>

    <div class="header">
        <div class="title">列表</div>
        <ul class="button_list">

            <li>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('新建') ?>, ['create'], ['class' => 'btn btn-success']) ?>
            </li>
            <li>
                <a class="btn btn-success " href="javascript:void(0)" onclick="(document.getElementById('search').style.display=='block')?
                (document.getElementById('search').style.display='none')&&(this.text='筛选'):(document.getElementById('search').style.display='block')&&(this.text='关闭筛选'); ">筛选</a>
            </li>
        </ul>
    </div>
    <?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>
    <?php if ($generator->indexWidgetType === 'grid'): ?>
        <?= "<?= " ?>GridView::widget([
        'tableOptions' => ['class' => 'table table-striped'],
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'layout' => \"{items}{summary}{pager}\",\n'columns' => [\n" : "'columns' => [\n"; ?>
        //['class' => 'yii\grid\SerialColumn'],
        <?php
        $count = 0;
        if (($tableSchema = $generator->getTableSchema()) === false) {
            foreach ($generator->getColumnNames() as $name) {
                if (++$count < 6) {
                    echo "            '" . $name . "',\n";
                } else {
                    echo "            // '" . $name . "',\n";
                }
            }
        } else {
            foreach ($tableSchema->columns as $column) {
                $format = $generator->generateColumnFormat($column);
                if (++$count < 6) {
                    echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                } else {
                    echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
                }
            }
        }
        ?>

        [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}',
        'buttons' => [
        'update' => function ($url, $model, $key) {
        return Html::a('编辑', ['update', 'id' => $key], ['class' => 'btn btn-sm btn-success']);
        }
        ]
        ],
        ],
        ]); ?>
    <?php else: ?>
        <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
        return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
        ]) ?>
    <?php endif; ?>
    <?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>
</div>
