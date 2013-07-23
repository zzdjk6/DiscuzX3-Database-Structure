<?php

$html_str = iconv('GBK', 'UTF-8//IGNORE', file_get_contents('discuzx3db.html'));
$html_str = str_replace("</table>", "</table><h2>", $html_str);
$html_str = str_replace("<table", "</h2><table", $html_str);

$tidy = new tidy();
$tidy->parseString($html_str, array(
    'indent' => true,
    'output-xhtml' => true), 'utf8');
$tidy->cleanRepair();
$html_str = sprintf("%s", $tidy);

$html = mb_convert_encoding($html_str, 'HTML-ENTITIES', 'UTF-8');
$dom = new DOMDocument();
$dom->loadHTML($html);
$h2_list = $dom->getElementsByTagName('h2');
$tables = array();
foreach ($h2_list as $i => $h2) {
    /* @var $h2 DOMNode */
    $nodeValue = trim($h2->nodeValue);

    // remove extra lines
    if (in_array($i, array_merge(range(0, 2), array(245, 246)))) {
        continue;
    }

    //echo $i . '=>' . $nodeValue . "\n";
    list($table_name, $table_remark) = explode(' ', $nodeValue, 2);

    $tbody = $h2->nextSibling->nextSibling->firstChild;
    $columns = array();
    /* @var $tbody DOMNode */
    foreach ($tbody->childNodes as $j => $tr) {
        if ($j == 0) {
            continue;
        }
        /* @var $tr DOMNode */
        $td_list = $tr->childNodes;
        $column_name = trim($td_list->item(0)->nodeValue);
        $column_type = trim($td_list->item(2)->nodeValue);
        $column_default = trim($td_list->item(4)->nodeValue);
        $column_nullable = trim($td_list->item(6)->nodeValue);
        $column_autoincrement = trim($td_list->item(8)->nodeValue);
        $column_remark = trim($td_list->item(10)->nodeValue);
        $columns[$column_name] = array(
            'type' => $column_type,
            'default' => $column_default,
            'nullable' => $column_nullable,
            'autoincrement' => $column_autoincrement,
            'remark' => $column_remark,
        );
    }

    $tables[] = array(
        'name' => $table_name,
        'remark' => $table_remark,
        'columns' => $columns,
    );
}
file_put_contents('discuzdb.json', json_encode($tables));