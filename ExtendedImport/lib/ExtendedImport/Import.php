<?php

class ExtendedImport_Import
{

    public function getTargetFields($fields)
    {
         
        $availableFields = array();
        $languages = Pimcore_Tool::getValidLanguages();
        
        // "password" - have a sys bug! You haven't save password field in fieldcollections !
        $supportedFieldTypes = array("checkbox", "country", "date", "datetime", "href", "image", "input", "language", "table", 
            "multiselect", "numeric", "password", "select", "slider", "textarea", "wysiwyg", "objects", "multihref", "geopoint", 
            "geopolygon", "geobounds", "link", "user", "email", "gender", "firstname", "lastname", "newsletterActive", 
            "newsletterConfirmed", "languagemultiselect", "countrymultiselect", "structuredTable", "hotspotimage");    
        array_push($supportedFieldTypes, "keyValue"); 
        array_push($supportedFieldTypes, "dynamicDropdown", "dynamicDropdownMultiple");
        array_push($supportedFieldTypes, "localizedfields", "objectbricks", "fieldcollections");
        
        foreach ($fields as $key => $field) {

            $title = $field->getName();
            if (method_exists($field, "getTitle")) {
                if ($field->getTitle()) {
                    $title = $field->getTitle();
                }
            }   
            if (in_array($field->getFieldType(), $supportedFieldTypes)) {               
//  fieldcollections
                if ($field instanceof Object_Class_Data_Fieldcollections) {

                    foreach ($field->getAllowedTypes() as $allowedCollection) {
                        
                        $collectionsClass = "Object_Fieldcollection_Data_" . ucfirst($allowedCollection);
                        $collectionsMethods = get_class_methods($collectionsClass);

                        foreach($collectionsMethods as $collectionsMethod){
                            if (substr($collectionsMethod, 0,3) == 'set' && !method_exists("Object_Fieldcollection_Data_Abstract", $collectionsMethod) && !method_exists("Pimcore_Model_Abstract", $collectionsMethod)){

                                $availableFields[] = array($field->getName() . "_" . $allowedCollection . "_" . lcfirst(substr($collectionsMethod, 3)) , $field->getName() . " - " .lcfirst(substr($collectionsMethod, 3)) . "( " . $allowedCollection . " )");                                                          
                            }
                        }                         
                    }
                }
//  objectbricks
                if ($field instanceof Object_Class_Data_Objectbricks) {

                    foreach ($field->getAllowedTypes() as $allowedBricks) {

                        $brickClass = "Object_Objectbrick_Data_" . ucfirst($allowedBricks);
                        $brickMethods = get_class_methods($brickClass);

                        foreach($brickMethods as $brickmethod){
                            if (substr($brickmethod, 0,3) == 'set' && !method_exists("Object_Objectbrick_Data_Abstract", $brickmethod) && !method_exists("Pimcore_Model_Abstract", $brickmethod)){

                                $availableFields[] = array($field->getName() . "_" . $allowedBricks . "_" . lcfirst(substr($brickmethod, 3)) , $field->getName() . " - " . lcfirst(substr($brickmethod, 3)) . "( " . $allowedBricks . " )");                                 
                            }
                        }   
                     }  
                 }                               
//  localizedfields                
                if ($field instanceof Object_Class_Data_Localizedfields) {

                    $tmpArray = array();
                    
                    foreach($languages as $language) { 
                        if ($field->hasChilds()) {
                            $tmpArray[$field->getName() . '-' . $language . "-tmp"] = $this->createLocalizedArrayMapping($field->getChilds(), array(), $language, $supportedFieldTypes);  
                        }                     
                    }
                    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($tmpArray));
                    
                    $tmpArray1 = array();
                    foreach($it as $key => $value) {
                        if($key == 0) {                        
                            $tmpArray1[$key] = $value;   
                        } else if($key == 1) {
                            $tmpArray1[$key] = $value;
                            $availableFields[] = $tmpArray1; 
                        }                       
                    }                       
                } else {                  
                    $availableFields[] = array($field->getName(), $title . "(" . $field->getFieldType() . ")");                    
                }                                                          
            }
        }      
        return $availableFields;
    }
    
    public function createLocalizedArrayMapping($def, $loc, $language, $supportedFieldTypes) 
    {
        foreach($def as $key => $child) {
            
            if ($child instanceof Object_Class_Layout) {
                if ($child->hasChilds()) { 
                    $loc[$child->getName() . '-' . $language . "-tmp"] = $this->createLocalizedArrayMapping($child->getChilds(), $loc[$child->getName() . '-' . $language], $language, $supportedFieldTypes);
                }
            }
            else if ($child instanceof Object_Class_Data && in_array($child->getFieldtype(), $supportedFieldTypes)) {

                $loc[$child->getName() . '-' . $language] = array($child->getName() . '-' . $language, $child->getName() . '-' . $language . "(" . $child->getFieldType() . ")");
            }                
        }
        return $loc; 
    }
    
    public function createLocalizedArrayImport($def, $object, $language, $data, $mapping) 
    {
        foreach($def as $key => $child) {
            if ($child instanceof Object_Class_Layout) {
                if ($child->hasChilds()) {
                    $this->createLocalizedArrayImport($child->getChilds(), $object, $language, $data, $mapping);  
                }
            } else if ($child instanceof Object_Class_Data) {

                $srcValue = $data[$mapping[$child->name . "-". $language]];
                $value = null;
                
                if ($child->fieldtype == "href") {

                    $values = explode(":", $srcValue);
                    if(count($values)==2){
                        $type = $values[0];
                        $path = $values[1];
                        $value = Element_Service::getElementByPath($type,$path);                   
                    } 
                    
                } else if ($child->fieldtype == "objects") {
                                        
                    $values = explode(",", $srcValue);

                    $value = array();
                    foreach ($values as $element) {
                        if ($el = Object_Abstract::getByPath($element)) {
                            $value[] = $el;
                        }
                    }

                } else if ($child->fieldtype == "multihref") {

                    $values = explode(",", $srcValue);
                    $value = array();
                    foreach ($values as $element) {

                        $tokens = explode(":", $element);
                        if (count($tokens) == 2) {
                            $type = $tokens[0];
                            $path = $tokens[1];
                            $value[] = Element_Service::getElementByPath($type, $path);
                        } 
                    }

                } else if ($child->fieldtype == "link") {

                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($srcValue));
                   
                } else if ($child->fieldtype == "hotspotimage") {

                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($srcValue));

                } else if ($child->fieldtype == "image") {
 
                    $value = Asset::getByPath($srcValue);

                } else if ($child->fieldtype == "table") {

                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($srcValue));

                } else if ($child->fieldtype == "dynamicDropdown") {

                    $value = Object_Abstract::getByPath($srcValue);

                } else if ($child->fieldtype == "date") {

                    try { 
                        $value = new Pimcore_Date($srcValue);     
                    } catch (Exception $exc) {
                        $value = null;
                        continue;
                    }

                } else if ($child->fieldtype == "datetime") {

                    try { 
                        $value = new Zend_Date($srcValue);  
                    } catch (Exception $exc) {
                        $value = null;
                        continue;
                    }
                    
                } else if ($child->fieldtype == "structuredTable") {

                    $dataArray = explode("##", $srcValue);
                    $i = 0;
                    $dataTable = array();
                    foreach($child->getRows() as $r) {
                        foreach($child->getCols() as $c) {
                            $dataTable[$r['key']][$c['key']] = $dataArray[$i];
                            $i++;
                        }
                    }
                    $value = new Object_Data_StructuredTable($dataTable);

                } else if ($child->fieldtype == "geopoint") {

                    $coords = explode(",", $srcValue);
                    $value = null;
                    if ($coords[1] && $coords[0]) {
                        $value = new Object_Data_Geopoint($coords[1], $coords[0]);
                    }

                } else if ($child->fieldtype == "geopolygon") {

                    $rows = explode("|", $srcValue);
                    $value = array();
                    if (is_array($rows)) {
                        foreach ($rows as $row) {
                            $coords = explode(";", $row);
                            $value[] = new  Object_Data_Geopoint($coords[1], $coords[0]);
                        }
                    }

                } else if ($child->fieldtype == "geobounds") {

                    $points = explode("|", $srcValue);
                    $value = null;
                    if(is_array($points) and count($points)==2){
                        $northEast = explode(",",$points[0]);
                        $southWest = explode(",",$points[1]);
                        if ($northEast[0] && $northEast[1] && $southWest[0] && $southWest[1]) {
                            $value = new Object_Data_Geobounds(new Object_Data_Geopoint($northEast[0],$northEast[1]),new Object_Data_Geopoint($southWest[0],$southWest[1]));
                        }
                    }

                } else if ($child->fieldtype == "multiselect"
                        || $child->fieldtype == "dynamicDropdownMultiple" 
                        || $child->fieldtype == "languagemultiselect"                                           
                        || $child->fieldtype == "countrymultiselect") {

                    $value = explode(",", $srcValue);     

                } else {

                    $value = strval($srcValue);
                }

                $object->setValue($child->name, $value, $language);             
            }                
        }
    }
    
    public function createMappingStoreArray($file, $dialect) 
    {
        
        $count = 0;
        if (($handle = fopen($file, "r")) !== false) {
            while (($rowData = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar)) !== false) {
                if ($count == 0) {
                    $firstRowData = $rowData;
                }
                $data[] = $rowData;
                $count++;
            }
            fclose($handle);
        }
        
        return array($firstRowData, $data);
    }


}

?>
