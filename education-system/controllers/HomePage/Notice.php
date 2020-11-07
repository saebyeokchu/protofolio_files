<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {

	public function index()
	{
		$this->noticeHead();
		$this->load->view('homePage/notice');
		$this->load->view('homePage/common/footer');
		
    }
    
    private function noticeHead() {
        $this->load->view('homePage/common/header',array(
			'selectedMenuIndex' => 2
		)); 
    } 

}
