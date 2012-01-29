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
        $this->view->buslines = $busstops->findBuslines($id);
        $this->view->name = $busstop->name;
        $this->render();
    }
}
?>