<?php
class BuslineController extends Zend_Controller_Action {
	public function init()
    {
        
	    $this->initView();
	    // $this->view->url = $this->_request->getBaseUrl();
	    $this->view->stylesPath = $this->view->url . '/public/styles/';
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('list','json')
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
    
    public function listAction()
    {
        // ...
        
    }
    
    public function viewAction()
    {
        $buslines = new Buslines();      
        $id = (int)$this->_request->getParam('id');
        $busline = $buslines->fetchRow('id = '.$id);
        $this->view->busstops = $buslines->findBusstops($id);
        $this->view->name = $busline->name;
        $this->view->description = $busline->description;
        $this->view->dateAdded = $busline->date_add;
        $this->render();
    }
    
    public function editAction()
    {

        $buslines = new Buslines();
        $id = (int)$this->_request->getParam('id');
        $busline = $buslines->fetchRow('id = '.$id);
        $this->view->buslineId = $busline->id;
        $this->view->buslineName = $busline->name;
        $this->view->buslineDescription = $busline->description;
        $this->render();
        
        
        if($this->getRequest()->isPost()){
            
            $this->_redirect('/');
            
        }
    }
    
    public function buslineForm()
    {
        $view = new Zend_View();
        
        $form = new Zend_Form();
        $form->setView($view);
        $form->setAction('/uus/busline/edit')
             ->setMethod('post')
             ->setAttrib('class','editor');
             
  		$id = $form->createElement('text', 'id');
        $id->setAttrib('disabled', true)
           ->setLabel('ID:');
        
        $name = $form->createElement('text','name');
        $name->setRequired(true)
             ->addFilter('StringToLower')
			 ->setLabel('Busline name:');
             
        $description = $form->createElement('textarea','description');
        
        $form->addElement($id)
             ->addElement($name)
             ->addElement($description)
             ->addElement('submit', 'login', array('label','login'));
        
        return $form;
        
    }
    
}
?>