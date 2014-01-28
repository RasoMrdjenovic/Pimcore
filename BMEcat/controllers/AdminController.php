<?php

class BMEcat_AdminController extends Pimcore_Controller_Action_Admin {
    

    
    
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
    
    
    public function getClassAction(){
        $src = array();
        
        $class = Object_Class::getByName($this->getParam('className'));
        $class->setFieldDefinitions(null);
        $fields = $class->layoutDefinitions->childs;
//        Logger::warning($fields);
        
        foreach($fields as $field){
          $src[]= self::recursive($field);
        }
 
    //    Logger::warning("@@@@@@@@@@@");
   //     Logger::warning($src);
        
        $this->_helper->json($src);
 
    }
    
    protected function recursive($field){
        
        if(count($field->childs) > 0){
            $childs = array();
            foreach($field->childs as $child){
                $childs[]= self::recursive($child);
            }
            
           $array =  self::returnFieldData($field,false,$childs);
           return $array;
            
        }else{
              $array = self::returnFieldData($field,true);
              return $array;
            }
    }
    
    protected function returnFieldData($field, $leaf, $children = null){   
      $expanded = true;  
      if($field->fieldtype == 'objects'){
          $className = $field->classes[0]['classes'];
          $class = Object_Class::getByName($className);
          $class->setFieldDefinitions(null);
          $classFields = $class->layoutDefinitions->childs;
          $children = array() ;
          foreach($classFields as $classField){
              $children[]= self::recursive($classField);
          }
          $leaf = false;
          $expanded = false;
      }  
      return  array(
            'text'      =>    $field->name,
            'iconCls' => 'pimcore_icon_' . $field->fieldtype,
            'leaf' => $leaf,
            "elementType" => "element",
            'expanded' =>$expanded,
            'allowChildren' => true,
            'children' => $children,
            'qtipCfg' => array(
                "title" => $field->title,
                "type" => $field->datatype,
                "fieldtype" => $field->fieldtype
            )
            );
    }
    
    
    public function getXmlTreeAction(){
        
        $src = array();
        
       $SUPPLIER_PID[] = array(
            'text' => "SUPPLIER_PID_OOO",
            'leaf' => true,
            "elementType" => "element",
            'expanded' => true,
            'allowChildren' => true,
      //      'children' => $children,
            'qtipCfg' => array(
                "title" => "SUPPLIER_PID_OOO",
                "type" => "xml",
                "fieldtype" => "tag"
                )
            );
       
       $producte[] = array(
            'text' => "PRODUCTE",
            'leaf' => false,
            "elementType" => "element",
            'expanded' => true,
            'allowChildren' => true,
            'children' => $SUPPLIER_PID,
            'qtipCfg' => array(
                "title" => "PRODUCTE",
                "type" => "xml",
                "fieldtype" => "tag"
                )
            );   
        
        $children[] = array(
            'text' => "header",
            'leaf' => true,
            "elementType" => "element",
            'expanded' => true,
            'allowChildren' => true,
      //      'children' => $children,
            'qtipCfg' => array(
                "title" => "header",
                "type" => "xml",
                "fieldtype" => "tag"
                )
            );
        
        $children[] = array(
            'text' => "T_new_catalog",
            'leaf' => false,
            "elementType" => "element",
            'expanded' => true,
            'allowChildren' => true,
            'children' => $producte,
            'qtipCfg' => array(
                "title" => "T_new_catalog",
                "type" => "xml",
                "fieldtype" => "tag"
                )
            );
 
        $src[] = array(
            'text'      =>    "bmecat",
            'leaf' => false,
            "elementType" => "element",
            'expanded' => true,
            'allowChildren' => true,
            'children' => $children,
            'qtipCfg' => array(
                "title" => "bmecat",
                "type" => "xml",
                "fieldtype" => "tag"
                )
            );
        

        
        
         $this->_helper->json($src);
    }

    
    
    

    public function getClassFieldsAction() {
        
        $src = array();

        
        
        
        $class = Object_Class::getByName($this->getParam('className'));
        $class->setFieldDefinitions(null);
        $fields = $class->layoutDefinitions->childs[0];
        
        if ($fields->hasChilds()) { 
            $tmpArray[$fields->getName()] = $this->createMappingArray($fields->getChilds(), array()/*, $supportedFieldTypes*/);   
        } 

        
        $availableFields = array();

        $locArray = self::localized_array($tmpArray);
//        
        Logger::warning($tmpArray);
        Logger::warning($locArray);
        

        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($tmpArray));

        $locIt = new RecursiveIteratorIterator(new RecursiveArrayIterator($locArray));

        foreach($it as $key => $value) {
            
//            if (in_array($value, $locIt)) {
//                
//      //          $availableFields[] = $value . "aaa";
//            }
                 
               $availableFields[] = $value;      
        }                       

 
        
        
        $this->_helper->json(
            array(
                "success" => true,
                "availableFields" => $availableFields
            )
        );

    }
 
    
    
    private function localized_array($array, &$locArray = null) {

        if(!$locArray) {
            if (is_array($array)) {
                foreach ($array as $key => $value) {

                    if ($key == "localizedfields") {
                        $locArray = $value;
                        break;
                    } 
                    self::localized_array($value, $locArray);
                }
                return $locArray;
            }
        
        }

    }
    
    
    private function createMappingArray($def, $loc, $supportedFieldTypes = null) {

        
        foreach($def as $key => $child) {
           
            if ($child instanceof Object_Class_Layout) {

                if ($child->hasChilds()) { 
                    $loc[$child->getName()] = $this->createMappingArray($child->getChilds(), $loc[$child->getName()], $supportedFieldTypes);
                }
                
            } else if ($child instanceof Object_Class_Data_Localizedfields) {

                if ($child->hasChilds()) { 
                    
                    $loc[$child->getName()] = $this->createMappingArray($child->getChilds(), $loc[$child->getName()], $supportedFieldTypes);
                }
                
            } 
            
            
            
            else if ($child instanceof Object_Class_Data) {

                $loc[$child->getName()] = $child->getName();
            }  
            
            
        }
        
        return $loc; 
     }
    
    
    public function getFoldersAction(){
        $root = Object_Abstract::getById(1);
        $folders = $root->getChilds();
        $this->_helper->json($folders);
    }
    
    protected function loadClass($class_name) {
         include  PIMCORE_PLUGINS_PATH . "/BMEcat/lib/generated/" . $class_name . '.php';
         return new $class_name();
    }
   
    
    public function createObjectsAction() {
        
        $mapping = Zend_Json::decode($this->getParam('mapping'), Zend_Json::TYPE_ARRAY);
    //    $importId = $this->getParam('id');
        $className = $this->getParam('classImport');
        $targetPath = $this->getParam('targetPath');
        $objectName = $this->getParam('objectName');

        
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_bmecat_" . $this->getParam("id");
        
        $xml = simplexml_load_file($file);
        $arr = (array) $xml->T_NEW_CATALOG;
        $checker = false;
        
   //     Logger::warning($mapping);
        
        foreach ($mapping as $key => $arrayValue) {
            
             if(is_null($arrayValue) || $arrayValue == '') {
                 unset($mapping[$key]);
             } else {

                $dirtyArray = explode("->", $arrayValue);
                $newArray = array_slice($dirtyArray, 1);
                // if we have more then one product in file
                if ($arr[$newArray[0]]['0']) {
                    $checker = true;
                    array_splice($newArray, 1, 0, "0");
                }
                $mapping[$key] = $newArray;
            }
        
        }

        if ($checker) {
            $countProduct = count($arr[$newArray[0]]);
        } else {
            $countProduct = 1;
        }

        for ($i = 0; $i < $countProduct; $i++) {

            $objectClass = 'Object_' . ucfirst($className);
            $object = $objectClass::create();

            foreach ($mapping as $key => $arrayValue) {
                
                if ($checker) {
                    // mapping thet product (if we have more then 1 product in arr)
                    $mapping[$key][1] = $i;
                }               
                $subarr = $arr;
                foreach ($mapping[$key] as $value) {
                    $subarr = (array) $subarr[$value];
                }
                $method = 'set' . ucfirst($key);
                $object->$method($subarr[0]);
            }
            
            if ($i == 0) {
                $object->setKey($objectName);
            } else {
               $object->setKey($objectName . "_" . $i); 
            }
            
            $object->setPublished(true);
            $object->setParentId(Object_Abstract::getByPath($targetPath)->getId());
            $object->save();
        }
       

       
        echo "Object successfuly generated";
    }
    
    public function createXmlAction(){
  
        require_once PIMCORE_PLUGINS_PATH . '/BMEcat/lib/Array2XML.php'; 

        $headerInfo = Zend_Json::decode($this->getParam('headerInfo')); // info for bmecat header tag
        $catData =  Zend_Json::decode($this->getParam('mapping'));     // info for transaction tags (T_NEW_CATALOG or T_UPDATE_PRODUCTS or T_UPDATE_PRICES)
        $className = $this->getParam('class');                         
        $objectClass = 'Object_' . ucfirst($className);
        $class = Object_Class::getByName($className);
        $classList = 'Object_' . ucfirst($className) . '_List';
        $objects = new $classList();
        $languages = Pimcore_Tool::getValidLanguages();              
        $methods = get_class_methods($objectClass);
      
        $bmecat = self::loadClass(BMECAT);
        $header = self::loadClass(HEADER);
        $bmecat->HEADER = $header;

        /*-----CATALOG-----*/
        $catalog = self::loadClass(CATALOG);
        $catalog->CATALOG_ID = $headerInfo["bme_catId"];
        $catalog->LANGUAGE = $headerInfo["bme_language"];
        $catalog->CATALOG_VERSION = $headerInfo["bme_catVarsion"];
        $catalog->CATALOG_NAME = $headerInfo["bme_catName"];
        $catalog->CURRENCY = $headerInfo["bme_currency"];
        $bmecat->HEADER->CATALOG = $catalog;

        /*-----BUYER-----*/
        $buyer = self::loadClass(BUYER);
        $buyer->BUYER_ID = $headerInfo["bme_buyerId"];
        $buyer->BUYER_NAME = $headerInfo["bme_buyerName"];
        $bmecat->HEADER->BUYER = $buyer;

        /*-----SUPPLIER-----*/
        $supplier = self::loadClass(SUPPLIER);
        $supplier->SUPPLIER_NAME = $headerInfo["bme_supplierName"];
        $bmecat->HEADER->SUPPLIER = $supplier;


        $bmecat->HEADER->SUPPLIER_IDREF['@attributes'] = array( "type" => $headerInfo["bme_suppIdRef"] );
        $bmecat->HEADER->DOCUMENT_CREATOR_IDREF['@attributes'] = array("type" => $headerInfo["bme_docCreator"]);

        /*----T_NEW_CATALOG----*/

        $tNewCatalog = self::loadClass(T_NEW_CATALOG);
        $product = self::loadClass(PRODUCT);
        $pid = self::loadClass(SUPPLIER_PID);
            
        /*----T_NEW_CATALOG -> CATALOG GROUP SYSTEM------*/
        if($catData['groupSystemName'] !== ''){
            $tNewCatalog->CATALOG_GROUP_SYSTEM = array();
            $catalogGroupSys = array();
            $catalogGroupSys['GROUP_SYSTEM_NAME'] = $catData['groupSystemName'];
            $root = Object_Abstract::getByPath('/' . $catData['catalogRoot']);
            $catalogGroupSys['CATALOG_STRUCTURE'] = array(array('@attributes' => array('type'=>'root'), 'GROUP_ID' => $root->getId(),'GROUP_NAME'=>$root->getKey(),'PARENT_ID'=>$root->getParentId()));
            $category = $catData['categoryClassName'];
            $instance = 'Object_' . ucfirst($category);
            $childs = $root->getChilds();
            $allCategories = self::getCategories($childs,$instance);
            foreach($allCategories as $categorieArray){
                array_push($catalogGroupSys['CATALOG_STRUCTURE'], $categorieArray);
            }

            array_push($tNewCatalog->CATALOG_GROUP_SYSTEM, $catalogGroupSys);
        }
        
        /*----END T_NEW_CATALOG -> CATALOG GROUP SYSTEM------*/
        
        /*----T_NEW_CATALOG -> PRODUCT------*/
        $tNewCatalog->PRODUCT = array();
        $productArray = array();
        $productArray['@attributes'] = array('mode' => 'new');
        
    //    Logger::warning($catData);

        $descLong = $manufacturer = $delivery = $keywords = $productOrder = $productType = $productCategory = null;         
        $ouPerCu = $priceQuantity = $quantMin = $quantInterv = $quantMax = null;
        $tax = $priceFactor = $lowerBound = $teritories = null;
        $mimeType = $mimeDescr = $mimeAlt = $mimePurpose = null;
            
        foreach($objects as $object){
            $pidMethod = self::getMethod($catData['pid'],$methods);
            $supplierPid = $object->$pidMethod();
            $productArray['SUPPLIER_PID'] = $supplierPid;
            
            $shortDescMethod = self::getMethod($catData['short_desc'],$methods);
            $shorDesc = $object->$shortDescMethod(); 
             
            
            if ( $catData['desc_long'] || !empty($catData['desc_long']) ) {
                $descLongMethod = self::getMethod($catData['desc_long'],$methods);
                $descLong = $object->$descLongMethod();
            }
            
            if ($catData['manufacturer'] || !empty($catData['manufacturer']) ) {
                $manufacturerMethod = self::getMethod($catData['manufacturer'],$methods);
                $manufacturer = $object->$manufacturerMethod(); 
            }
            
            if ($catData['delivery'] || !empty($catData['delivery']) ) {
                $deliveryMethod = self::getMethod($catData['delivery'],$methods);
                $delivery = $object->$deliveryMethod();
            }
            
            if ($catData['keywords'] || !empty($catData['keywords']) ) {
                $keywordsMethod = self::getMethod($catData['keywords'],$methods);
                $keywords = $object->$keywordsMethod();
            }
            
            if ($catData['product_order'] || !empty($catData['product_order']) ) {
                $productOrderMethod = self::getMethod($catData['product_order'],$methods);
                $productOrder = $object->$productOrderMethod();
            }
            
            if ($catData['product_type'] || !empty($catData['product_type']) ) {
                $productTypeMethod = self::getMethod($catData['product_type'],$methods);
                $productType = $object->$productTypeMethod();
            }
            
            if ($catData['product_category'] || !empty($catData['product_category']) ) {
                $productCategoryMethod = self::getMethod($catData['product_category'],$methods);
                $productCategory = $object->$productCategoryMethod();
            }
            
            $productArray['PRODUCT_DETAILS'] = array(
                "DESCRIPTION_SHORT" => $shorDesc,
                "DESCRIPTION_LONG" => $descLong,
                "MANUFACTURER_NAME" => $manufacturer,
                "DELIVERY_TIME" => $delivery,
                "KEYWORD" => $keywords,
                "PRODUCT_ORDER" => $productOrder,
                "PRODUCT_TYPE" => $productType,
                "PRODUCT_CATEGORY" => $productCategory
            );                         
            
            $orderUnitMethod = self::getMethod($catData['order_unit'],$methods);
            $orderUnit = $object->$orderUnitMethod();
            
            $contentUnitMethod = self::getMethod($catData['content_unit'],$methods);
            $contentUnit = $object->$contentUnitMethod();
            
            if ($catData['ou_per_cu'] || !empty($catData['ou_per_cu']) ) {
                $ouPerCuMethod = self::getMethod($catData['ou_per_cu'],$methods);
                $ouPerCu = $object->$ouPerCuMethod();
            }
            
            if ($catData['price_quantity'] || !empty($catData['price_quantity']) ) {
                $priceQuantityMethod = self::getMethod($catData['price_quantity'],$methods);
                $priceQuantity = $object->$priceQuantityMethod();
            }
            
            if ($catData['quant_min'] || !empty($catData['quant_min']) ) {
                $quantMinMethod = self::getMethod($catData['quant_min'],$methods);
                $quantMin = $object->$quantMinMethod();
            } 
            
            if ($catData['quant_interv'] || !empty($catData['quant_interv']) ) {
                $quantIntervMethod = self::getMethod($catData['quant_interv'],$methods);
                $quantInterv = $object->$quantIntervMethod();
            }    
            
            if ($catData['quant_max'] || !empty($catData['quant_max']) ) {
                $quantMaxMethod = self::getMethod($catData['quant_max'],$methods);
                $quantMax = $object->$quantMaxMethod();
            }             
            
            $productArray['PRODUCT_ORDER_DETAILS'] = array(
                'ORDER_UNIT' => $orderUnit, 
                'CONTENT_UNIT' => $contentUnit,
                'NO_CU_PER_OU' => $ouPerCu,
                'PRICE_QUANTITY' => $priceQuantity,
                'QUANTITY_MIN' => $quantMin,
                'QUANTITY_INTERVAL' => $quantInterv,
                'QUANTITY_MAX' => $quantMax
            );


            $ammountMethod = self::getMethod($catData['ammount'],$methods);
            $ammount = $object->$ammountMethod();
         
            $priceType = $catData['price_type'];
            $priceCurrency = $catData['currency'];
            
           
            if ($catData['tax'] || !empty($catData['tax']) ) {
                $taxMethod = self::getMethod($catData['tax'],$methods);
                $tax = $object->$taxMethod();
            }            
            if ($catData['price_factor'] || !empty($catData['price_factor']) ) {
                $priceFactorMethod = self::getMethod($catData['price_factor'],$methods);
                $priceFactor = $object->$priceFactorMethod();
            }
            if ($catData['lower_bound'] || !empty($catData['lower_bound']) ) {
                $lowerBoundMethod = self::getMethod($catData['lower_bound'],$methods);
                $lowerBound = $object->$lowerBoundMethod();
            } 
            if ($catData['teritories'] || !empty($catData['teritories']) ) {
                $teritoriesMethod = self::getMethod($catData['teritories'],$methods);
                $teritories = $object->$teritoriesMethod();
            } 
          
            $productArray['PRODUCT_PRICE_DETAILS'] = array(
                'PRODUCT_PRICE' => array('@attributes' => array('price_type' => $priceType), 
                    'PRICE_AMOUNT' => $ammount, 
                    'PRICE_CURRENCY' => $priceCurrency,
                    'TAX' => $tax,
                    'PRICE_FACTOR' => $priceFactor,
                    'LOWER_BOUND' => $lowerBound,
                    'TERRITORY' => $teritories          
                    )
                ); 
            
            $mimeSourceMethod = self::getMethod($catData['mime_source'],$methods);
            $mimeSource = $object->$mimeSourceMethod();

            if ($catData['mime_type'] || !empty($catData['mime_type']) ) {
                $mimeTypeMethod = self::getMethod($catData['mime_type'],$methods);
                $mimeType = $object->$mimeTypeMethod();
            } 
            if ($catData['mime_descr'] || !empty($catData['mime_descr']) ) {
                $mimeDescrMethod = self::getMethod($catData['mime_descr'],$methods);
                $mimeDescr = $object->$mimeDescrMethod();
            } 
            if ($catData['mime_alt'] || !empty($catData['mime_alt']) ) {
                $mimeAltMethod = self::getMethod($catData['mime_alt'],$methods);
                $mimeAlt = $object->$mimeAltMethod();
            } 
            if ($catData['mime_purpose'] || !empty($catData['mime_purpose']) ) {
                $mimePurposeMethod = self::getMethod($catData['mime_purpose'],$methods);
                $mimePurpose = $object->$mimePurposeMethod();
            } 

            $productArray['MIME_INFO']['MIME'] = array(
                'MIME_SOURCE' => $mimeSource,
                'MIME_TYPE' => $mimeType,
                'MIME_DESCR' => $mimeDescr,
                'MIME_ALT' => $mimeAlt,
                'MIME_PURPOSE' => $mimePurpose     
            );
                    
    //        $productArray['PRODUCT_ORDER_DETAILS'] = array('ORDER_UNIT' => $mimeSource, 'CONTENT_UNIT' => $contentUnit);
            
            
            if($catData['features']!==''){
                $features = explode(';',substr($catData['features'],0,-1));
                $featuresArray = array();
   
                foreach($features as $feature){
                    
                    $featuresMethod = self::getMethod($feature,$methods);
                    if(self::checkIfLocalized($class,$feature)){
                        $fvalueArray = array();
                        $tmp_val = array();
                        foreach($languages as $lang){
                            $fvalue = $object->$featuresMethod($lang);
                            $tmp_val[] = $lang;
                      //      $fvalueArray[] = array('@attributes' => array('locale' => $lang), '@value' => $fvalue); 
                            $fvalueArray[] = array('@value' => $fvalue);
                        }
                       // $featuresArray[] = array();
                        
                        $featuresArray[] =  array('FNAME' => $feature, 'FVALUETYPE' => implode(",", $tmp_val), 'FVALUE' => $fvalueArray );
                        
                       // $featuresArray[] =  array('FVALUETYPE' => $feature,'FVALUE' => $fvalueArray);
                    }else{
                        $fvalue = $object->$featuresMethod();
                        $featuresArray[] =  array('FNAME' => $feature,'FVALUE' => $fvalue);
                    }
                }
             
                $productArray['PRODUCT_FEATURES'] = array('FEATURE' => $featuresArray);
                
                if($catData['reference_field_0']){
                    $counter = $this->getParam('refNum');
                    $productArray['PRODUCT_REFERENCE'] = array();
                    for ($i=0; $i < $counter; $i++){
                        $ref = explode('->',$catData['reference_field_' . $i]);
                 
                        $refObjectMethod = get . ucfirst($ref[0]);
                        $refIdMethod = get . ucfirst($ref[1]);
                        $refIds = $object-> $refObjectMethod();
                  //      Logger::warning($refIds);
                        foreach ($refIds as $refId){
                            $reference = $refId->$refIdMethod();
                            $productArray['PRODUCT_REFERENCE'][]=array('@attributes' => array('type' => $catData['reference_type_' . $i]), 'PROD_ID_TO' => $reference, 'CATALOG_ID' => $catData['catalog_id_' . $i]);
                        }
                    }
                }
            }
            array_push($tNewCatalog->PRODUCT , $productArray);
            
        }
        /*----END T_NEW_CATALOG -> PRODUCT------*/
        
        /*----T_NEW_CATALOG -> PRODUCT TO CATALOG GROUP MAP------*/
        $tNewCatalog->PRODUCT_TO_CATALOGGROUP_MAP = array();
        $groupMapp = array();
        foreach($objects as $object){
            $pidMethod = self::getMethod($catData['pid'],$methods);
            $supplierPid = $object->$pidMethod();
            $groupMapp['PROD_ID'] = $supplierPid;
            $groupMapp['CATALOG_GROUP_ID'] = $object->getParentId();
            array_push($tNewCatalog->PRODUCT_TO_CATALOGGROUP_MAP , $groupMapp);
            
        }

        /*----END  T_NEW_CATALOG -> PRODUCT TO CATALOG GROUP MAP------*/
        $bmecat->T_NEW_CATALOG = $tNewCatalog;
        /*----END T_NEW_CATALOG-----*/
        
        
        $bmecat = self::objectToArray($bmecat); 
        $bmecat['@attributes'] = array('version' => '2005');
        $xml = Array2XML::createXML('BMECAT',$bmecat);
        $xmlFile = $xml->saveXML();
        file_put_contents(PIMCORE_PLUGINS_PATH . '/BMEcat/static/xml/bmetest1.xml', $xmlFile);
        echo "XML successfuly generated";
        
        
    }
    
   
    /* this should be recursive.... */
    protected function getCategories($childs,$instance){
        $array = array();
        foreach($childs as $child){
            if($child instanceof $instance){
                array_push($array, array('@attributes' => array('type'=>'node'), 'GROUP_ID' => $child->getId(),'GROUP_NAME'=>$child->getKey(),'PARENT_ID'=>$child->getParentId()));
                if($child->hasChilds()){
                    foreach($child->getChilds() as $subchild){
                        if($subchild instanceof $instance){
                             array_push($array, array('@attributes' => array('type'=>'node'), 'GROUP_ID' => $subchild->getId(),'GROUP_NAME'=>$subchild->getKey(),'PARENT_ID'=>$subchild->getParentId()));
                             if($subchild->hasChilds()){
                                 foreach($subchild->getChilds() as $subsubchild){
                                     if($subsubchild instanceof $instance){
                                         array_push($array, array('@attributes' => array('type'=>'node'), 'GROUP_ID' => $subsubchild->getId(),'GROUP_NAME'=>$subsubchild->getKey(),'PARENT_ID'=>$subsubchild->getParentId()));
                                         if($subsubchild->hasChilds()){
                                             foreach($subsubchild->getChilds() as $subsubsubchild){
                                                 if($subsubsubchild instanceof $instance){
                                                     array_push($array, array('@attributes' => array('type'=>'node'), 'GROUP_ID' => $subsubsubchild->getId(),'GROUP_NAME'=>$subsubsubchild->getKey(),'PARENT_ID'=>$subsubsubchild->getParentId()));
                                                     
                                                 }
                                             }
                                         }
                                     }
                                 }
                             }
                        }
                    }
                }
            }  
        }
        return $array;
    }
    

    
    protected function getMethod($data,$methods){
        
        $method = get . ucfirst($data);
        if(in_array($method, $methods)){
            return $method;
        }
    }
    
    protected function checkIfLocalized($class,$method){
        $localizedFields = array();
        foreach($class->fieldDefinitions['localizedfields']->childs as $field){
            

            if(!$field->childs && $field->datatype == 'data'){
                $localizedFields[] = $field->name;
            }
            else if($field->childs){
                foreach($field->childs as $child){
                    if($child->datatype = 'data'){
                        $localizedFields[] = $child->name;
                    }
                }
            }
        }
        if(in_array($method, $localizedFields)){
            return true;
        }else{
            return false;
        }
    }
    
//    protected function recursiveLocalized($fields){
//        foreach($fields as $field){
//           
//            if(!$field->childs && $field->datatype == 'data'){
//                $array[] = $field->name;
//            }else if($field->childs){
//                $array = self::recursiveLocalized($field->childs);
//            }
//        }
//        return $array;
//        
//    }

    protected function objectToArray($data){
        if ((! is_array($data)) and (! is_object($data))) return 'xxx'; //$data;

        $result = array();

        $data = (array) $data;
        foreach ($data as $key => $value) {
            if (is_object($value)) $value = (array) $value;
            if (is_array($value)) 
            $result[$key] = self::objectToArray($value);
            else
                $result[$key] = $value;
        }

        return $result;
    }

    
    public function importUploadAction() 
    {
        
        $data = file_get_contents($_FILES["Filedata"]["tmp_name"]);

        $encoding = Pimcore_Tool_Text::detectEncoding($data);
        if ($encoding) {
            $data = iconv($encoding, "UTF-8", $data);
        }

        $importFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_bmecat_" . $this->getParam("id");
        file_put_contents($importFile, $data);
        chmod($importFile, 0766);
        
        $importFileOriginal = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_bmecat_" . $this->getParam("id") . "_original";
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
        $file = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_bmecat_" . $this->getParam("id");
        
       //  Logger::warning($this->getParam("id"));
        
        // determine type
     //   $dialect = Pimcore_Tool_Admin::determineCsvDialect(PIMCORE_SYSTEM_TEMP_DIRECTORY . "/import_bmecat_" . $this->getParam("id") . "_original");
        
        $xml = simplexml_load_file($file);
        
      //  Logger::warning($xml);
        
        $arr = (array) $xml->T_NEW_CATALOG;
        
        
        $src = array();
           
        
        
        foreach($arr as $key => $value){

            if ($key == 'PRODUCT' || $key == 'PRODUCTE') {
                
                if (isset($value[0])) {
                    $src[] = self::recursiveImport($value[0], $key);
                    break;
                } else {
                    $src[] = self::recursiveImport($value, $key);
                } 
            }

        }
    //    Logger::warning($src);
        
       $this->_helper->json($src);
        
    } 
    
    protected function recursiveImport($field, $fieldKey = null)
    {
        
        if ($field instanceof SimpleXMLElement) {
                $field = (array) $field;
        }

        if (is_array($field)) {
            $childs = array();
            foreach($field as $key => $child){
                $childs[]= self::recursiveImport($child, $key);
            }
            
           $array =  self::returnFieldDataImport($field, $fieldKey, false, $childs);
           return $array;
            
        }else{

              $array = self::returnFieldDataImport($field, $fieldKey, true);
              return $array;

            }
    }
    
    protected function returnFieldDataImport($field, $fieldKey, $leaf, $children = null)
    {   
      $expanded = true;
      
      $tmlfield = $field;
      
      if (empty($field)) {       
          $tmlfield = "";
      } 

      return array(
            'text'      =>  $fieldKey,
            'value'      =>  $field,
            'iconCls' => 'pimcore_icon_' . $field->fieldtype,
            'leaf' => $leaf,
            "elementType" => "element",
            'expanded' =>$expanded,
            'allowChildren' => true,
            'children' => $children,
            'qtipCfg' => array(
                "title" => $tmlfield . " ...",
            )
            );
    }

    
    
}