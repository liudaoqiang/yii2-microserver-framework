1. 在vendor\composer\autoload_psr4.php最下面增加

'vendor\\gaofeng\\db-schema-view\\' => array($vendorDir . '/gaofeng/db-schema-view'),
如图
               


2.  在vendor\yiisoft\extensions.php 最下面增加

       'gaofeng/db-schema-view\' =>
		array (
				'name' => 'gaofeng/dbsv',
				'version' => '2.0.6',
				'alias' =>
				array (
						'@vendor/gaofeng/db-schema-view' => $vendorDir . '/gaofeng/db-schema-view',
				),
		),

如图
