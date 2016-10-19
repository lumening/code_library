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
function arrayToSql($action='update', $table='', $data, $condition='', $field='*', $limit=''){
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

    if($action == 'select'){
        return 'SELECT ' . (is_array($field) ? implode(',', $field) : $field) .' FROM ' . $table . $condition .' '.$limit;
    }elseif($action == 'delete'){
        return 'DELETE FROM ' . $table . $condition .' '.$limit;
    }elseif($action == 'update'){
        return 'UPDATE '. $table . ' SET '. implode(', ', $tmp_data). $condition .' '.$limit;
    }else{
        return $action . ' INTO ' . $table . ' SET '. implode(', ', $tmp_data) . $condition . ' '.$limit;
    }
}