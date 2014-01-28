<?php  

// Set error reporting to ignore notices  

error_reporting(E_ALL ^ E_NOTICE);  

  

// Include XML_Serializer  

require_once 'XML/Serializer.php';  

function __autoload($class_name) {
    include "generated/" . $class_name . '.php';
}


  
$bmecat = new BMECAT();

$header = new HEADER();

$bmecat->HEADER = $header;
$catalog = new CATALOG();
$lang = new LANGUAGE(); 
$lang->default = "ENG";
$catalog->LANGUAGE = $lang;
$bmecat->HEADER->CATALOG = $catalog;


$serializer_options = array (  

    'addDecl' => TRUE,  

    'encoding' => 'ISO-8859-1',  

    'indent' => '  ',  

    
    'rootName'  => 'BMECAT',  
    "rootAttributes"  => array("version" => "2005"),
    'defaultTagName' => 'HEADER'

    
);  

  

// Instantiate the serializer with the options  

$Serializer = &new XML_Serializer($serializer_options);  

  

// Serialize the data structure  

$status = $Serializer->serialize($bmecat);  

  

// Check whether serialization worked  

if (PEAR::isError($status)) {  

    die($status->getMessage());  

}  

  

// Display the XML document  

header('Content-type: text/xml');  

echo $Serializer->getSerializedData();  

?>