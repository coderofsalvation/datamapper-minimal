<?php 

require_once __DIR__ . '/../src/DataMapper.php'; // Autoload files using Composer autoload

use coderofsalvation\DataMapper;

$datamapper = new DataMapper();

// create a fake magento product
class magentoProduct {
  public $EntityId = 123;
  public function getTypeId(){ return "normal"; }
}
$magentoProduct = new magentoProduct();

// create a fake woocommerce product 
$wcProduct = (object)array(
  "ArticleID" => 454, 
  "Category"  => "notnormal"
);

// create middleware product
$ourProduct = (object)array(
  "Id"   => false,
  "Type" => false, 
  "Unmapped field" => false
);

// create mapping tables to define transformation
$datamapper->addMapping("magento", array(
  array( "source" => "EntityId", "destination" => "Id",   "transform" => function($s,&$d){ return $s->EntityId;    } ), 
  array( "source" => "Ptype",    "destination" => "Type", "transform" => function($s,&$d){ return $s->getTypeId(); } ), 
));

// create mapping tables to define transformation
$datamapper->addMapping("woocommerce", array(
  array( "source" => "ArticleID", "destination" => "Id",   "transform" => function($s,&$d){ return $s->ArticleID;  } ), 
  array( "source" => "Category",  "destination" => "Type", "transform" => function($s,&$d){ return $s->Category;   } ), 
));

// lets see some debugging output 
DataMapper::$onProgress = function($type,$value){ print($type."> ".$value."\n"); };

// lets process items with different layouts and unite them
$products = array();
$products[] = $datamapper->map("magento",     $magentoProduct, $ourProduct );
$products[] = $datamapper->map("woocommerce", $wcProduct, $ourProduct );

print_r($products);

