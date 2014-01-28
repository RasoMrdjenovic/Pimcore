<?php

class ExtendedImport_ImportController extends Pimcore_Controller_Action_Admin
{

    public function importUploadAction() 
    {
        
        $data = file_get_contents($_FILES["Filedata"]["tmp_name"]);

        $encoding = Pimcore_Tool_Text::detectEncoding($data);
        if ($encoding) {
            $data = iconv($encoding, "UTF-8", $data);
        }

        $importFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id");
        file_put_contents($importFile, $data);
        chmod($importFile, 0766);

        $importFileOriginal = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id") . "_original";
        file_put_contents($importFileOriginal, $data);
        chmod($importFileOriginal, 0766);

        $this->_helper->json(array(
            "success" => true
        ), false);

        // set content-type to text/html, otherwise (when application/json is sent) chrome will complain in
        // Ext.form.Action.Submit and mark the submission as failed
        $this->getResponse()->setHeader("Content-Type", "text/html");
    }
    
    
    public function importGetFileInfoAction()
    {
        $success = true;
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id");
        // determine type
        $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id") . "_original");

        $count = 0;
        if (($handle = fopen($file, "r")) !== false) {
            while (($rowData = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar)) !== false) {
                if ($count == 0) {
                    $firstRowData = $rowData;
                }
                $tmpData = array();
                foreach ($rowData as $key => $value) {
                    $tmpData["field_" . $key] = $value;
                }
                $data[] = $tmpData;
                $cols = count($rowData);

                $count++;

                if ($count > 18) {
                    break;
                }
            }
            fclose($handle);
        }

        // get class data
        $class = Object_Class::getById($this->getParam("classId"));
        $fields = $class->getFieldDefinitions();
        
        $importObject = new ExtendedImport_Import();       
        $availableFields = $importObject->getTargetFields($fields);
        
        $mappingStore = array();
        for ($i = 0; $i < $cols; $i++) {
        
            $firstRow = $i;
            if (is_array($firstRowData)) {
                $firstRow = $firstRowData[$i];
            }
            
            $mappedField = null;
            $mappingStore[] = array(
                "source" => $i,
                "firstRow" => $firstRow,
                "target" => $mappedField
            );
        }

        //How many rows
        $csv = new SplFileObject($file);
        $csv->setFlags(SplFileObject::READ_CSV);
        $csv->setCsvControl($dialect->delimiter, $dialect->quotechar, $dialect->escapechar);
        $rows = 0;
        $nbFields = 0;
        foreach ($csv as $fields) {
            if (0 === $rows) {
                $nbFields = count($fields);
                $rows++;
            } elseif ($nbFields == count($fields)) {
                $rows++;
            }
        }
        
        $this->_helper->json(array(
            "success" => $success,
            "dataPreview" => $data,
            "dataFields" => array_keys($data[0]),
            "targetFields" => $availableFields,
            "mappingStore" => $mappingStore,
            "rows" => $rows,
            "cols" => $cols
        ));
    }

    public function importProcessAction() 
    {

        $success = true;

        $parentId = $this->getParam("parentId");
        $job = $this->getParam("job");
        $id = $this->getParam("id");
        $mappingRaw = Zend_Json::decode($this->getParam("mapping"));
        $class = Object_Class::getById($this->getParam("classId"));
        $skipFirstRow = $this->getParam("skipHeadRow") == "true";
 //      $fields = $class->getFieldDefinitions();
      
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $id;

        // currently only csv supported
        // determine type
        $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $id . "_original");

//        $count = 0;
        if (($handle = fopen($file, "r")) !== false) {
            $data = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar);
        }
        if ($skipFirstRow && $job == 1) {
            //read the next row, we need to skip the head row
            $data = fgetcsv($handle, 0, $dialect->delimiter, $dialect->quotechar, $dialect->escapechar);
        }

        $tmpFile = $file . "_tmp";
        $tmpHandle = fopen($tmpFile, "w+");
        while (!feof($handle)) {
            $buffer = fgets($handle);
            fwrite($tmpHandle, $buffer);
        }

        fclose($handle);
        fclose($tmpHandle);

        unlink($file);
        rename($tmpFile, $file);

        // prepare mapping
        foreach ($mappingRaw as $map) {

            if ($map[0] !== "" && $map[1] && !empty($map[2])) {
                $mapping[$map[2]] = $map[0];
            } else if ($map[1] == "published (system)") {
                $mapping["published"] = $map[0];
            }
        }

        // create new object
        $className = "Object_" . ucfirst($this->getParam("className"));

        $parent = Object_Abstract::getById($this->getParam("parentId"));

        $objectKey = "object_" . $job;
        if ($this->getParam("filename") == "id") {
            $objectKey = null;
        }
        else if ($this->getParam("filename") != "default") {
            $objectKey = Pimcore_File::getValidFilename($data[$this->getParam("filename")]);
        }

        $overwrite = false;
        if ($this->getParam("overwrite") == "true") {
            $overwrite = true;
        }

        if ($parent->isAllowed("create")) {

            $intendedPath = $parent->getFullPath() . "/" . $objectKey;

            if ($overwrite) {
                $object = Object_Abstract::getByPath($intendedPath);
                if (!$object instanceof Object_Concrete) {
                    //create new object
                    $object = new $className();
                } else if (object instanceof Object_Concrete and $object->getO_className() !== $className) {
                    //delete the old object it is of a different class
                    $object->delete();
                    $object = new $className();
                } else if (object instanceof Object_Folder) {
                    //delete the folder
                    $object->delete();
                    $object = new $className();
                } else {
                    //use the existing object
                }
            } else {
                $counter = 1;
                while (Object_Abstract::getByPath($intendedPath) != null) {
                    $objectKey .= "_" . $counter;
                    $intendedPath = $parent->getFullPath() . "/" . $objectKey;
                    $counter++;
                }
                $object = new $className();
            }
            $object->setClassId($this->getParam("classId"));
            $object->setClassName($this->getParam("className"));
            $object->setParentId($this->getParam("parentId"));
            $object->setKey($objectKey);
            $object->setCreationDate(time());
            $object->setUserOwner($this->getUser()->getId());
            $object->setUserModification($this->getUser()->getId());

            if ($data[$mapping["published"]] === "1") {
                $object->setPublished(true);
            } else {
                $object->setPublished(false);
            }

            $languages = Pimcore_Tool::getValidLanguages();      
                 
            foreach ($class->getFieldDefinitions() as $key => $field) {   
// fieldCollections
                if ($field instanceof Object_Class_Data_Fieldcollections) {

                    $collectionName = $field->getName();
                    $collectionArray = array();
                    
                    foreach ($field->getAllowedTypes() as $allowedFieldcollections) { 
                        
                        $collectionClass = "Object_Fieldcollection_Data_" . ucfirst($allowedFieldcollections);
                        $collectionMethods = get_class_methods($collectionClass);

                        foreach($collectionMethods as $collectionMethod) {
                            
                            if (substr($collectionMethod, 0,3) == 'set' && !method_exists("Object_Fieldcollection_Data_Abstract", $collectionMethod) && !method_exists("Pimcore_Model_Abstract", $collectionMethod)){        
                                
                                $collectionsElm = $field->getName() . "_" . $allowedFieldcollections . "_" . lcfirst(substr($collectionMethod, 3));
                                $dataValue = $data[$mapping[$collectionsElm]];
                                
                                if ($dataValue) {  
                                    
                                    $collectionsValues = explode('::', $dataValue);
                                    for ($i = 0; $i < count($collectionsValues); $i++) {
                                        
                                        $collectionArray[$collectionName][$allowedFieldcollections][$i][lcfirst(substr($collectionMethod, 3))] = $collectionsValues[$i];

                                    }
                                }
                            }
                        }                    
                    }
                                        
                    foreach ($collectionArray as $objectCollectionName => $collectionElements) {
                        
                        $fieldCollectionContainer = new Object_Fieldcollection();
                        
                        foreach ($collectionElements as $collectionElementKey => $collectionElement) {
                            
                            $collectionClassName = "Object_Fieldcollection_Data_" . ucfirst($collectionElementKey); 
                            $collectionClass = new $collectionClassName();
                            $collectionTypeElement = $collectionClass->getDefinition()->getFieldDefinitions();

                            foreach ($collectionElement as $collectionFunctions) {

                                $collection = new $collectionClass($object);

                                foreach ($collectionFunctions as $collectionFunction => $collectionValue) {
                                   
                                    if ($collectionTypeElement[$collectionFunction]->fieldtype == "href") {
                                        
                                        $values = explode(":",$collectionValue);
                                        if(count($values)==2){
                                            $type = $values[0];
                                            $path = $values[1];
                                            $value = Element_Service::getElementByPath($type,$path);
                                            
                                            $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                            $collection->$collectionFunctionSet($value);                                            
                                        } 
                                        
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "objects") {
                                        
                                        $values = explode(",", $collectionValue);

                                        $value = array();
                                        foreach ($values as $element) {
                                            if ($el = Object_Abstract::getByPath($element)) {
                                                $value[] = $el;
                                            }
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);
 
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "multihref") {
                                        
                                        $values = explode(",", $collectionValue);
                                        $value = array();
                                        foreach ($values as $element) {

                                            $tokens = explode(":", $element);
                                            if (count($tokens) == 2) {
                                                $type = $tokens[0];
                                                $path = $tokens[1];
                                                $value[] = Element_Service::getElementByPath($type, $path);
                                            } 
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);
 
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "link"
                                            ) {
                                        
                                        
                                        $value = Pimcore_Tool_Serialize::unserialize(base64_decode($collectionValue));
                                        if ($value instanceof Object_Data_Link) {
                                            
                                            $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                            $collection->$collectionFunctionSet($value);
                                        }

                                        
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "hotspotimage") {
                                        
                                        $value = Pimcore_Tool_Serialize::unserialize(base64_decode($collectionValue));
                                        if ($value instanceof Object_Data_Hotspotimage) {
                                            
                                            $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                            $collection->$collectionFunctionSet($value);
                                        }
                                        
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "image") {
                        
                                        if ($element = Asset::getByPath($collectionValue) ) {
                                            $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                            $collection->$collectionFunctionSet($element);
                                        }
 
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "table") {
                                        
                                        $value = Pimcore_Tool_Serialize::unserialize(base64_decode($collectionValue));
                                        if (is_array($value)) {
                                            
                                            $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                            $collection->$collectionFunctionSet($value);
                                        }
                                        
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "dynamicDropdown") {
                                        
                                        $obj = Object_Abstract::getByPath($collectionValue);
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($obj);  

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "date") {
                                        
                                        try { 
                                            $value = new Pimcore_Date($collectionValue);     
                                        } catch (Exception $exc) {
                                            $value = null;
                                            continue;
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "datetime") {
                                        
                                        try { 
                                            $value = new Zend_Date($collectionValue);  
                                        } catch (Exception $exc) {
                                            $value = null;
                                            continue;
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "structuredTable") {

                                        $dataArray = explode("##", $collectionValue);
                                        $i = 0;
                                        $dataTable = array();
                                        foreach($collectionTypeElement[$collectionFunction]->getRows() as $r) {
                                            foreach($collectionTypeElement[$collectionFunction]->getCols() as $c) {
                                                $dataTable[$r['key']][$c['key']] = $dataArray[$i];
                                                $i++;
                                            }
                                        }
                                        $value = new Object_Data_StructuredTable($dataTable);
                                        
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);   
                                                                              
                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "geopoint") {

                                        $coords = explode(",", $collectionValue);
                                        $value = null;
                                        if ($coords[1] && $coords[0]) {

                                            $value = new Object_Data_Geopoint($coords[1], $coords[0]);
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "geopolygon") {
                                        
                                        $rows = explode("|", $collectionValue);
                                        $points = array();
                                        if (is_array($rows)) {
                                            foreach ($rows as $row) {
                                                $coords = explode(";", $row);
                                                $points[] = new  Object_Data_Geopoint($coords[1], $coords[0]);
                                            }
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($points);

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "geobounds") {

                                        $points = explode("|", $collectionValue);
                                        $value = null;
                                        if(is_array($points) and count($points)==2){
                                            $northEast = explode(",",$points[0]);
                                            $southWest = explode(",",$points[1]);
                                            if ($northEast[0] && $northEast[1] && $southWest[0] && $southWest[1]) {
                                                $value = new Object_Data_Geobounds(new Object_Data_Geopoint($northEast[0],$northEast[1]),new Object_Data_Geopoint($southWest[0],$southWest[1]));
                                            }
                                        }
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet($value);

                                    } else if ($collectionTypeElement[$collectionFunction]->fieldtype == "multiselect"
                                            || $collectionTypeElement[$collectionFunction]->fieldtype == "dynamicDropdownMultiple" 
                                            || $collectionTypeElement[$collectionFunction]->fieldtype == "languagemultiselect"                                           
                                            || $collectionTypeElement[$collectionFunction]->fieldtype == "countrymultiselect") {
                                                                  
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);
                                        $collectionValue = explode(",", $collectionValue);     
                                        $collection->$collectionFunctionSet($collectionValue);
                                        
                                    } else {
                                        
                                        $collectionFunctionSet = set . ucfirst($collectionFunction);     
                                        $collection->$collectionFunctionSet(strval($collectionValue));                                 
                                    }
                                }
                                $fieldCollectionContainer->add($collection);
                            }                    
                        }

                        $objectFunctionCollectionName = set . ucfirst($objectCollectionName);
                        $object->$objectFunctionCollectionName($fieldCollectionContainer);
                    }  
                } else             
// objectbricks                          
                if ($field instanceof Object_Class_Data_Objectbricks) { 

                    $bricksName = $field->getName();
                    $bricksArray = array();
                    
                    foreach ($field->getAllowedTypes() as $allowedBricks) { 
                        
                        $brickClass = "Object_Objectbrick_Data_" . ucfirst($allowedBricks);
                        $brickMethods = get_class_methods($brickClass);

                        foreach($brickMethods as $brickMethod) {                         
                            if (substr($brickMethod, 0,3) == 'set' && !method_exists("Object_Objectbrick_Data_Abstract", $brickMethod) && !method_exists("Pimcore_Model_Abstract", $brickMethod)) {

                                    $bricksElm =  $field->getName() . "_" . $allowedBricks . "_" . lcfirst(substr($brickMethod, 3));
                                    $dataValue = $data[$mapping[$bricksElm]];

                                if ($dataValue) {
                                    $bricksArray[$bricksName][$allowedBricks][lcfirst(substr($brickMethod, 3))] = $dataValue;
                                }                              
                            }
                        }
                    }
                                       
                    foreach ($bricksArray as $objectBricksName => $bricksElements) {
                        
                        foreach ($bricksElements as $bricksElementKey => $bricksFunctions) {

                            $bricksClassName = "Object_Objectbrick_Data_" . ucfirst($bricksElementKey); 
                            $bricksClass = new $bricksClassName($object);

                            $bricksTypeElement = $bricksClass->getDefinition()->getFieldDefinitions();

                            $bricks = new $bricksClass($object);

                            foreach ($bricksFunctions as $bricksFunction => $bricksValue) {

                                if ($bricksTypeElement[$bricksFunction]->fieldtype == "href") {

                                    $values = explode(":",$bricksValue);
                                    if(count($values)==2){
                                        $type = $values[0];
                                        $path = $values[1];
                                        $value = Element_Service::getElementByPath($type,$path);

                                        $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                        $bricks->$bricksFunctionSet($value);                                            
                                    } 

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "objects") {

                                    $values = explode(",", $bricksValue);

                                    $value = array();
                                    foreach ($values as $element) {
                                        if ($el = Object_Abstract::getByPath($element)) {
                                            $value[] = $el;
                                        }
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "multihref") {

                                    $values = explode(",", $bricksValue);
                                    $value = array();
                                    foreach ($values as $element) {

                                        $tokens = explode(":", $element);
                                        if (count($tokens) == 2) {
                                            $type = $tokens[0];
                                            $path = $tokens[1];
                                            $value[] = Element_Service::getElementByPath($type, $path);
                                        } 
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "link") {


                                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($bricksValue));
                                    if ($value instanceof Object_Data_Link) {

                                        $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                        $bricks->$bricksFunctionSet($value);
                                    }


                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "hotspotimage") {

                                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($bricksValue));
                                    if ($value instanceof Object_Data_Hotspotimage) {

                                        $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                        $bricks->$bricksFunctionSet($value);
                                    }

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "image") {

                                    if ($element = Asset::getByPath($bricksValue) ) {
                                        $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                        $bricks->$bricksFunctionSet($element);
                                    }

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "table") {

                                    $value = Pimcore_Tool_Serialize::unserialize(base64_decode($bricksValue));
                                    if (is_array($value)) {

                                        $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                        $bricks->$bricksFunctionSet($value);
                                    }

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "dynamicDropdown") {

                                    $obj = Object_Abstract::getByPath($bricksValue);
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($obj);  

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "date") {

                                    try { 
                                        $value = new Pimcore_Date($bricksValue);     
                                    } catch (Exception $exc) {
                                        $value = null;
                                        continue;
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "datetime") {

                                    try { 
                                        $value = new Zend_Date($bricksValue);  
                                    } catch (Exception $exc) {
                                        $value = null;
                                        continue;
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "structuredTable") {

                                    $dataArray = explode("##", $bricksValue);
                                    $i = 0;
                                    $dataTable = array();
                                    foreach($bricksTypeElement[$bricksFunction]->getRows() as $r) {
                                        foreach($bricksTypeElement[$bricksFunction]->getCols() as $c) {
                                            $dataTable[$r['key']][$c['key']] = $dataArray[$i];
                                            $i++;
                                        }
                                    }
                                    $value = new Object_Data_StructuredTable($dataTable);

                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);   

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "geopoint") {

                                    $coords = explode(",", $bricksValue);
                                    $value = null;
                                    if ($coords[1] && $coords[0]) {

                                        $value = new Object_Data_Geopoint($coords[1], $coords[0]);
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "geopolygon") {

                                    $rows = explode("|", $bricksValue);
                                    $points = array();
                                    if (is_array($rows)) {
                                        foreach ($rows as $row) {
                                            $coords = explode(";", $row);
                                            $points[] = new  Object_Data_Geopoint($coords[1], $coords[0]);
                                        }
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($points);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "geobounds") {

                                    $points = explode("|", $bricksValue);
                                    $value = null;
                                    if(is_array($points) and count($points)==2){
                                        $northEast = explode(",",$points[0]);
                                        $southWest = explode(",",$points[1]);
                                        if ($northEast[0] && $northEast[1] && $southWest[0] && $southWest[1]) {
                                            $value = new Object_Data_Geobounds(new Object_Data_Geopoint($northEast[0],$northEast[1]),new Object_Data_Geopoint($southWest[0],$southWest[1]));
                                        }
                                    }
                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet($value);

                                } else if ($bricksTypeElement[$bricksFunction]->fieldtype == "multiselect"
                                        || $bricksTypeElement[$bricksFunction]->fieldtype == "dynamicDropdownMultiple" 
                                        || $bricksTypeElement[$bricksFunction]->fieldtype == "languagemultiselect"                                           
                                        || $bricksTypeElement[$bricksFunction]->fieldtype == "countrymultiselect") {

                                    $bricksFunctionSet = set . ucfirst($bricksFunction);
                                    $bricksValue = explode(",", $bricksValue);     
                                    $bricks->$bricksFunctionSet($bricksValue);

                                } else {

                                    $bricksFunctionSet = set . ucfirst($bricksFunction);     
                                    $bricks->$bricksFunctionSet(strval($bricksValue));                                 
                                }
                            }

                            $objectFunctionBricksName = get . ucfirst($objectBricksName);
                            $brickFunctionBricksName = set . ucfirst($bricksElementKey);
                            
                            $object->$objectFunctionBricksName()->$brickFunctionBricksName($bricks);                          
                        }
                    }                                         
                } else         
// localizedfields            
                if ($field instanceof Object_Class_Data_Localizedfields) { 
                    
                    foreach($languages as $language) {
                        
                        if ($field->hasChilds()) {
                            
                            $importObject = new ExtendedImport_Import();
                            $importObject->createLocalizedArrayImport($field->getChilds(), $object, $language, $data, $mapping);  
                        }
                    }
                } else {

                        $value = $data[$mapping[$key]];
                        if (array_key_exists($key, $mapping) and  $value != null) {
                            // data mapping
                            $value = $field->getFromCsvImport($value);

                            if ($value !== null) {
                                $object->setValue($key, $value);   
                            }
                        }
                    }             
                }
            
            try {
                $object->save();
                $this->_helper->json(array("success" => true));
            } catch (Exception $e) {
                $this->_helper->json(array("success" => false, "message" => $object->getKey() . " - " . $e->getMessage()));
            }
        }

        $this->_helper->json(array("success" => $success));
    }
  
}
