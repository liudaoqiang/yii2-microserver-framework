<?php

namespace gaofeng\dbsv\controllers;

use yii\web\Controller;
use gaofeng\dbsv\models\SystemDbSearch;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new SystemDbSearch;
    	$tables = $searchModel->getTables();
         return $this->render('index', [
            'tables'=> $tables,
        ]);
    }
}
