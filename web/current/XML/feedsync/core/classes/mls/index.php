<?php
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

require_once("vendor/autoload.php");

$log = new \Monolog\Logger('PHRETS');
$log->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

$config = new \PHRETS\Configuration;
// $config->setLoginUrl('http://rets.mfrmls.com/contact/rets/login')
//     ->setUsername('RETS1103')
//     ->setPassword('wpWBb!!321')
//     ->setRetsVersion('1.7.2');

// $config->setLoginUrl('http://rets.mfrmls.com/contact/rets/login')
//     ->setUsername('RETS1166')
//     ->setPassword('mERVbArretT!32')
//     ->setRetsVersion('1.5')
//     ->setOption('use_post_method', true); // boolean

$config->setLoginUrl('http://rets.las.mlsmatrix.com/rets/login.ashx')
    ->setUsername('dscott')
    ->setPassword('bhhs')
    ->setRetsVersion('1.5');
    //->setOption('use_post_method', true); // boolean

$rets = new \PHRETS\Session($config);
//var_dump($rets); die;
$rets->setLogger($log);

$connect = $rets->Login();
// echo "<pre>";
// print_r($connect); die;
$system = $rets->GetSystemMetadata();
//var_dump($system); die;
//echo "Server Name: " . $system->getSystemDescription(); 


$resources = $system->getResources();
// echo "<pre>";
// print_r($resources); die;
$classes = $resources->first()->getClasses();

// echo "<pre>";
// print_r($classes); die;

$class_metadata = $rets->GetClassesMetadata('Property');

// echo "<pre>";
// print_r($class_metadata); die;
// var_dump($classes->first());
// 


$methods = $rets->GetObjectMetadata('Property','Photo');
// echo "<pre>";
// print_r($methods);
// echo "</pre>"; die;

$objects = $rets->GetObject('Property', 'Photo', '1329605', '*', 0);

foreach($objects as $object) {
	file_put_contents("photos/{$object->getContentId()}-{$object->getObjectId()}.jpg",  $object->getContent());
}
// echo "<pre>";
// print_r($objects);
// echo "</pre>"; die;


$fields = $rets->GetTableMetadata('Agent', 'Agent');
// echo "<pre>";
// print_r($fields);
// echo "</pre>"; die;

// die;

$array = array(
	'QueryType' 	=> 'DMQL2',
    'Count' 		=> 1, // count and records
    'Format' 		=> 'COMPACT-DECODED',
    'Limit' 		=> 1,
    'StandardNames' => 0
);
$results = $rets->Search('Property','Listing', '(ListAgent_MUI = 9629564),(BedsTotal = 3+)', $array );

var_dump($results); die;
// echo "<pre>";
// print_r($results->toArray() ); die;
echo $results->getTotalResultsCount(); die;
include('class-array-to-xml.php');
$xml = Array2XML::createXML('propertyList', array('property'	=>	$results->toArray()) );
header("Content-type: text/xml");
echo $xml->saveXML();
//var_dump($results); die;
// foreach ($results as $r) {
//     echo "<pre>";
//     print_r($r);
//     echo "</pre>";
// }