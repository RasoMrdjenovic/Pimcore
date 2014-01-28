<?php

class ExtendedImport_Class_Data_KeyValue extends ExtendedImport_Class_Data {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "keyValue";

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "Object_Data_KeyValue";

    /** width of key column
     * @var
     */
    public $keyWidth;

    /** width of value column
     * @var
     */
    public $valueWidth;

    /** width of description column
     * @var
     */
    public $descWidth;

    /** Height of grid
     * @var
     */
    public $height;

    /** Maximum height of grid
     * @var
     */
    public $maxheight;

    /** width of group column
     * @var
     */
    public $groupWidth;

    /** width of group description column
     * @var
     */
    public $groupDescWidth;

    /**
     * This method is called in Object_Class::save() and is used to create the database table for the localized data
     * @return void
     */
    public function classSaved($class)
    {
        // iterate over fieldDefinitions array and check if there is an item of type
        // object_Class_Data_KeyValue
        // if found, create the table, otherwise do nothing

        $keyValue = new Object_Data_KeyValue();
        $keyValue->setClass($class);
        $fieldDefinitions = $class->getFieldDefinitions();
        //TODO is this even called if type keyvalue not part of the class def?
        foreach ($fieldDefinitions as $definition) {
            if ($definition instanceof Object_Class_Data_KeyValue) {
                Logger::debug("found definition of type keyvalue, create table");
                $keyValue->createUpdateTable();
                break;
            }
        }
    }


    public function save($object, $params = array())
    {
        Logger::debug("save called");
        $pairs = $object->{  "get" . ucfirst($this->getName()) }();

        if ($pairs instanceof Object_Data_KeyValue) {
            $pairs->setClass($object->getClass());
            $pairs->setObjectId($object->getId());
            $pairs->save();
        }
    }


    /**
     * @return integer
     */
    public function getKeyWidth() {
        return $this->keyWidth;
    }

    /** Returns the width of the description column.
     * @return mixed
     */
    public function getDescWidth() {
        return $this->descWidth;
    }


    /**
     * @param integer $width
     * @return void
     */
    public function setKeyWidth($width) {
        $this->keyWidth = $this->getAsIntegerCast($width);
        return $this;
    }

    /**
     * @param integer $width
     * @return void
     */
    public function setGroupWidth($width) {
        $this->groupWidth = $this->getAsIntegerCast($width);
        return $this;
    }

    /** Sets the width of the description column.
     * @param $width
     * @return Object_Class_Data_KeyValue
     */
    public function setDescWidth($width) {
        $this->descWidth = $this->getAsIntegerCast($width);
        return $this;
    }

    /**
     * @return integer
     */
    public function getMaxheight() {
        return $this->maxheight;
    }

    /**
     * @param integer $height
     * @return void
     */
    public function setMaxheight($maxheight) {
        $this->maxheight = $this->getAsIntegerCast($maxheight);
        return $this;
    }


    /**
     * @return integer
     */
    public function getGroupWidth() {
        return $this->groupWidth;
    }




    /**
     * @return integer
     */
    public function getValueWidth() {
        return $this->valueWidth;
    }

    /**
     * @param integer $width
     * @return void
     */
    public function setValueWidth($width) {
        $this->valueWidth = $this->getAsIntegerCast($width);
        return $this;
    }


    public function load($object, $params = array())
    {
        $pairs = new Object_Data_KeyValue();
        $pairs->setClass($object->getClass());
        $pairs->setObjectId($object->getId());
        $pairs->load();

        return $pairs;
    }

    public function delete($object)
    {
        $pairs = $object->{ "get" . ucfirst($this->getName()) }();

        if ($pairs instanceof Object_Data_KeyValue) {
            $pairs->setClass($object->getClass());
            $pairs->setObjectId($object->getId());
            $pairs->delete();

        }
    }

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param Object_Data_KeyValue $data
     * @param null|Object_Abstract $object
     * @return tbd
     */
    public function getDataForEditmode($data, $object = null) {
        $result = array();
        if (!$data) {
            return $result;
        }

        $properties = $data->getProperties();

        foreach ($properties as $key => $property) {

            // Logger::debug($property);
            $key = $property["key"];
            $keyConfig = Object_KeyValue_KeyConfig::getById($key);
            $property["type"] = $keyConfig->getType();
            $property["possiblevalues"] = $keyConfig->getPossibleValues();
            $groupId = $keyConfig->getGroup();

            if ($groupId) {
                $group = Object_KeyValue_GroupConfig::getById($groupId);
                $property["group"] = $group->getName();
                $property["groupDesc"] = $group->getDescription();
            }


            $property["keyName"] = $keyConfig->getName();
            $property["keyDesc"] = $keyConfig->getDescription();
            $result[] = $property;
        }
        return $result;
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @return string
     */
    public function getDataFromEditmode($data, $object = null) {

        $class = $object->getClass();
        $objectId = $object->getId();

        $pairs = array();
        foreach ($data as $pair) {
            $key = $pair["key"];
            $pairs[$key] = $pair;
        }


        $keyValue = new Object_Data_KeyValue();

        $keyValue->setObjectId($objectId);
        $keyValue->setProperties($pairs);

        return $keyValue;
    }


    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false){

        // TODO throw exception if not valid
    }

    public function isDiffChangeAllowed() {
        return true;
    }



    public function getDiffDataFromEditmode($data, $object = null) {
        $result = array();

        // filter everything out that doesn't exist anymore
        foreach ($data as $pair) {
            if ($pair["data"]) {
                $result[] = $pair["data"];
            }
        }
        $dataFromEditMode = $this->getDataFromEditmode($result, $object);
        return $dataFromEditMode;
    }

    public function getDiffDataForEditMode($data, $object = null) {

        if (!$data) {
            return array();
        }

        $properties = $data->getProperties();
        $result = array();

        foreach ($properties as $key => $property) {

            $key = $property["key"];

            $diffdata = array();
            $diffdata["field"] = $this->getName();
            $diffdata["key"] = $this->getName() . "~" . $key;
            $diffdata["type"] = $this->fieldtype;
            unset($property["id"]);
            unset($property["o_id"]);
            unset($property["source"]);
            $diffdata["data"] = $property;


            $keyConfig = Object_KeyValue_KeyConfig::getById($key);
            $keyName = $keyConfig->getName();

            $prettyValue = $property["value"];
            if ($keyConfig->getType() == "select") {
                $possibleValues = Zend_Json::decode($keyConfig->getPossibleValues());

                foreach($possibleValues as $pValue) {
                    if ($pValue["key"] == $property["value"]) {
                        $prettyValue = $pValue["value"];
                        break;
                    }
                }
            } else if ($keyConfig->getType() == "translated") {
                $translatedValue = $property["translated"];
                if ($translatedValue) {
                    $prettyValue = $translatedValue;
                }
            }

            $diffdata["value"] = $prettyValue;
            $diffdata["title"] = $keyName;
            $diffdata["tooltip"] = $keyName;
            $keyDescription = $keyConfig->getDescription();
            if (!empty($keyDescription)) {
                $diffdata["title"] = $keyDescription;
            }
            $diffdata["disabled"] = !($this->isDiffChangeAllowed());
            $result[] = $diffdata;
        }

        return $result;
    }



    /**
     * converts data to be exposed via webservices
     * @param string $object
     * @return mixed
     */
    public function getForWebserviceExport($object)
    {
        $key = $this->getName();
        $getter = "get" . ucfirst($key);
        $data = $object->$getter();
        if ($data) {
            $result = array();
            foreach ($data->arr as $item) {
                $keyConfig = Object_KeyValue_KeyConfig::getById($item["key"]);
                $keyName = $keyConfig->getName();
                $resultItem = array(
                    "id" => $item["key"],
                    "name" => $keyName,
                    "value" => $item["value"]
                );

                if ($keyConfig->getType() == "translated") {
                    $resultItem["translated"] = $item["translated"];
                }

                $result[] = $resultItem;
            }
            return $result;
        }
    }

    /**
     * converts data to be imported via webservices
     * @param mixed $value
     * @return mixed
     */
    public function getFromWebserviceImport($value, $relatedObject = null, $idMapper = null)
    {
        if ($value) {
            $pairs = array();


            foreach ($value as $property) {

                if (key_exists("id", $property)) {
                    $property = (array) $property;
                    $id = $property["id"];
                    $property["key"] = $id;
                    unset($property["id"]);

                    $key = $property["key"];
                    if ($idMapper != null) {
                        $newKey = $idMapper->getMappedId("kvkey", $key);
                        if (!$newKey) {
                            if ($idMapper->ignoreMappingFailures()) {
                                $idMapper->recordMappingFailure($relatedObject->getId(), "kvkey", $key);
                                continue;
                            } else {
                                throw new Exception("Key " . $key . " could not be mapped");
                            }
                        }
                        $property["key"] = $newKey;
                    }
                    $pairs[] = $property;
                }
            }

            $keyValueData = new Object_Data_KeyValue();
            $keyValueData->setProperties($pairs);
            $keyValueData->setClass($relatedObject->getClass());
            $keyValueData->setObjectId($relatedObject->getId());
            return ($keyValueData);
        }
    }

}
