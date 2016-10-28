<?php
/**
 * 数据库语句
 *
 * @param string $action
 * @param string $table
 * @param        $data
 * @param string $condition
 * @param string $field
 * @param string $limit
 * @return string
 */
function array_2_sql($action='update', $table='', $data, $condition='', $field='*', $limit=''){
    $tmp_data = array();
    if(is_array($data)){
        foreach ($data as $k => $v) {
            $k = addslashes(trim($k));
            $v = addslashes(trim($v));
            $tmp_data[] = "{$k}='{$v}'";
        }
    }else{
        $tmp_data[] = $data;
    }

    if($condition){
        $tmp_cond = array();
        if(is_array($condition)){
            foreach ($condition as $k => $v) {
                $k = addslashes(trim($k));
                $v = addslashes(trim($v));
                $tmp_cond[] = "{$k}='{$v}'";
            }
        }else{
            $tmp_cond[] = $condition;
        }
        $condition = ' WHERE '. implode(' AND ', $tmp_cond);
    }else{
        $condition = '';
    }

    $limit = $limit ? (' LIMIT '.$limit) : '';

    switch(strtolower($action)) {
        case "select" :
            return 'SELECT ' . (is_array($field) ? implode(',', $field) : $field) .' FROM ' . $table . $condition .' '.$limit;
        case "update" :
            return 'UPDATE '. $table . ' SET '. implode(', ', $tmp_data). $condition .' '.$limit;
        case "delete" :
            return 'DELETE FROM ' . $table . $condition .' '.$limit;
        default :
            return $action . ' INTO ' . $table . ' SET '. implode(', ', $tmp_data) . $condition . ' '.$limit;
    }
}