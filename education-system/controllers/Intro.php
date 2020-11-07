<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intro extends CI_Controller {

	//생성자 호출
	function __construct() {
		parent::__construct();
    }

	public function index()
	{   
        $this->introHead(1);
		$this->load->view('homePage/intro/intro');
		$this->load->view('homePage/intro/ourSkill');
		$this->load->view('homePage/common/footer');
		
	}

	public function aboutUs()
	{   
		$this->introHead(1);
		$this->load->view('homePage/intro/aboutUs');
		$this->load->view('homePage/intro/ourSkill');
		$this->load->view('homePage/common/footer');
	}

	public function location()
	{
		$this->introHead(1);
		$this->load->view('homePage/intro/location');
		$this->load->view('homePage/common/footer');
    }

    public function subjects($index)
	{
		$this->introHead(3);
		if($index){ //인덱스가 들어오면 디테일 페이지로
			$this->load->view('homePage/intro/detail/subject',array(
				'index' => $index
			));
		}else{
			$this->load->view('homePage/intro/subjects');
		}
		
		$this->load->view('homePage/common/footer');
	}

	public function gallery() { 
		$this->introHead(1);
		$this->load->view('homePage/intro/gallery');
		$this->load->view('homePage/common/footer');
	}
    
    private function introHead($index) {
        $this->load->view('homePage/common/header',array(
            'selectedMenuIndex' => $index
		)); 
    }
}
