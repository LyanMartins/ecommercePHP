<?php 

namespace classe;

use Rain\Tpl;

class Page 
{
	
	private $tpl; 
	private $option = [];
	private $defaults =  [

		"header" => true,
		"footer" => true,
		"data" => []

	];


	public function __construct($opts = array(),$tpl_dir = "/ecommerce/views/"){
		
		$this->option = array_merge($this->defaults, $opts);

		$config = array(
					"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
					"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/ecommerce/views-cache/",
					"debug"         => false // set to false to improve the speed
				   );

		Tpl::configure( $config );

		$this->tpl = new Tpl;


		$this->setData($this->option["data"]);

		if($this->option["header"] === true) {
		
			$this->tpl->draw("header");
		
		}
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

		if($this->option["footer"] === true){
			
			$this->tpl->draw("footer");
		
		}
	}


}

