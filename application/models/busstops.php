<?php
class Busstops extends Zend_Db_Table_Abstract { 
    protected $_name = 'busstops';
    protected $_dependentTables = array('Buslines');
    
    function findBuslines($id){
        $buslines = new Buslines();
        
        $select = $buslines->select();
        $select->from('buslines');
        $select->joinInner('bb','buslines.id = bb.buslines_id', array());
        $select->where('bb.busstops_id = ?',$id);
        $result = $buslines->fetchAll($select);
        return $result;
    }
}
?>