<?php

class ExtendedImport_ImportMappingController extends Pimcore_Controller_Action_Admin
{
    public function importMappingUploadAction() 
    {
        $data = file_get_contents($_FILES["Filedata"]["tmp_name"]);

        $encoding = Pimcore_Tool_Text::detectEncoding($data);
        if ($encoding) {
            $data = iconv($encoding, "UTF-8", $data);
        }

        $importFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_mapping_" . $this->getParam("id");
        file_put_contents($importFile, $data);
        chmod($importFile, 0766);

        $importFileOriginal = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_mapping_" . $this->getParam("id") . "_original";
        file_put_contents($importFileOriginal, $data);
        chmod($importFileOriginal, 0766);

        $this->_helper->json(array(
            "success" => true
        ), false);

        // set content-type to text/html, otherwise (when application/json is sent) chrome will complain in
        // Ext.form.Action.Submit and mark the submission as failed
        $this->getResponse()->setHeader("Content-Type", "text/html");
    }    

    public function importMappingInfoAction() 
    {
        $success = true;
        
        $importObject = new ExtendedImport_Import();
        
        $mappingFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_mapping_" . $this->getParam("id");
        $mappingDialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_mapping_" . $this->getParam("id") . "_original");
        
        $mappingArray = $importObject->createMappingStoreArray($mappingFile, $mappingDialect);
        $firstRowData = $mappingArray[0];
        $data = $mappingArray[1];

        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id");
        $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id") . "_original");

        $mappingArraySource = $importObject->createMappingStoreArray($file, $dialect);
        $firstRowDataSource = $mappingArraySource[0];
    //    $dataSource = $mappingArraySource[1];

        $class = Object_Class::getById($this->getParam("classId"));
        $fields = $class->getFieldDefinitions();

        $availableFields = $importObject->getTargetFields($fields);

        for ($i = 0; $i < count($firstRowDataSource); $i++) {

            $firstRow = $i;
            if (is_array($firstRowDataSource)) {
                $firstRow = $firstRowDataSource[$i];
            }
            $mappedField = null;
            $mappingStoreSource[] = array(
                "source" => $i,
                "firstRow" => $firstRow,
                "target" => $mappedField
            );
        }
   
        $mappingStoreTmp = array();
        for ($i = 1; $i < count($data); $i++) {
            
            $mappingStoreTmp[] = array(
                $firstRowData[0] => $data[$i][0],
                $firstRowData[1] => $data[$i][1],
                $firstRowData[2] => $data[$i][2]
            );
        }
    
        foreach ($availableFields as $key => $value) {
            $avaibleFieldsList[] = $value[0];         
        }
        
        for ($i = 0; $i < count($mappingStoreSource); $i++) {
            for ($j = 0; $j < count($mappingStoreTmp); $j++) {
                if ($mappingStoreSource[$i]["firstRow"] == $mappingStoreTmp[$j]["firstRow"]) {
                    if (in_array($mappingStoreSource[$i]["firstRow"], $avaibleFieldsList)) {
                        
                        $mappingStoreSource[$i]["target"] = $mappingStoreTmp[$j]["target"];     
                    }  
                }
            }
        }

        $this->_helper->json(array(
            "success" => $success,
            "mappingStore" => $mappingStoreSource,
        ));
         
    }
    
    public function autoMappingDataFieldAction() 
    {        
        $success = true;
        
        $importObject = new ExtendedImport_Import();
        
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id");
        $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id") . "_original");
        
        $mappingArraySource = $importObject->createMappingStoreArray($file, $dialect);
        $firstRowData = $mappingArraySource[0];
        // get class data
        $class = Object_Class::getById($this->getParam("classId"));
        $fields = $class->getFieldDefinitions();

        $availableFields = $importObject->getTargetFields($fields);

        $mappingStore = array();
        for ($i = 0; $i < count($firstRowData); $i++) {
        
            $firstRow = $i;
            if (is_array($firstRowData)) {
                $firstRow = $firstRowData[$i];
            }
            
            $mappedField = null;
            for ($j = 0; $j < count($availableFields); $j++) {   
                if ($availableFields[$j][0] == $firstRowData[$i]) {
                    $oldField = $availableFields[$i]; 
                    $availableFields[$i] = $availableFields[$j];
                    $availableFields[$j] = $oldField;
                    
                    $mappedField = $availableFields[$i][0];  
                }               
            }               
            $mappingStore[] = array(
                "source" => $i,
                "firstRow" => $firstRow,
                "target" => $mappedField
            );
        }

        $this->_helper->json(array(
            "success" => $success,
            "mappingStore" => $mappingStore,
        ));    
    }
    
    public function mappingViewAction() 
    {
 
        $classId = Zend_Json::decode($this->getParam("classId"));
        $parentId = Zend_Json::decode($this->getParam("parentId"));
                
        $configParam = PIMCORE_PLUGINS_PATH . "/ExtendedImport/var/mapping/" . $parentId . "_" . $classId . "_" . $this->getUser()->getId() . ":"; // . $name . ".csv";
        
        $files = glob($configParam . "*.csv");

        if ($files) { 
            foreach ($files as $filename) {
                $baseFileName = basename($filename, ".csv");
                $mappingName[] = explode(":", $baseFileName);
            }
        } 
        
        $mappingView[] = array();
        
        for ($i=0; $i < sizeof($mappingName); $i++) { 
             $mappingView[] = array(
                 
                    "mapId"   => $mappingName[$i][0],
                    "title"     => $mappingName[$i][1] 
                );
        }
         return $this->_helper->json(array('mappingView' => $mappingView ));
    }
        
    public function changeMappingFilterAction() 
    {
        $success = true;
        $mapId = Zend_Json::decode($this->getParam("mapId"));
        $title = Zend_Json::decode($this->getParam("title"));
        $key = Zend_Json::decode($this->getParam("keys"));
        $class = Object_Class::getById($this->getParam("classId"));
        
        $importObject = new ExtendedImport_Import();

        $fields = $class->getFieldDefinitions();
        $availableFields = $importObject->getTargetFields($fields);
        
        $mappingFile = PIMCORE_PLUGINS_PATH . "/ExtendedImport/var/mapping/" . $mapId . ":" . $title . ".csv";
        $mappingDialect = Pimcore_Tool_Admin::determineCsvDialect($mappingFile);
        
        $mappingArray = $importObject->createMappingStoreArray($mappingFile, $mappingDialect);
        $firstRowData = $mappingArray[0];
        $data = $mappingArray[1];
        
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id");
        $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_" . $this->getParam("id") . "_original");

        $mappingArraySource = $importObject->createMappingStoreArray($file, $dialect);
        $firstRowDataSource = $mappingArraySource[0];
 //       $dataSource = $mappingArraySource[1];
        
        for ($i = 0; $i < count($firstRowDataSource); $i++) {

            $firstRow = $i;
            if (is_array($firstRowDataSource)) {
                $firstRow = $firstRowDataSource[$i];
            }
            $mappedField = null;
            $mappingStoreSource[] = array(
                "source" => $i,
                "firstRow" => $firstRow,
                "target" => $mappedField
            );
        }
         
        $mappingStoreTmp = array();
        for ($i = 1; $i < count($data); $i++) {
            
            $mappingStoreTmp[] = array(
                $firstRowData[0] => $data[$i][0],
                $firstRowData[1] => $data[$i][1],
                $firstRowData[2] => $data[$i][2]
            );
        }

        foreach ($availableFields as $key => $value) {
            $avaibleFieldsList[] = $value[0];         
        }
        
        for ($i = 0; $i < count($mappingStoreSource); $i++) {
            for ($j = 0; $j < count($mappingStoreTmp); $j++) {
                if ($mappingStoreSource[$i]["firstRow"] == $mappingStoreTmp[$j]["firstRow"]) {
                    if (in_array($mappingStoreSource[$i]["firstRow"], $avaibleFieldsList)) {
                        
                        $mappingStoreSource[$i]["target"] = $mappingStoreTmp[$j]["target"];     
                    }  
                }
            }
        }

        $this->_helper->json(array(
            "success" => $success,
            "mappingStore" => $mappingStoreSource
        ));        
    }   
    
    public function saveMappingDataFieldAction() 
    {
        
        $keys = Zend_Json::decode($this->getParam("keys"));
        $data = Zend_Json::decode($this->getParam("data"));
        $classId = Zend_Json::decode($this->getParam("classId"));
        $parentId = Zend_Json::decode($this->getParam("parentId"));
        
        $name = preg_replace("/[[:blank:]]+/","-", trim(Zend_Json::decode($this->getParam("name"))));

        $csv = implode(";", $keys) . "\r\n";   
        foreach ($data as $columns) {
            foreach ($columns as $key => $value) {

                $value = strip_tags($value);
                $value = str_replace('"', '', $value);
                $value = str_replace("\r", "", $value);
                $value = str_replace("\n", "", $value);
                $o[$key] = '"' . $value . '"';
            }
            $csv .= implode(";", $o) . "\r\n";
        }
                
        $configFile = PIMCORE_PLUGINS_PATH . "/ExtendedImport/var/mapping/" . $parentId . "_" . $classId . "_" . $this->getUser()->getId() . ":" . $name . ".csv";
        
        $configDir = dirname($configFile);
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }
        file_put_contents($configFile, $csv); 
        chmod($configFile, 0766);
        
        $this->_helper->json("Success SAVE mapping records: " . $name);
    }  
    
    public function deleteMappingDataFieldAction() 
    {
        
        $classId = Zend_Json::decode($this->getParam("classId"));
        $parentId = Zend_Json::decode($this->getParam("parentId"));  
        $name = Zend_Json::decode($this->getParam("name"));
       
        if ($name === 0) {
            $name = "";
        }

        $deleteMappingFileName = PIMCORE_PLUGINS_PATH . "/ExtendedImport/var/mapping/" . $parentId . "_" . $classId . "_" . $this->getUser()->getId() . ":" . $name . ".csv";
        $files = glob($deleteMappingFileName);
        
        foreach($files as $file){
          if(is_file($file)) {
             unlink($file);             
          }
        }   
        $this->_helper->json("Success DELETE mapping records: " . $name);

    }

    public function exportMappingDataFieldAction() 
    {
        
        $keys = Zend_Json::decode($this->getParam("keys"));
        $data = Zend_Json::decode($this->getParam("data"));
           
        $csv = implode(";", $keys) . "\r\n";   
        foreach ($data as $columns) {
            foreach ($columns as $key => $value) {

                $value = strip_tags($value);
                $value = str_replace('"', '', $value);
                $value = str_replace("\r", "", $value);
                $value = str_replace("\n", "", $value);

                $o[$key] = '"' . $value . '"';                                             

            }
            $csv .= implode(";", $o) . "\r\n";
        }
        
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=\"mappingexport.csv\"");
        echo $csv;
        exit;
    }
}
    

