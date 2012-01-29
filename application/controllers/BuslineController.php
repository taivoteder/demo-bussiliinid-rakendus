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

    public function addAction()
    {
        $busstops = new Busstops();
        $this->view->busstops = $busstops->fetchAll();
        if($this->getRequest()->isPost()){
            $buslineName = $this->getRequest()->getPost('buslineName');
            $buslineDescription = $this->getRequest()->getPost('buslineDescription');
            
            if(empty($buslineName) || empty($buslineDescription)){
                $this->view->error = "Please fill all fiealds";
            } else {
                
                $buslines = new Buslines();
                $data = array(
                        'name' => $buslineName,
                        'description' => $buslineDescription
                        );
                $buslineId = $buslines->insert($data);
                                
                $db = Zend_Registry::get('db');        
                $list = $this->getRequest()->getPost('busstopList');
                
                if(isset($list)){
                    foreach($list as $id){
                        $data = array(
                                'buslines_id' => $buslineId,
                                'busstops_id' => $id,
                                );
                                
                        $db->insert('bb',$data);
                    }
                }


                // 
                $this->view->message = "New busline added.";
            } 
        }
        $this->render();
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
       
        $id = (int)$this->getRequest()->getParam('id');
        if($this->getRequest()->isPost()){ 
            $data = array(
                    'name' => $this->getRequest()->getPost('buslineName'),
                    'description' => $this->getRequest()->getPost('buslineDescription')
                    );        
            $buslines->update($data, 'id ='.$this->getRequest()->getPost('formBuslineId'));
            
            $this->_redirect('/'); 
        } else {
            $busline = $buslines->fetchRow('id = '.$id);
            $this->view->buslineId = $busline->id;
            $this->view->buslineName = $busline->name;
            $this->view->buslineDescription = $busline->description;
            
        }
        
        $this->render(); 
    }
    
    public function deleteAction(){
        
        $buslines = new Buslines();
        
        $id = $this->getRequest()->getParam('id');
        if($this->getRequest()->isPost()){
            $id = (int)$this->getRequest()->getPost('id');
            $action = $this->getRequest()->getPost('del');
            if($action == 'Yes'){
                $db = Zend_Registry::get('db');
                $db->delete('bb','buslines_id = '.$id);    
                $db->delete('buslines','buslines.id = '.$id);
            }
            
            $this->_redirect('/'); 
        } else {
            $busline = $buslines->fetchRow('id = '.$id);
            $this->view->buslineId = $busline->id;
            $this->view->buslineName = $busline->name;
            $this->view->buslineDescription = $busline->description;
            
        }
        $this->render();
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
        $id->setLabel('ID:');
        
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