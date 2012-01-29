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
    
    public function addAction()
    {
        $errors = array();
        if($this->getRequest()->isPost()){
            $busstopName = $this->getRequest()->getPost('busstopName');
            $xCoord = $this->getRequest()->getPost('xCoord');
            $yCoord = $this->getrequest()->getPost('yCoord');
            
            if(empty($busstopName) || empty($xCoord) || empty($yCoord)){
                array_push($errors , 'Please fill all the fields'); 
            } elseif(!is_numeric($xCoord) || !is_numeric($yCoord)){
                array_push($errors, 'Coordinates must be numeric'); 
            } else {
                $busstops = new Busstops();
                $data = array(
                        'name' => $busstopName,
                        'x_coord' => $xCoord,
                        'y_coord' => $yCoord,
                        );
                $busstops->insert($data);
                $this->view->message = "New busstop added successfuly!";
            }
        }
        
        $this->view->errors = $errors;
        $this->render();
        
        
    }
    
    public function viewAction()
    {
        $busstops = new Busstops();
        $id = (int)$this->_request->getParam('id');
        $busstop = $busstops->fetchRow('id = '.$id);
        $this->view->busstopX = $busstop->x_coord;
        $this->view->busstopY = $busstop->y_coord;
        $this->view->buslines = $busstops->findBuslines($id);
        $this->view->name = $busstop->name;
        $this->render();
    }

    public function editAction()
    {

        $messages = array();
        $errors = array();
        $busstops = new Busstops();
       
        $id = (int)$this->getRequest()->getParam('id');
        if($this->getRequest()->isPost()){ 
            
            $busstopName = $this->getRequest()->getPost('busstopName');
            $xCoord = (int)$this->getRequest()->getPost('xCoord');
            $yCoord = (int)$this->getrequest()->getPost('yCoord');
            
            if(empty($busstopName) || empty($xCoord) || empty($yCoord)){
                array_push($errors , 'Please fill all the fields'); 
            } elseif(!is_numeric($xCoord) || !is_numeric($yCoord)){
                array_push($errors, 'Coordinates must be numeric'); 
            } else {
                
                $data = array(
                    'name' => $busstopName,
                    'x_coord' => $xCoord,
                    'y_coord' => $yCoord
                );
                
                $busstops->update($data, 'id ='.$this->getRequest()->getPost('formBusstopId'));
                array_push($messages, 'Edited succesfully');
            }
        } else {
            $busstop = $busstops->fetchRow('id = '.$id);
            $this->view->busstopId = $busstop->id;
            $this->view->busstopName = $busstop->name;
            $this->view->xCoord = $busstop->x_coord;
            $this->view->yCoord = $busstop->y_coord;
            
        }
        $this->view->errors = $errors;
        $this->view->messages = $messages;
        $this->render(); 
    }
}
?>