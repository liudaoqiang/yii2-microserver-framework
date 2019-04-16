<?php
$this->title = '数据表信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-db-index">
    <div class="page-header">
            <small>数据表总数为 <?= count($tables) ?></small>
    </div>
    <ul class='table_ul' style='list-style:none; padding-left:0;'>
        <?php foreach ($tables as $table) {
        echo "<li class='table_li'>";
        echo "<div class='table_info' style='width:100%;'>";
        $tableName = $table['tableName'];
        $tableComment = $table['tableComment'];
        $tableInfo = $table['tableInfo'];
        echo "<p style='font-weight:700' >".$tableName." (".$tableComment.")</p>";

        echo "<table class='table table-bordered table-hover' width='100%' border='1' cellpadding ='2' cellspacing ='2'>";
        echo "<tr class='table_info_desc'>";
        echo "<th style='width:10%;margin-left:10px;margin-right:10px;'>字段</th>";
        echo "<th style='width:10%;margin-left:10px;margin-right:10px;'>类型</th>";
        echo "<th style='width:10%;margin-left:10px;margin-right:10px;'>排序规则</th>";
        echo "<th style='width:10%;margin-left:10px;margin-right:10px;'>允许空值</th>";
        echo "<th style='width:5%;margin-left:10px;margin-right:10px;'>索引</th>";
        echo "<th style='width:5%;margin-left:10px;margin-right:10px;'>默认值</th>";
        echo "<th style='width:10%;margin-left:10px;margin-right:10px;'>其他</th>";
        echo "<th style='width:15%;margin-left:10px;margin-right:10px;'>权限</th>";
        echo "<th style='width:25%;margin-left:10px;margin-right:10px;'>备注</th>";
        echo "</tr>";
        foreach ($tableInfo as $field) {
            echo "<tr class='table_info_field'>"
                ."<td>".$field['Field']."</td>"
                ."<td>".$field['Type']."</td>"
                ."<td>".$field['Collation']."</td>"
                ."<td>".$field['Null']."</td>"
                ."<td>".$field['Key']."</td>"
                ."<td>".$field['Default']."</td>"
                ."<td>".$field['Extra']."</td>"
                ."<td>".$field['Privileges']."</td>"
                ."<td>".$field['Comment']."</td>"
                ."</tr>";
        }
        echo "</table>";
        echo "</div>";
        echo "</li>";
        echo "<hr/>";
        } ?>
    </ul>
</div>
