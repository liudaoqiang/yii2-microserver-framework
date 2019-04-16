<?php

namespace gaofeng\dbsv\models;




use Yii;
use yii\base\Model;

class SystemDbSearch extends Model
{
    /**
     * 获取所有数据表信息
     * @param void
     * @return array $tables 数据表信息列表
     */
    public function getTables(){
        $tables = [];
        $db = Yii::$app->db;
        // $tables = $this->getFromRedisSortedList();
        // if (empty($tables)) {
            $tableNames = Yii::$app->db->getSchema()->getTableNames();
            foreach ($tableNames as $key => $tableName) {
                $tableComment = $this->getTableComment($tableName);
                $show_fields_sql = 'SHOW FULL FIELDS FROM '.$tableName;
                $tableInfo = $db->createCommand($show_fields_sql)->queryAll();
                $table = [
                    'tableName'=>$tableName,
                    'tableComment'=>$tableComment,
                    'tableInfo'=>$tableInfo,
                    ];
                array_push($tables, $table);
                // $this->addToRedisSortedList($table);
            }
        // }
        return $tables;
    }

    /**
     * 获取数据表注释信息
     * @param string $tableName 数据表名称
     * @return string $comment 数据表注释
     */
    public function getTableComment($tableName){
        $tableComment = '';
        $db = Yii::$app->db;
        $table_sql = 'SHOW CREATE TABLE '.$tableName;
        $tableRes = $db->createCommand($table_sql)->queryOne();
        $comment = $tableRes['Create Table'];
        if (strstr($comment,"COMMENT='")){
            $comment = substr($comment,strrpos($comment,"COMMENT='")+9,-1);
        }else{
            $comment = '暂无';
        }
        return $comment;
    }

    /**
     * 将数据表信息添加到缓存列表
     * @param array $table 数据表信息
     * @return bool $addFlag 是否添加成功
     */
    public function addToRedisSortedList($table = []){
        $redis = Yii::$app->redis;
        $cacheName = 'table_list';
        $addFlag = false;
        try{
            $addFlag = $redis->RPUSH($cacheName, serialize($table));
        }catch(Exception $e){
            $addFlag = false;
        }
        return $addFlag;
    }

    /**
     * 读取缓存列表信息
     * @param void
     * @return array $tables 数据表信息列表
     */
    public function getFromRedisSortedList(){
        $redis = Yii::$app->redis;
        $cacheName = 'table_list';
        $tableList = [];
        $tables = [];
        try{
            $llen = $redis->LLEN($cacheName);
            $tableList = $redis->LRANGE($cacheName, 0, $llen-1);
        }catch(Exception $e){
            return $tables;
        }
        foreach ($tableList as $key => $serializedTable) {
            $table = unserialize($serializedTable);
            array_push($tables, $table);
        }
        return $tables;
    }
}
