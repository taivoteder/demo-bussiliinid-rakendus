<?php
class BusstopController extends Zend_Controller_Action {
	public function init()
    {
	    $this->initView();
	    // $this->view->url = $this->_request->getBaseUrl();
	    $this->view->stylesPath = $this->view->url . '/public/styles/';  
        include("./application/models/buslines.php");
        include("./application/models/busstops.php");
	}  
    public function indexAction()
    {
        $busstops = new Busstops();
        $this->view->busstops = $busstops->fetchAll();
        $this->render();
    }
    
    public function viewAction()
    {
        $busstops = new Busstops();
        $id = (int)$this->_request->getParam('id');
        $busstop = $busstops->fetchRow('id = '.$id);
        $this->view->buslines = $busstops->findBuslines($id);
        $this->view->name = $busstop->name;
        $this->render();
    }
}
?>