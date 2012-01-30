<?php
class BuslineController extends Zend_Controller_Action {
	public function init()
    {  
	    $this->initView();
        $ajaxContext = $this->_helper->getHelper('ajaxContext');
        $ajaxContext->addActionContext('index', 'html')
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
        $this->view->id = $busline->id;
        $this->render();
    }
    
    public function editAction()
    {

        $buslines = new Buslines();
        $busstops = new Busstops();
        $busstops = $busstops->fetchAll();
       
        $id = (int)$this->getRequest()->getParam('id');
        $db = Zend_Registry::get('db');
        
        if($this->getRequest()->isPost()){
            $data = array(
                    'name' => $this->getRequest()->getPost('buslineName'),
                    'description' => $this->getRequest()->getPost('buslineDescription')
                    );   
            
            $buslineId = (int)$this->getRequest()->getPost('formBuslineId');
            $buslines->update($data, 'id ='.$buslineId);
            
            $list = $this->getRequest()->getPost('busstopList');
            
            // delete old records and replace new selected ones
            $db->delete('bb', 'buslines_id = '.$buslineId);
            if(isset($list)){
                foreach($list as $busstopId){                
                    $values = array(
                              'buslines_id' => $buslineId,
                              'busstops_id' => $busstopId
                            ); 
                    $query = "INSERT INTO bb (buslines_id, busstops_id) VALUES(?,?) ON DUPLICATE KEY UPDATE buslines_id = VALUES(buslines_id), busstops_id = VALUES(busstops_id)";

                    $db->query($query, array_values($values));
                }
            }
            $this->_redirect('/busline/');
        } else {
            $busline = $buslines->fetchRow('id = '.$id);
            
            $selected = array();
            
            // retrieve all selected busstops
            $select = $db->select()
                         ->from('bb', array('busstops_id'))
                         ->where('buslines_id = ?',$id);
            $results = $db->query($select)->fetchAll();
            
            foreach($results as $result){
                array_push($selected, $result['busstops_id']);  
            }
            
            $this->view->selected = $selected;
            $this->view->buslineName = $busline->name;
            $this->view->buslineDescription = $busline->description;
            $this->view->busstops = $busstops;
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
            
            $this->_redirect('/busline/'); 
        } else {
            $busline = $buslines->fetchRow('id = '.$id);
            $this->view->buslineName = $busline->name;
        }
        $this->render();
    }
    
    public function buslineForm()
    {
        
        // application/forms ?
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