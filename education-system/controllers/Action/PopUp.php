<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PopUp extends CI_Controller {

	public function index($img)
	{
		$this->load->view('popUp/popUp',array(
            'img' => $img
        ));
		
    }
    

}  
 
?>