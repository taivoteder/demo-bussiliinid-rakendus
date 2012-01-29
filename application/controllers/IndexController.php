<?php
/* Zend_Controller_Action */

class IndexController extends Zend_Controller_Action {
	public function init()
	{
		//prepare the view we will render in the actions
	    $this->initView();
	    //declare variables in header, menu and footer
	    // $this->view->url = $this->_request->getBaseUrl();
	    $this->view->stylesPath = $this->view->url . '/public/styles/';
        
        $this->view->layout()->navigation = "<h1>Navigation</h1>";
        include("./application/models/buslines.php");
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
}
