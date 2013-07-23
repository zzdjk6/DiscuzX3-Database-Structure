<?php

$discuzdb = json_decode(file_get_contents('discuzdb.json'), true);
$ucenterdb = json_decode(file_get_contents('ucenterdb.json'), true);
$tables = array_merge($discuzdb, $ucenterdb);

$sql = '';
$html = '<html><head></head><body><table>';
$tr = "
<tr>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
";
foreach ($tables as $table) {
    $columns = $table['columns'];
    foreach ($columns as $field => $details) {
//        printf("表 %s 中 %s 字段，类型 %s，描述 %s\n", $table['name'], $field, $details['type'], $details['remark']);
        if ($details['remark']
            && mb_strpos($details['remark'], '用户', null, 'utf-8') !== false
            && mb_strpos($details['remark'], '名', null, 'utf-8') !== false
            && mb_strpos($details['remark'], '隔', null, 'utf-8') === false
            && strpos($details['type'], 'char') !== false
        ) {
            $html .= sprintf($tr, $table['name'], $field, $details['type'], $details['remark']);
//            printf("表 %s 中 %s 字段，类型 %s，描述 %s\n", $table['name'], $field, $details['type'], $details['remark']);
            $sql .= "ALTER TABLE `{$table['name']}` CHANGE `{$field}` `{$field}` VARCHAR(255);\n";
        }
    }
}
file_put_contents('username_related.sql', $sql);

$html .= '</table></body>';
file_put_contents('username_related.html',$html);

