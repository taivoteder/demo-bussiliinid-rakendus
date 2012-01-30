<?php
/* Zend_Controller_Action */

class IndexController extends Zend_Controller_Action {
	public function init()
	{
        $this->initView();
        $this->view->headScript()->appendFile('public/js/ajaxload.js');
        $ajaxContext = $this->_helper->getHelper('ajaxContext');
        $ajaxContext->addActionContext('index', 'html')
                    ->addActionContext('list', 'html')
                    ->initContext();
        include("./application/models/buslines.php");
        include("./application/models/busstops.php");
	}  
    public function indexAction()
    {
        $buslines = new Buslines();
        $this->view->buslines = $buslines->fetchAll();
        $this->render();

    }
    public function infoAction()
    {
        $this->view->pageTitle = 'Information';
        $this->view->title = 'Information page :]';
        $this->render();

    }
    public function contactAction()
    {
        $this->view->paginaTitel = 'Contact';
        $this->view->title = 'Contact us :]';
        $this->render();
    }
    
    public function listAction()
    {
        $list = array();
        if($this->getRequest()->isPost()){
            $type = $this->getRequest()->getPost('list');
            if($type == 'busline'){
                $buslines = new Buslines();
                $list = $buslines->fetchAll()->toArray();
            } else {
                $busstops = new Busstops();
                $list = $busstops->fetchAll()->toArray();   
            }
        }
        echo Zend_Json::encode($list);
    }
}
