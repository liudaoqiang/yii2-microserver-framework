<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">' . Yii::$app->name . '</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">


                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user3-128x128.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=Yii::$app->user->identity->username; ?></span>
                        <span class="hidden-xs">设置</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <!-- /.search form -->
                        <?php if(file_exists(Yii::$app->basePath.'/config/top-menu-config.php')):?>
                            <?php require Yii::$app->basePath.'/config/top-menu-config.php';?>
                        <?php else:?>
                          <li >
                              <a href="#">请添加top-menu-config.php到config</a>
                          </li>
                        <?php endif;?>

                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                          <li class="user-footer">

                              <div >
                                  <?= Html::a(
                                      '注销',
                                      ['/site/logout'],
                                      ['data-method' => 'post', 'class' => 'btn-flat']
                                  ) ?>
                              </div>
                          </li>


                    </ul>
                </li>


            </ul>
        </div>
    </nav>
</header>
