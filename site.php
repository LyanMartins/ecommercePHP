<?php 

use \classe\PageAdmin;

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});