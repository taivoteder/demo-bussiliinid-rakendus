<?php

class Buslines extends Zend_Db_Table_Abstract {
    
    protected $_name = 'buslines';
    // protected $_rowClass = 'Busline'; // single Busline object
    // protected $_dependentTables = array('Busstops');
    
    function findBusstops($id){
        $busstops = new Busstops();
    /**
     * SELECT * 
     * FROM busstops
     * INNER JOIN bb ON busstops.id = bb.busstops_id
     * WHERE bb.buslines_id = $id 
     */ 
        $select = $busstops->select();
        $select->from('busstops');
        $select->joinInner('bb','busstops.id = bb.busstops_id', array());
        $select->where('bb.buslines_id = ?', $id);
        $result = $busstops->fetchAll($select);
        
        return $result;
    }
}

?>