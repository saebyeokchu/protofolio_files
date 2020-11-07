<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends CI_Controller {

	//생성자 호출
	function __construct() {
		parent::__construct();
    }

	public function index($index)
	{
		$this->head(3);
		if($index){ //인덱스가 들어오면 디테일 페이지로
			$this->load->view('homePage/subject/detail',array(
				'index' => $index
			));
		}else{ 
			$this->load->view('homePage/subject/main');
		}
		
		$this->load->view('homePage/common/footer');
	}

    
    private function head($index) {
        $this->load->view('homePage/common/header',array(
			'selectedMenuIndex' => $index
		)); 
    }
}
