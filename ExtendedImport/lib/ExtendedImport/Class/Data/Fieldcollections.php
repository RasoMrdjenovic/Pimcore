<?php 
/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @category   Pimcore
 * @package    Object_Class
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

class ExtendedImport_Class_Data_Fieldcollections extends ExtendedImport_Class_Data
{

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "fieldcollections";

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "Object_Fieldcollection";

    /**
     * @var string
     */
    public $allowedTypes = array();

    /**
     * @var boolean
     */
    public $lazyLoading;

    /**
     * @var int
     */
    public $maxItems;


    /**
     * @return boolean
     */
    public function getLazyLoading(){
        return $this->lazyLoading;
    }

    /**
     * @param  $lazyLoading
     * @return void
     */
    public function setLazyLoading($lazyLoading){
        $this->lazyLoading = $lazyLoading;
        return $this;
    }

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForEditmode($data, $object = null)
    {

        $editmodeData = array();

        if ($data instanceof Object_Fieldcollection) {
            foreach ($data as $item) {

                if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                    continue;
                }

                try {
                    $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
                } catch (Exception $e) {
                    continue;
                }

                $collectionData = array();

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    $collectionData[$fd->getName()] = $fd->getDataForEditmode($item->{$fd->getName()}, $object); 
                }

                $editmodeData[] = array(
                    "data" => $collectionData,
                    "type" => $item->getType()
                );
            }
        }

        return $editmodeData;
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataFromEditmode($data, $object = null)
    {
        $values = array();
        $count = 0;

        if (is_array($data)) {
            foreach ($data as $collectionRaw) {

                $collectionData = array();
                $collectionDef = Object_Fieldcollection_Definition::getByKey($collectionRaw["type"]);

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    if (array_key_exists($fd->getName(),$collectionRaw["data"])) {
                        $collectionData[$fd->getName()] = $fd->getDataFromEditmode($collectionRaw["data"][$fd->getName()]);
                    }
                }

                $collectionClass = "Object_Fieldcollection_Data_" . ucfirst($collectionRaw["type"]);
                $collection = new $collectionClass;
                $collection->setValues($collectionData);
                $collection->setIndex($count);
                $collection->setFieldname($this->getName());

                $values[] = $collection;

                $count++;
            }
        }

        $container = new Object_Fieldcollection($values, $this->getName());
        return $container;
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param string $data
     * @return string
     */
    public function getVersionPreview($data)
    {
        return "FIELDCOLLECTIONS";
    }

    /**
     * @param Object_Abstract $object
     * @return string
     */
    public function getForCsvExport($object, $value)
    {
        $ob = array();
        // "password" - have a sys bug! You haven't save password field in fieldcollections !
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
        
        $collectTypes = $this->allowedTypes; 
        $colElems = $items->items;        
        
        foreach ($collectTypes as $collectType) {
            
            $class = "Object_Fieldcollection_Data_" . ucfirst($collectType);
            $collectionMethods = get_class_methods($class);
            
            foreach($collectionMethods as $collectionMethod){
                if (substr($collectionMethod, 0,3) == 'get' && !method_exists("Object_Fieldcollection_Data_Abstract", $collectionMethod) && !method_exists("Pimcore_Model_Abstract", $collectionMethod)){ 
                    $fields[]= lcfirst(substr($collectionMethod, 3)); 
                }
            }
        } 

        if(count($colElems) > 0){
            foreach($colElems as $key => $colElem){

                $collectionClassName = "Object_Fieldcollection_Data_" . ucfirst($colElem->type); 
                $collectionClass = new $collectionClassName();
                $collectionTypeElement = $collectionClass->getDefinition()->getFieldDefinitions();
                
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

        if ($container instanceof Object_Fieldcollection) {
            $container->save($object);
        }
    }

    public function load($object, $params = array())
    {
        if (!$this->getLazyLoading() or $params["force"]) {
            $container = new Object_Fieldcollection(null, $this->getName());
            $container->load($object);

            if ($container->isEmpty()) {
                return null;
            }

            return $container;
        }

        return null;
    }

    public function delete($object)
    {
        $container = new Object_Fieldcollection(null, $this->getName());
        $container->delete($object);
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
                    Object_Fieldcollection_Definition::getByKey($allowedTypes[$i]);
                } catch (Exception $e) {

                    Logger::warn("Removed unknown allowed type [ $allowedTypes[$i] ] from allowed types of field collection");
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

        if ($data instanceof Object_Fieldcollection) {
            foreach ($data as $item) {

                if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                    continue;
                }

                $wsDataItem = new Webservice_Data_Object_Element();
                $wsDataItem->value = array();
                $wsDataItem->type = $item->getType();

                try {
                    $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
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

                $wsData[] = $wsDataItem;
            }


        }

        return $wsData;


    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function getFromWebserviceImport($data, $object = null, $idMapper = null)
    {
        $values = array();
        $count = 0;

        if (is_array($data)) {
            foreach ($data as $collectionRaw) {

                if ($collectionRaw instanceof stdClass) {
                    $collectionRaw = Pimcore_Tool_Cast::castToClass("Webservice_Data_Object_Element", $collectionRaw);
                }
                if (!$collectionRaw instanceof Webservice_Data_Object_Element) {

                    throw new Exception("invalid data in fieldcollections [" . $this->getName() . "]");
                }

                $fieldcollection = $collectionRaw->type;
                $collectionData = array();
                $collectionDef = Object_Fieldcollection_Definition::getByKey($fieldcollection);

                if (!$collectionDef) {
                    throw new Exception("Unknown fieldcollection in webservice import [" . $fieldcollection . "]");
                }

                foreach ($collectionDef->getFieldDefinitions() as $fd) {
                    foreach ($collectionRaw->value as $field) {
                        if ($field instanceof stdClass) {
                            $field = Pimcore_Tool_Cast::castToClass("Webservice_Data_Object_Element", $field);
                        }
                        if (!$field instanceof Webservice_Data_Object_Element) {
                            throw new Exception("invalid data in fieldcollections [" . $this->getName() . "]");
                        } else if ($field->name == $fd->getName()) {

                            if ($field->type != $fd->getFieldType()) {
                                throw new Exception("Type mismatch for fieldcollection field [" . $field->name . "]. Should be [" . $fd->getFieldType() . "] but is [" . $field->type . "]");
                            }
                            $collectionData[$fd->getName()] = $fd->getFromWebserviceImport($field->value, $object, $idMapper);
                            break;
                        }


                    }

                }

                $collectionClass = "Object_Fieldcollection_Data_" . ucfirst($fieldcollection);
                $collection = new $collectionClass;
                $collection->setValues($collectionData);
                $collection->setIndex($count);
                $collection->setFieldname($this->getName());

                $values[] = $collection;

                $count++;
            }
        }

        $container = new Object_Fieldcollection($values, $this->getName());
        return $container;


    }


    /**
     * @param mixed $data
     */
    public function resolveDependencies($data)
    {
        $dependencies = array();

        if ($data instanceof Object_Fieldcollection) {
            foreach ($data as $item) {

                if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                    continue;
                }

                try {
                    $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
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

        if ($data instanceof Object_Fieldcollection) {
            foreach ($data as $item) {

                if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                    continue;
                }

                try {
                    $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
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


    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false) {

        if(!$omitMandatoryCheck){
            if ($data instanceof Object_Fieldcollection) {
                foreach ($data as $item) {

                    if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                        continue;
                    }

                    try {
                        $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
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
 

    public function preGetData ($object, $params = array()) {

        if(!$object instanceof Object_Concrete) {
            throw new \Exception("Field Collections are only valid in Objects");
        }

        $data = $object->{$this->getName()};
        if($this->getLazyLoading() and !in_array($this->getName(), $object->getO__loadedLazyFields())){
            $data = $this->load($object, array("force" => true));

            $setter = "set" . ucfirst($this->getName());
            if(method_exists($object, $setter)) {
                $object->$setter($data);
            }
        }
        return $data;
    }

    public function preSetData ($object, $data, $params = array()) {

        if($data === null) $data = array();

        if($this->getLazyLoading() and !in_array($this->getName(), $object->getO__loadedLazyFields())){
            $object->addO__loadedLazyField($this->getName());
        }

        if ($data instanceof Object_Fieldcollection) {
            $data->setFieldname($this->getName());
        }

        return $data;
    }


    /**
     * @param $data
     * @param Object_Concrete $object
     * @return string
     */
    public function getDataForGrid($data, $object = null) {
        return "NOT SUPPORTED";
    }


    public function getGetterCode ($class) {
        // getter

        $key = $this->getName();
        $code = "";

        $code .= '/**' . "\n";
        $code .= '* @return ' . $this->getPhpdocType() . "\n";
        $code .= '*/' . "\n";
        $code .= "public function get" . ucfirst($key) . " () {\n";

        // adds a hook preGetValue which can be defined in an extended class
        $code .= "\t" . '$preValue = $this->preGetValue("' . $key . '");' . " \n";
        $code .= "\t" . 'if($preValue !== null && !Pimcore::inAdmin()) { return $preValue;}' . "\n";

        if(method_exists($this,"preGetData")) {
            $code .= "\t" . '$data = $this->getClass()->getFieldDefinition("' . $key . '")->preGetData($this);' . "\n";
        } else {
            $code .= "\t" . '$data = $this->' . $key . ";\n";
        }

        $code .= "\t return " . '$data' . ";\n";
        $code .= "}\n\n";

        return $code;
    }

    /**
     * @param int $maxItems
     */
    public function setMaxItems($maxItems)
    {
        $this->maxItems = $this->getAsIntegerCast($maxItems);
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxItems()
    {
        return $this->maxItems;
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }


    /** Generates a pretty version preview (similar to getVersionPreview) can be either html or
     * a image URL. See the ObjectMerger plugin documentation for details
     * @param $data
     * @param null $object
     * @return array|string
     */
    public function getDiffVersionPreview($data, $object = null) {
        $html = "";
        if ($data instanceof Object_Fieldcollection) {

            $html = "<table>";
            foreach ($data as $item) {
                if (!$item instanceof Object_Fieldcollection_Data_Abstract) {
                    continue;
                }

                $type = $item->getType();
                $html .= "<tr><th><b>" . $type . "</b></th><th>&nbsp;</th><th>&nbsp;</th></tr>";

                try {
                    $collectionDef = Object_Fieldcollection_Definition::getByKey($item->getType());
                } catch (Exception $e) {
                    continue;
                }

                $collectionData = array();

                foreach ($collectionDef->getFieldDefinitions() as $fd) {

                    $title = !empty($fd->title) ? $fd->title : $fd->getName();
                    $html .= "<tr><td>&nbsp;</td><td>" . $title . "</td><td>";
                    $html .= $fd->getVersionPreview($item->{$fd->getName()});
                    $html .= "</td></tr>";
                }
            }

            $html .= "</table>";
        }

        $value = array();
        $value["html"] = $html;
        $value["type"] = "html";
        return $value;
    }

    public function getDiffDataFromEditmode($data, $object = null) {
        $result = parent::getDiffDataFromEditmode($data, $object);
        return $result;
    }

}
