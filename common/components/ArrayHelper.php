<?php

namespace common\components;

use yii\base\Exception;

class ArrayHelper extends \yii\helpers\ArrayHelper{

    public static function sumObjectColumn($objectList, $column){
        if (!is_array($objectList)){
            throw new Exception('传入参数1不是数组');
        }
        $value = 0;
        if (!empty($objectList)){
            foreach ($objectList as $object){
                $value += floatval($object->getAttribute($column));
            }
        }
        return $value;
    }

    public static function sumByColumn($data_arr, $column){
        if (!is_array($data_arr)){
            throw new Exception('传入参数1不是数组');
        }
        if (empty($data_arr)){
            return 0;
        }
        if (!in_array($column, array_keys($data_arr[0]))){
            throw new Exception('传入参数2不是数组的键');
        }

        $sum = 0;
        foreach ($data_arr as $data_row){
            $add_num = $data_row[$column];
            $sum += $add_num;
        }
        return $sum;
    }

    /**
     * 二维数组的唯一化
     * @param $array
     * @param $key
     * @return array
     */
    public static function uniqueMultidimArray($array, $key){
         $temp_array = array();
         $i = 0;
         $key_array = array();

         foreach($array as $val){
             if(!in_array($val[$key],$key_array)){
                 $key_array[$i] = $val[$key];
                 $temp_array[$i] = $val;
             }
            $i++;
        }
        return $temp_array;
    }

    public static function filterMultidimArray($array, $key){
        $temp_array = array();
         $i = 0;
         $key_array = array();

         foreach($array as $val){
             if(!empty($val[$key])){
                 $temp_array[$i] = $val;
             }
            $i++;
        }
        return $temp_array;
    }

    /**
     * @param $clazz
     * 将对象模型转换为关联数组
     */
    public static function object2Array($clazz){
        if(!is_object($clazz)){
            throw new \yii\base\Exception('传入参数不是对象模型');
        }
        $arr = json_decode(json_encode($clazz), true);
        return $arr;
    }

    public static function groupBy($dataArr, $groupFields=[], $summaryFields=[]){
        if (!is_array($dataArr)){
            throw new Exception('传入参数1不是数组');
        }
        if (empty($groupFields)){
            return $dataArr;
        }

        $allFields = array_keys($dataArr[0]);

        $data = [];
        while(null != ($currentRow =  array_shift($dataArr))){
            $multiItem = [];
            $item = [];
            $multiItem[] = $currentRow;
            foreach ($dataArr as $dataKey => &$dataRow){
                for($i=0; $i<count($groupFields); $i++){
                    $groupField = $groupFields[$i];
                    if ($currentRow[$groupField] != $dataRow[$groupField]){
                        break 2;
                    }elseif($i >= count($groupFields)-1){
                        $multiItem[] = $dataRow;
                        unset($dataRow);
                    }else{
                        continue;
                    }
                }
            }
            $item = $multiItem[0];
            foreach ($summaryFields as $summaryField){
                $item[$summaryField] = 0;
            }
            foreach ($summaryFields as $summaryField){
                $item[$summaryField] = self::sumByColumn($multiItem, $summaryField);
            }
            $data[] = $item;
        }
        return $data;
    }

    public static function likeInArray($item, $arr){
        if (empty($arr)){
            return false;
        }else{
            foreach ($arr as $temp){
                if (false !== strpos($temp, $item)){
                    return true;
                }
                echo strpos($temp, $item);
            }
            return false;
        }
    }

    /**
     * 二维数组排序
     * @param $arrays
     * @param $sort_key
     * @param int $sort_order
     * @param int $sort_type
     * @return array|bool
     */
    public static function two_dimensional_array_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC)
    {
        if(is_array($arrays)){
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }

        /**
         * 数组分页函数 (核心函数 array_slice)
         * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
         * @param $count 每页多少条数据
         * @param $page 当前第几页
         * @param $array 查询出来的所有数组
         * @param $order 0 - 不变   1- 反序
         * @return array
         */
        public static function page_array($count,$page,$array,$order=0)
        {
            $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
            $start=($page-1)*$count; #计算每次分页的开始位置
            if($order==1){
                $array=array_reverse($array);
            }
            $totals=count($array);
            $countpage=ceil($totals/$count); #计算总页面数
            $pagedata=array();
            $pagedata=array_slice($array,$start,$count);
            return ['list' => $pagedata, 'total' => $totals, 'pageSize' => $count, 'current' => $page]; #返回查询数据
        }
}