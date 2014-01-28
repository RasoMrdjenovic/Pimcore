<?php

class ExtendedImport_AdminController extends Pimcore_Controller_Action_Admin
{
    /**
     * @var Object_Service
     */
    protected $_objectService;

    public function init()
    {
        parent::init();

        // check permissions
        $notRestrictedActions = array();
        if (!in_array($this->getParam("action"), $notRestrictedActions)) {
            $this->checkPermission("objects");
        }

        $this->_objectService = new Object_Service($this->getUser());
    }

    public function treeGetChildsByIdAction()
    {
        $object = Object_Abstract::getById($this->getParam("node"));
        $objectTypes = null;

        if ($object instanceof Object_Concrete) {
            $class = $object->getClass();
            if ($class->getShowVariants()) {
                $objectTypes = array(Object_Abstract::OBJECT_TYPE_FOLDER, Object_Abstract::OBJECT_TYPE_OBJECT, Object_Abstract::OBJECT_TYPE_VARIANT);
            }
        }

        if (!$objectTypes) {
            $objectTypes = array(Object_Abstract::OBJECT_TYPE_OBJECT, Object_Abstract::OBJECT_TYPE_FOLDER);
        }

        if ($object->hasChilds($objectTypes)) {

            $limit = intval($this->getParam("limit"));
            if (!$this->getParam("limit")) {
                $limit = 100000000;
            }
            $offset = intval($this->getParam("start"));


            $childsList = new Object_List();
            $condition = "o_parentId = '" . $object->getId() . "'";

            // custom views start
            if ($this->getParam("view")) {
                $cvConfig = Pimcore_Tool::getCustomViewConfig();
                $cv = $cvConfig[($this->getParam("view") - 1)];

                if ($cv["classes"]) {
                    $cvConditions = array();
                    $cvClasses = explode(",", $cv["classes"]);
                    foreach ($cvClasses as $cvClass) {
                        $cvConditions[] = "o_classId = '" . $cvClass . "'";
                    }

                    $cvConditions[] = "o_type = 'folder'";

                    if (count($cvConditions) > 0) {
                        $condition .= " AND (" . implode(" OR ", $cvConditions) . ")";
                    }
                }
            }
            // custom views end

            $childsList->setCondition($condition);
            $childsList->setLimit($limit);
            $childsList->setOffset($offset);
            $childsList->setOrderKey("o_key");
            $childsList->setOrder("asc");
            $childsList->setObjectTypes($objectTypes);

            $childs = $childsList->load();

            foreach ($childs as $child) {
                $tmpObject = $this->getTreeNodeConfig($child);

                if ($child->isAllowed("list")) {
                    $objects[] = $tmpObject;
                }
            }
        }

        if ($this->getParam("limit")) {
            $this->_helper->json(array(
                "total" => $object->getChildAmount(),
                "nodes" => $objects
            ));
        }
        else {
            $this->_helper->json($objects);
        }

    }



    public function treeGetRootAction()
    {

        $id = 1;
        if ($this->getParam("id")) {
            $id = intval($this->getParam("id"));
        }

        $root = Object_Abstract::getById($id);
        if ($root->isAllowed("list")) {
            $this->_helper->json($this->getTreeNodeConfig($root));
        }

        $this->_helper->json(array("success" => false, "message" => "missing_permission"));
    }

    
    
    /**
     * @param Object_Abstract $child
     * @return array
     */
    protected function getTreeNodeConfig($child)
    {


        $tmpObject = array(
            "id" => $child->getId(),
            "text" => $child->getKey(),
            "type" => $child->getType(),
            "path" => $child->getFullPath(),
            "basePath" => $child->getPath(),
            "elementType" => "object",
            "locked" => $child->isLocked(),
            "lockOwner" => $child->getLocked() ? true : false
        );

        $tmpObject["isTarget"] = false;
        $tmpObject["allowDrop"] = false;
        $tmpObject["allowChildren"] = false;

        $tmpObject["leaf"] = $child->hasNoChilds();
//        $tmpObject["iconCls"] = "pimcore_icon_object";

        $tmpObject["isTarget"] = true;
        if ($tmpObject["type"] != "variant") {
            $tmpObject["allowDrop"] = true;
        }

        $tmpObject["allowChildren"] = true;

        $tmpObject["leaf"] = false;
        $tmpObject["cls"] = "";

        if ($child->getType() == "folder") {
//            $tmpObject["iconCls"] = "pimcore_icon_folder";
            $tmpObject["qtipCfg"] = array(
                "title" => "ID: " . $child->getId()
            );
        }
        else {
            $tmpObject["published"] = $child->isPublished();
            $tmpObject["className"] = $child->getClass()->getName();
            $tmpObject["qtipCfg"] = array(
                "title" => "ID: " . $child->getId(),
                "text" => 'Type: ' . $child->getClass()->getName()
            );

            if (!$child->isPublished()) {
                $tmpObject["cls"] .= "pimcore_unpublished ";
            }

            $tmpObject["allowVariants"] = $child->getClass()->getAllowVariants();

//            if ($child->getClass()->getIcon()) {
//                unset($tmpObject["iconCls"]);
//                $tmpObject["icon"] = $child->getClass()->getIcon();
//            }
        }
        if ($tmpObject["type"] == "variant") {
            $tmpObject["iconCls"] = "pimcore_icon_tree_variant";
        } else {
            if($child->getElementAdminStyle()->getElementIcon()) {
                $tmpObject["icon"] = $child->getO_elementAdminStyle()->getElementIcon();
            }

            if($child->getElementAdminStyle()->getElementIconClass()) {
                $tmpObject["iconCls"] = $child->getO_elementAdminStyle()->getElementIconClass();
            }
        }

        if($child->getElementAdminStyle()->getElementCssClass()) {
            $tmpObject["cls"] .= $child->getO_elementAdminStyle()->getElementCssClass() . " ";
        }


        $tmpObject["expanded"] = $child->hasNoChilds();
        $tmpObject["permissions"] = $child->getUserPermissions($this->getUser());


        if ($child->isLocked()) {
            $tmpObject["cls"] .= "pimcore_treenode_locked ";
        }
        if ($child->getLocked()) {
            $tmpObject["cls"] .= "pimcore_treenode_lockOwner ";
        }

        return $tmpObject;
    }


    public function exportAction(){

        $class = Object_Class::getByName($this->getParam("className"));
        $className = $class->getName();
        $listClass = "Object_" . ucfirst($className) . "_List"; 
        if($this->getParam("objectId")){
            $objectId = $this->getParam("objectId");
            $object = Object_Abstract::getById($objectId);
            $objects = array();
            if($object->hasChilds()){
                foreach($object->getChilds() as $childObject){
                    if ($childObject instanceof Object_Concrete) {                       
                        $o = $this->csvObjectData($childObject);
                        $objects[] = $o;
                    }                 
                }
            }
            else{
                if ($object instanceof Object_Concrete) {
                    $o = $this->csvObjectData($object);
                    $objects[] = $o;
                }
            }        
        }
        else{
            
            $folder = Object_Abstract::getById($this->getParam("folderId"));

            if(!empty($folder)) {
                $conditionFilters = array("o_path LIKE '" . $folder->getFullPath() . "%'");
            } else {
                $conditionFilters = array();
            }

            if ($this->getParam("filter")) {
                $conditionFilters[] = Object_Service::getFilterCondition($this->getParam("filter"), $class);
            }
            if ($this->getParam("condition")) {
                $conditionFilters[] = "(" . $this->getParam("condition") . ")";
            }

            $list = new $listClass();
            $list->setCondition(implode(" AND ", $conditionFilters));
            $list->setOrder("ASC");
            $list->setOrderKey("o_id");
          
            if($this->getParam("objecttype")) {
                $list->setObjectTypes(array($this->getParam("objecttype")));
            }

            $list->load();

            $objects = array();          
            Logger::debug("objects in list:" . count($list->getObjects()));
            
            foreach ($list->getObjects() as $object) {

                if ($object instanceof Object_Concrete) {
                    $o = $this->csvObjectData($object);
                    $objects[] = $o;
                }
            }
            //create csv
        }      
        if(!empty($objects)) {           
            $columns = array_keys($objects[0]);
            foreach ($columns as $key => $value) {
                $columns[$key] = '"' . $value . '"';
            }
            $csv = implode(";", $columns) . "\r\n";
            foreach ($objects as $o) {
                foreach ($o as $key => $value) {
                                        
                    //clean value of evil stuff such as " and linebreaks
                    if (is_string($value)) {
                        $value = strip_tags($value);
                        $value = str_replace('"', '', $value);
                        $value = str_replace("\r", "", $value);
                        $value = str_replace("\n", "", $value);

                        $o[$key] = '"' . $value . '"';                                             
                    }
                }

                $csvArray = array();
                foreach ($columns as $column) {
                    
                    $column = str_replace('"', '', $column);

                    if (array_key_exists($column,  $o)) {
                        
                        $csvArray[$column] = $o[$column];
                    } else {
                        $csvArray[$column] = "";
                    }                    
                }
                
                $csv .= implode(";", $csvArray) . "\r\n";
            }
        }
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=\"export.csv\"");
        echo $csv;
        exit;
 
     }
 
    public function loadFromParentObj($parentObj, $object)
    {

        foreach($parentObj as $key => $val) {
            
            if (is_object($parentObj)) {
                if (is_array($val)) {
                    $object->$key = $this->loadFromParentObj($parentObj->$key, array());
                } else if(is_object($val)) {
                     $className = get_class($val);
                     $object->$key = $this->loadFromParentObj($parentObj->$key, new $className);
                } else {
                    $object->$key = $parentObj->$key;
                }           
            } else if (is_array($parentObj)) {              
                if(is_array($val)) {
                    $object[$key] = $this->loadFromParentObj($parentObj[$key], array());
                } else if(is_object($val)) {
                    $className = get_class($val);
                    $object[$key] = $this->loadFromParentObj($parentObj[$key], new $className);
                } else {
                    $object[$key] = $parentObj[$key];
                }
            }          
        }

        return $object;
    }
         
     protected function csvObjectData($object)
     {   
        $o = array();
        foreach ($object->getClass()->getFieldDefinitions() as $key => $value) {
            //exclude remote owner fields
            if (!($value instanceof Object_Class_Data_Relations_Abstract and $value->isRemoteOwner())) {
                
                $objClassName = get_class($value);
                $newClassName = "ExtendedImport" . substr($objClassName, 6);                  
                $newObj = new $newClassName();
                $newObj = $this->loadFromParentObj($value, $newObj);
        
                if( $value->fieldtype == 'localizedfields' 
                      || $value->fieldtype == 'objectbricks' 
                      || $value->fieldtype == 'fieldcollections' ) {

                    $array = $newObj->getForCsvExport($object, $value); 
                    foreach($array as $key=>$value) {
                        $o[$key] = $value;
                    }
                } 
                else {
                    $o[$key] = $newObj->getForCsvExport($object);
                }               
            }

        }

        $o["id (system)"] = $object->getId();
        $o["key (system)"] = $object->getKey();
        $o["fullpath (system)"] = $object->getFullPath();
        $o["published (system)"] = $object->isPublished();
     
        return $o;
    }    
    
    
    
}
