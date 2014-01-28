<?php

class ExtendedImport_Class_Data_Objectbricks extends ExtendedImport_Class_Data
{
    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "objectbricks";

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "Object_Objectbrick";

    /**
     * @var array
     */
    public $allowedTypes = array();

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForEditmode($data, $object = null, $objectFromVersion = null)
    {
        $editmodeData = array();

        if ($data instanceof Object_Objectbrick) {
            $getters = $data->getBrickGetters();

            foreach ($getters as $getter) {
                $editmodeData[] = $this->doGetDataForEditmode($data, $getter, $objectFromVersion);
            }
        }

        return $editmodeData;
    }

    private function doGetDataForEditmode($data, $getter, $objectFromVersion, $level = 0) {
//        p_r($data); die();
        $parent = Object_Service::hasInheritableParentObject($data->getObject());
        $item = $data->$getter();
        if(!$item && !empty($parent)) {
            $data = $parent->{"get" . ucfirst($this->getName())}();
            return $this->doGetDataForEditmode($data, $getter, $objectFromVersion, $level + 1);
        }

        if (!$item instanceof Object_Objectbrick_Data_Abstract) {
            return null;
        }

        try {
            $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
        } catch (Exception $e) {
            return null;
        }

        $brickData = array();
        $brickMetaData = array();

        $inherited = false;
        foreach ($collectionDef->getFieldDefinitions() as $fd) {
            $fieldData = $this->getDataForField($item, $fd->getName(), $fd, $level, $data->getObject(), $getter, $objectFromVersion); //$fd->getDataForEditmode($item->{$fd->getName()});
            $brickData[$fd->getName()] = $fieldData->objectData;
            $brickMetaData[$fd->getName()] = $fieldData->metaData;
            if($fieldData->metaData['inherited'] == true) {
                $inherited = true;
            }
        }

        $editmodeDataItem = array(
            "data" => $brickData,
            "type" => $item->getType(),
            "metaData" => $brickMetaData,
            "inherited" => $inherited
        );
        return $editmodeDataItem;
    }


    /**
     * gets recursively attribute data from parent and fills objectData and metaData
     *
     * @param  $object
     * @param  $key
     * @param  $fielddefinition
     * @return void
     */
    private function getDataForField($item, $key, $fielddefinition, $level, $baseObject, $getter, $objectFromVersion) {
        $result = new stdClass();
        $parent = Object_Service::hasInheritableParentObject($baseObject);
        $valueGetter = "get" . ucfirst($key);

        // relations but not for objectsMetadata, because they have additional data which cannot be loaded directly from the DB
        if (!$objectFromVersion && method_exists($fielddefinition, "getLazyLoading") and $fielddefinition->getLazyLoading() and !$fielddefinition instanceof Object_Class_Data_ObjectsMetadata) {

            //lazy loading data is fetched from DB differently, so that not every relation object is instantiated
            if ($fielddefinition->isRemoteOwner()) {
                $refKey = $fielddefinition->getOwnerFieldName();
                $refId = $fielddefinition->getOwnerClassId();
            } else {
                $refKey = $key;
            }

            $relations = $item->getRelationData($refKey, !$fielddefinition->isRemoteOwner(), $refId);
            if(empty($relations) && !empty($parent)) {
                $parentItem = $parent->{"get" . ucfirst($this->getName())}();
                if(!empty($parentItem)) {
                    $parentItem = $parentItem->$getter();
                    if($parentItem) {
                        return $this->getDataForField($parentItem, $key, $fielddefinition, $level + 1, $parent, $getter, $objectFromVersion);
                    }
                }
            }
            $data = array();

            if ($fielddefinition instanceof Object_Class_Data_Href) {
                $data = $relations[0];
            } else {
                foreach ($relations as $rel) {
                    if ($fielddefinition instanceof Object_Class_Data_Objects) {
                        $data[] = array($rel["id"], $rel["path"], $rel["subtype"]);
                    } else {
                        $data[] = array($rel["id"], $rel["path"], $rel["type"], $rel["subtype"]);
                    }
                }
            }
            $result->objectData = $data;
            $result->metaData['objectid'] = $baseObject->getId();
            $result->metaData['inherited'] = $level != 0;

        } else {
            $value = null;
            if(!empty($item)) {
                $value = $fielddefinition->getDataForEditmode($item->$valueGetter(), $baseObject);
            }
            if(empty($value) && !empty($parent)) {
                $parentItem = $parent->{"get" . ucfirst($this->getName())}()->$getter();
                if(!empty($parentItem)) {
                    return $this->getDataForField($parentItem, $key, $fielddefinition, $level + 1, $parent, $getter, $objectFromVersion);
                }
            }
            $result->objectData = $value;
            $result->metaData['objectid'] = $baseObject->getId();
            $result->metaData['inherited'] = $level != 0;
        }
        return $result;
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataFromEditmode($data, $object = null)
    {

        $getter = "get" . ucfirst($this->getName());


        $container = $object->$getter(); //$this->getObject()->$getter();
        if(empty($container)) {
            $className = $object->getClass()->getName(); //$this->getObject()->getClass()->getName();

            $containerClass = "Object_" . ucfirst($className) . "_" . ucfirst($this->getName());
            $container = new $containerClass($object, $this->getName()); //$this->getObject(), $this->getName());
        }

        if (is_array($data)) {
            foreach ($data as $collectionRaw) {

                $collectionData = array();
                $collectionDef = Object_Objectbrick_Definition::getByKey($collectionRaw["type"]);

                $getter = "get" . ucfirst($collectionRaw["type"]);
                $brick = $container->$getter();
                if(empty($brick)) {
                    $brickClass = "Object_Objectbrick_Data_" . ucfirst($collectionRaw["type"]);
                    $brick = new $brickClass($object); //$this->getObject());
                }


                if($collectionRaw["data"] == "deleted") {
                    $brick->setDoDelete(true);
                } else {
                    foreach ($collectionDef->getFieldDefinitions() as $fd) {
                        if (array_key_exists($fd->getName(), $collectionRaw["data"])) {
                            $collectionData[$fd->getName()] = $fd->getDataFromEditmode($collectionRaw["data"][$fd->getName()], $object);
                        }
                    }
                    $brick->setValues($collectionData);
                    $brick->setFieldname($this->getName());
                }

                $setter = "set" . ucfirst($collectionRaw["type"]);
                $container->$setter($brick);
            }
        }

        return $container;
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param string $data
     * @return string
     */
    public function getVersionPreview($data)
    {
        return "BRICKS";
    }

    /**
     * @param Object_Abstract $object
     * @return string
     */
    public function getForCsvExport($object)
    {
        $ob = array();
        $supportedFieldTypes = array("checkbox", "country", "language", "time", "input", "select", "textarea", "wysiwyg", 
            "numeric", "date", "datetime",  "multiselect", "slider", "href", "link", "multihref", "objects",
            "table", "structuredTable", "geopoint", "geopolygon", "geobounds","languagemultiselect", "countrymultiselect", 
            "image", "hotspotimage", "newsletterActive", "newsletterConfirmed");
        array_push($supportedFieldTypes, "keyValue");
        array_push($supportedFieldTypes, "dynamicDropdown", "dynamicDropdownMultiple");        
             
        $fieldName = $this->getName();
        $method = get . ucfirst($fieldName);
        
        if ($object->$method()) {
             $items = $object->$method();
        }
        
        $colElems = $items->items;    

         if(count($colElems) > 0) {
             
            foreach($colElems as $key => $colElem){       
        

                $collectionClassName = "Object_Objectbrick_Data_" . ucfirst($colElem->type); 
                $collectionClass = new $collectionClassName($object);
                $collectionTypeElement = $collectionClass->getDefinition()->getFieldDefinitions();       

   
                   //    foreach ($collectTypes as $collectType) {
            
                        //    $class = "Object_Objectbrick_Data_" . ucfirst($collectType);
                        $collectionMethods = get_class_methods($collectionClassName);
                        $fields = array();
                        foreach($collectionMethods as $collectionMethod){
                            if (substr($collectionMethod, 0,3) == 'get' && !method_exists("Object_Objectbrick_Data_Abstract", $collectionMethod) && !method_exists("Pimcore_Model_Abstract", $collectionMethod)){ 
                                $fields[]= lcfirst(substr($collectionMethod, 3)); 
                            }
                        }
                                
                        foreach($fields as $field){
                            
                        if (in_array($collectionTypeElement[$field]->fieldtype, $supportedFieldTypes)) {
                            
                            $valueString = null;
                            switch ($collectionTypeElement[$field]->fieldtype) {
                                case "table":
                                    if (is_array($colElem->$field)) {
                                        $valueString = base64_encode(Pimcore_Tool_Serialize::serialize($colElem->$field));
                                    }
                                    break;    
                                case "link":
                                    if ($colElem->$field instanceof Object_Data_Link) {                               
                                        $valueString = base64_encode(Pimcore_Tool_Serialize::serialize($colElem->$field));
                                    }
                                    break;
                                case "href":
                                    if ($colElem->$field instanceof Element_Interface) {
                                        $valueString = Element_Service::getType($colElem->$field) . ":" . $colElem->$field->getFullPath();
                                    }
                                    break;
                                case "image":
                                    if ($colElem->$field instanceof Element_Interface) {
                                        $valueString = $colElem->$field->getFullPath();
                                    }
                                    break;     
                                case "hotspotimage":
                                    if ($colElem->$field instanceof Object_Data_Hotspotimage) {
                                       $valueString = base64_encode(Pimcore_Tool_Serialize::serialize($colElem->$field));
                                    }
                                    break;                                
                                case "multihref":
                                    if (is_array($colElem->$field)) {
                                    $paths = array();
                                    foreach ($colElem->$field as $eo) {
                                        if ($eo instanceof Element_Interface) {
                                            $paths[] = Element_Service::getType($eo) . ":" . $eo->getFullPath();
                                        }
                                    }
                                    $valueString =  implode(",", $paths);
                                    }
                                    break;
                                case "multiselect":
                                case "languagemultiselect":
                                case "countrymultiselect":
                                    if (is_array($colElem->$field)) {
                                        $valueString = implode(",", $colElem->$field);
                                    } else {
                                        $valueString = "";
                                    }
                                    break; 
                                case "structuredTable":     
                                    $value = $colElem->$field;
                                    if ($value instanceof Object_Data_StructuredTable) {
                                        $valueString = "";
                                        $dataArray = $value->getData();

                                        foreach($dataArray as $key1 => $row) {
                                            foreach($row as $key2 => $cell) {
                                                $valueString .= $dataArray[$key1][$key2] . "##";
                                            }
                                        }                                   
                                    } else {
                                        $valueString = "";
                                    }
                                    break; 
                                case "objects":
                                     if (is_array($colElem->$field)) {
                                        $paths = array();
                                        foreach ($colElem->$field as $eo) {
                                            if ($eo instanceof Element_Interface) {
                                                $paths[] = $eo->getFullPath();
                                            }
                                        }                                
                                        $valueString =  implode(",", $paths); 
                                    }  
                                    break;
                                case "dynamicDropdownMultiple":
                                    if (is_array($colElem->$field)) {
                                        $valueString = implode(",", $colElem->$field);
                                    } else {
                                        $valueString = "";
                                    }
                                    break;
                                case "geobounds":
                                     if ($colElem->$field instanceof Object_Data_Geobounds) {
                                         $valueString = $colElem->$field->getNorthEast()->getLongitude() . ","
                                                 . $colElem->$field->getNorthEast()->getLatitude() . "|"
                                                 . $colElem->$field->getSouthWest()->getLongitude() . ","
                                                 . $colElem->$field->getSouthWest()->getLatitude();                                     
                                     }  
                                    break; 
                                case "geopoint":

                                     if ($colElem->$field instanceof Object_Data_Geopoint) {
                                         $valueString = $colElem->$field->getLatitude() . "," . $colElem->$field->getLongitude();                                     
                                     }  
                                    break;      
                                case "geopolygon":
                                     if (!empty($colElem->$field)) {
                                        $dataArray = $collectionTypeElement[$field]->getDataForEditmode($colElem->$field);
                                        $rows = array();
                                        if (is_array($dataArray)) {
                                            foreach ($dataArray as $point) {
                                                $rows[] = implode(";", $point);
                                            }
                                             $valueString = implode("|", $rows);
                                        }
                                    }
                                    break;  

                                default:
                                    if (isset($colElem->$field)) {
                                        $valueString = $colElem->$field;
                                    } 
                                    break;
                            }
                                                        
                            if (!isset($ob[$fieldName . '_' . $colElem->getType() . '_' . $field])) {
                                $ob[$fieldName . '_' . $colElem->getType() . '_' . $field] .= strval($valueString); 
                            } else {
                                $ob[$fieldName . '_' . $colElem->getType() . '_' . $field] .= strval("::" . strval($valueString));
                            }  
                       }                                           
                       
                }
//                
            }
        }
        
        return $ob;
    }

    /**
     * @param string $importValue
     * @return null
     */
    public function getFromCsvImport($importValue)
    {
        return;
    }


    public function save($object, $params = array())
    {
        $getter = "get" . ucfirst($this->getName());
        $container = $object->$getter();
        if ($container instanceof Object_Objectbrick) {
            $container->save($object);
        }
    }

    public function load($object, $params = array())
    {
        $classname = "Object_" . ucfirst($object->getClass()->getName()) . "_" . ucfirst($this->getName());

        if(Pimcore_Tool::classExists($classname)) {
            $container = new $classname($object, $this->getName());
            $container->load($object);

            return $container;
        } else {
            return null;
        }
    }

    public function delete($object)
    {
        $container = $this->load($object);
        if($container) {
            $container->delete($object);
        }
    }

    public function getAllowedTypes()
    {

        return $this->allowedTypes;
    }

    public function setAllowedTypes($allowedTypes)
    {
        if (is_string($allowedTypes)) {
            $allowedTypes = explode(",", $allowedTypes);
        }

        if (is_array($allowedTypes)) {
            for ($i = 0; $i < count($allowedTypes); $i++) {
                try {
                    Object_Objectbrick_Definition::getByKey($allowedTypes[$i]);
                } catch (Exception $e) {

                    Logger::warn("Removed unknown allowed type [ $allowedTypes[$i] ] from allowed types of object brick");
                    unset($allowedTypes[$i]);
                }
            }
        }

        $this->allowedTypes = (array)$allowedTypes;
        return $this;
    }

    /**
     * @param Object_Abstract $object
     * @return mixed
     */
    public function getForWebserviceExport($object)
    {
        $getter = "get" . ucfirst($this->getName());
        $data = $object->$getter();
        $wsData = array();

        if ($data instanceof Object_Objectbrick) {
            foreach ($data as $item) {


                if (!$item instanceof Object_Objectbrick_Data_Abstract) {
                    continue;
                }

                $wsDataItem = new Webservice_Data_Object_Element();
                $wsDataItem->value = array();
                $wsDataItem->type = $item->getType();

                try {
                    $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
                } catch (Exception $e) {
                    continue;
                }

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    $el = new Webservice_Data_Object_Element();
                    $el->name = $fd->getName();
                    $el->type = $fd->getFieldType();
                    $el->value = $fd->getForWebserviceExport($item);
                    if ($el->value ==  null && self::$dropNullValues) {
                        continue;
                    }

                    $wsDataItem->value[] = $el;

                }


            }
            $wsData[] = $wsDataItem;

        }

        return $wsData;


    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function getFromWebserviceImport($data, $relatedObject, $idMapper = null)
    {
        $containerName = "Object_" . ucfirst($relatedObject->getClass()->getName()) . "_" . ucfirst($this->getName());
        $container = new $containerName($relatedObject, $this->getName());

        if (is_array($data)) {
            foreach ($data as $collectionRaw) {
                if ($collectionRaw instanceof stdClass) {
                    $class = "Webservice_Data_Object_Element";
                    $collectionRaw = Pimcore_Tool_Cast::castToClass($class, $collectionRaw);
                }

                if($collectionRaw != null) {
                    if (!$collectionRaw instanceof Webservice_Data_Object_Element) {

                        throw new Exception("invalid data in objectbrick [" . $this->getName() . "]");
                    }

                    $brick = $collectionRaw->type;
                    $collectionData = array();
                    $collectionDef = Object_Objectbrick_Definition::getByKey($brick);

                    if (!$collectionDef) {
                        throw new Exception("Unknown objectbrick in webservice import [" . $brick . "]");
                    }

                    foreach ($collectionDef->getFieldDefinitions() as $fd) {
                        foreach ($collectionRaw->value as $field) {
                            if ($field instanceof stdClass) {
                                $class = "Webservice_Data_Object_Element";
                                $field = Pimcore_Tool_Cast::castToClass($class, $field);
                            }
                            if (!$field instanceof Webservice_Data_Object_Element) {
                                throw new Exception("invalid data in objectbricks [" . $this->getName() . "]");
                            } else if ($field->name == $fd->getName()) {

                                if ($field->type != $fd->getFieldType()) {
                                    throw new Exception("Type mismatch for objectbricks field [" . $field->name . "]. Should be [" . $fd->getFieldType() . "] but is [" . $field->type . "]");
                                }
                                $collectionData[$fd->getName()] = $fd->getFromWebserviceImport($field->value, $relatedObject, $idMapper);
                                break;
                            }


                        }

                    }

                    $collectionClass = "Object_Objectbrick_Data_" . ucfirst($brick);
                    $collection = new $collectionClass($relatedObject);
                    $collection->setValues($collectionData);
                    $collection->setFieldname($this->getName());

                    $setter = "set" . ucfirst($brick);

                    $container->$setter($collection);
                }

            }
        }

        return $container;

    }


    public function preSetData($object, $value, $params = array())
    {
        if ($value instanceof Object_Objectbrick) {
            $value->setFieldname($this->getName());
        }
        return $value;
    }


    /**
     * @param mixed $data
     */
    public function resolveDependencies($data)
    {
        $dependencies = array();

        if ($data instanceof Object_Objectbrick) {
            $items = $data->getItems();
            foreach ($items as $item) {
                if (!$item instanceof Object_Objectbrick_Data_Abstract) {
                    continue;
                }

                try {
                    $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
                } catch (Exception $e) {
                    continue;
                }

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    $key = $fd->getName();
                    $getter = "get" . ucfirst($key);
                    $dependencies = array_merge($dependencies, $fd->resolveDependencies($item->$getter()));
                }
            }
        }

        return $dependencies;
    }

    /**
     * @param mixed $data
     * @param Object_Concrete $ownerObject
     * @param array $blockedTags
     */
    public function getCacheTags($data, $ownerObject, $tags = array())
    {
        $tags = is_array($tags) ? $tags : array();

        if ($data instanceof Object_Objectbrick) {
            $items = $data->getItems();
            foreach ($items as $item) {

                if (!$item instanceof Object_Objectbrick_Data_Abstract) {
                    continue;
                }

                try {
                    $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
                } catch (Exception $e) {
                    continue;
                }

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    $key = $fd->getName();
                    $getter = "get" . ucfirst($key);
                    $tags = $fd->getCacheTags($item->$getter(), $item, $tags);
                }
            }
        }

        return $tags;
    }


    public function getGetterCode ($class) {
        // getter

        $key = $this->getName();
        $code = "";

        $code .= '/**' . "\n";
        $code .= '* @return ' . $this->getPhpdocType() . "\n";
        $code .= '*/' . "\n";
        $code .= "public function get" . ucfirst($key) . " () {\n";

        $code .= "\t" . '$data = $this->' . $key . ";\n";
        $code .= "\t" . 'if(!$data) { ' . "\n";

        $classname = "Object_" . ucfirst($class->getName()) . "_" . ucfirst($this->getName());

        $code .= "\t\t" . 'if(Pimcore_Tool::classExists("' . $classname . '")) { ' . "\n";
        $code .= "\t\t\t" . '$data = new ' . $classname . '($this, "' . $key . '");' . "\n";
        $code .= "\t\t\t" . '$this->' . $key . ' = $data;' . "\n";
        $code .= "\t\t" . '} else {' . "\n";
        $code .= "\t\t\t" . 'return null;' . "\n";
        $code .= "\t\t" . '}' . "\n";
        $code .= "\t" . '}' . "\n";


        if(method_exists($this,"preGetData")) {
            $code .= "\t" . '$data = $this->getClass()->getFieldDefinition("' . $key . '")->preGetData($this);' . "\n";
        }

        // adds a hook preGetValue which can be defined in an extended class
        $code .= "\t" . '$preValue = $this->preGetValue("' . $key . '");' . " \n";
        $code .= "\t" . 'if($preValue !== null && !Pimcore::inAdmin()) { return $preValue;}' . "\n";

        $code .= "\t return " . '$data' . ";\n";
        $code .= "}\n\n";

        return $code;
    }


    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false) {
        if(!$omitMandatoryCheck){
            if ($data instanceof Object_Objectbrick) {
                $items = $data->getItems();
                foreach ($items as $item) {
                    if($item->getDoDelete()) {
                        continue;
                    }

                    if (!$item instanceof Object_Objectbrick_Data_Abstract) {
                        continue;
                    }

                    try {
                        $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
                    } catch (Exception $e) {
                        continue;
                    }

                    foreach ($collectionDef->getFieldDefinitions() as $fd) {
                        $key = $fd->getName();
                        $getter = "get" . ucfirst($key);
                        $fd->checkValidity($item->$getter());
                    }
                }
            }
        }
    }

    /**
     * @param $data
     * @param Object_Concrete $object
     * @return string
     */
    public function getDataForGrid($data, $object = null) {
        return "NOT SUPPORTED";
    }


    private function getDiffDataForField($item, $key, $fielddefinition, $level, $baseObject, $getter, $objectFromVersion) {
        $parent = Object_Service::hasInheritableParentObject($baseObject);
        $valueGetter = "get" . ucfirst($key);

        $value = $fielddefinition->getDiffDataForEditmode($item->$valueGetter(), $baseObject);
        return $value;
    }

    /** See parent class.
     * @param mixed $data
     * @param null $object
     * @return array|null
     */
    private function doGetDiffDataForEditmode($data, $getter, $objectFromVersion, $level = 0) {
//        p_r($data); die();
        $parent = Object_Service::hasInheritableParentObject($data->getObject());
        $item = $data->$getter();

        if(!$item && !empty($parent)) {
            $data = $parent->{"get" . ucfirst($this->getName())}();
            return $this->doGetDiffDataForEditmode($data, $getter, $objectFromVersion, $level + 1);
        }

        if (!$item instanceof Object_Objectbrick_Data_Abstract) {
            return null;
        }

        try {
            $collectionDef = Object_Objectbrick_Definition::getByKey($item->getType());
        } catch (Exception $e) {
            return null;
        }

        $brickData = array();
        $brickMetaData = array();

        $result = array();

        foreach ($collectionDef->getFieldDefinitions() as $fd) {
            $fieldData = $this->getDiffDataForField($item, $fd->getName(), $fd, $level, $data->getObject(), $getter, $objectFromVersion); //$fd->getDataForEditmode($item->{$fd->getName()});

            $diffdata = array();

            foreach ($fieldData as $subdata) {
                $diffdata["field"] = $this->getName();
                $diffdata["key"] = $this->getName() . "~" . $fd->getName();
                $diffdata["value"] = $subdata["value"];
                $diffdata["type"] = $subdata["type"];
                $diffdata["disabled"] = $subdata["disabled"];

                // this is not needed anymoe
                unset($subdata["type"]);
                unset($subdata["value"]);

                $diffdata["title"] = $this->getName() . " / " . $subdata["title"];
                $brickdata = array(
                    "brick" => substr($getter, 3),
                    "name" => $fd->getName(),
                    "subdata" => $subdata
                );
                $diffdata["data"] = $brickdata;
            }

            $result[] = $diffdata;
        }

        return $result;
    }

    /** See parent class.
     * @param mixed $data
     * @param null $object
     * @return array|null
     */
    public function getDiffDataForEditMode($data, $object = null, $objectFromVersion = null)
    {
        $editmodeData = array();

        if ($data instanceof Object_Objectbrick) {
            $getters = $data->getBrickGetters();

            foreach ($getters as $getter) {
                $brickdata = $this->doGetDiffDataForEditmode($data, $getter, $objectFromVersion);
                if ($brickdata) {
                    foreach ($brickdata as $item) {
                        $editmodeData[] = $item;
                    }
                }
            }
        }

        return $editmodeData;
    }

    /** See parent class.
     * @param $data
     * @param null $object
     * @return null|Pimcore_Date
     */

    public function getDiffDataFromEditmode($data, $object = null) {

        $valueGetter = "get" . ucfirst($this->getName());
        $valueSetter = "set" . ucfirst($this->getName());
        $brickdata = $object->$valueGetter();

        foreach ($data as $item) {
            $subdata = $item["data"];
            if (!$subdata) {
                continue;
            }
            $brickname = $subdata["brick"];

            $getter = "get" . ucfirst($brickname);
            $setter = "set" . ucfirst($brickname);

            $brick = $brickdata->$getter();
            if (!$brick) {
                // brick must be added to object
                $brickClass = "Object_Objectbrick_Data_" . ucfirst($brickname);
                $brick = new $brickClass($object);
            }

            $fieldname = $subdata["name"];
            $fielddata = array($subdata["subdata"]);

            $collectionDef = Object_Objectbrick_Definition::getByKey($brickname);

            $fd = $collectionDef->getFieldDefinition($fieldname);
            if ($fd && $fd->isDiffChangeAllowed()) {
                $value = $fd->getDiffDataFromEditmode($fielddata, $object);
                $brick->setValue($fieldname, $value);

                $brickdata->$setter($brick);
            }

            $object->$valueSetter($brickdata);
        }
        return $brickdata;
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }

}
