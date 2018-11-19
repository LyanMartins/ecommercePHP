<?php 

namespace classe;

use Rain\Tpl;

class Page 
{
	
	private $tpl; 
	private $option = [];
	private $defaults =  [

		"data" => []

	];


	public function __construct($opts = array()){
		
		$this->option = array_merge($this->defaults, $opts);

		$config = array(
					"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/ecommerce/views/",
					"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/ecommerce/views-cache/",
					"debug"         => false // set to false to improve the speed
				   );

		Tpl::configure( $config );

		$this->tpl = new Tpl;


		$this->setData($this->option["data"]);

		$this->tpl->draw("header");

	}

	private function setData($data = array()){

		foreach ($data as $key => $value) {
			
			$this->tpl->assign($key,$value);

		}
	}


	public function setTpl($name,$data= array(),$returnHTML = false){

		$this->setData($data);

		return $this->tpl->draw($name,$returnHTML);

	}

	public function __destruct(){

	
		$this->tpl->draw("footer");
	
	}


}
