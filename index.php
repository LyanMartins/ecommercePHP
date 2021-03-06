<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \classe\DB\Sql;
use \classe\Page;
use \classe\PageAdmin;
use \classe\Model\User;
use \classe\Model\Category;

$app = new Slim();


$app->config('debug', true);

require_once("site.php");
require_once("admin.php");
require_once("admin-user.php");
require_once("admin-categories.php");
require_once("admin-products.php");


$app->run();



?>