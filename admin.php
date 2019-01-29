<?php 

use \classe\PageAdmin;
use \classe\Model\User;

$app->get('/adm',function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");


});

$app->get('/adm/login',function(){

	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");
});

$app->post('/adm/login',function(){

	User::login($_POST['login'],$_POST['password']);
	header("Location: /ecommerce/adm");
	exit();

});
$app->get('/adm/logout', function(){

	User::logout();
	header("Location: /ecommerce/adm/login");
	exit();

});


$app->get("/admin/forgot",function(){

	$page =  new PageAdmin([
		"header" => false,
		"footer" => false,
	]);

	$page->setTpl("forgot");

});

$app->post("/admin/forgot",function(){


	$users=User::getForgot($_POST['email']);

	header("Location: /admin/forgot/sent");
	exit;

});
$app->get("/admin/forgot/sent",function(){

	$page =  new PageAdmin([
		"header" => false,
		"footer" => false,
	]);

	$page->setTpl("forgot-sent");
 

});