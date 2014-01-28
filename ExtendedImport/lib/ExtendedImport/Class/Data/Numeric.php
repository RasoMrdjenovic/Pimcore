<?php 

class ExtendedImport_Class_Data_Numeric extends ExtendedImport_Class_Data {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "numeric";

    /**
     * @var float
     */
    public $width;

    /**
     * @var float
     */
    public $defaultValue;

    /**
     * Type for the column to query
     *
     * @var string
     */
    public $queryColumnType = "double";

    /**
     * Type for the column
     *
     * @var string
     */
    public $columnType = "double";

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "float";

    /**
     * @return integer
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param integer $width
     * @return void
     */
    public function setWidth($width) {
        $this->width = $this->getAsIntegerCast($width);
        return $this;
    }

    /**
     * @return integer
     */
    public function getDefaultValue() {
        if($this->defaultValue !== null) {
            return (double) $this->defaultValue;
        }
    }

    /**
     * @param integer $defaultValue
     * @return void
     */
    public function setDefaultValue($defaultValue) {
        if(strlen(strval($defaultValue)) > 0) {
            $this->defaultValue = $defaultValue;
        }
        return $this;
    }

    /**
     * @see Object_Class_Data::getDataForResource
     * @param float $data
     * @param null|Object_Abstract $object
     * @return float
     */
    public function getDataForResource($data, $object = null) {

        if(is_numeric($data)) {
           return (float) $data; 
        }
        return null;
    }

    /**
     * @see Object_Class_Data::getDataFromResource
     * @param float $data
     * @return float
     */
    public function getDataFromResource($data) {
        if(is_numeric($data)) {
            return (float) $data;
        }
        return $data;
    }

    /**
     * @see Object_Class_Data::getDataForQueryResource
     * @param float $data
     * @param null|Object_Abstract $object
     * @return float
     */
    public function getDataForQueryResource($data, $object = null) {
        return $this->getDataForResource($data, $object);
    }

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param float $data
     * @param null|Object_Abstract $object
     * @return float
     */
    public function getDataForEditmode($data, $object = null) {
        return $this->getDataForResource($data, $object);
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param float $data
     * @param null|Object_Abstract $object
     * @return float
     */
    public function getDataFromEditmode($data, $object = null) {
        return $this->getDataFromResource($data);
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param float $data
     * @return float
     */
    public function getVersionPreview($data) {
        return $data;
    }

    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false){

        if(!$omitMandatoryCheck and $this->getMandatory() and $data === NULL){
            throw new Exception("Empty mandatory field [ ".$this->getName()." ]");
        }

        if(!empty($data) and !is_numeric($data)){
            throw new Exception("invalid numeric data");
        }
    }

    /**
     * converts object data to a simple string value or CSV Export
     * @abstract
     * @param Object_Abstract $object
     * @return string
     */
    public function getForCsvExport($object) {
        $key = $this->getName();
        $getter = "get".ucfirst($key);
        return strval($object->$getter());
    }


    /**
     * fills object field data values from CSV Import String
     * @param string $importValue
     * @return double
     */
    public function getFromCsvImport($importValue) {
        $value = (double) str_replace(",",".",$importValue);
        return $value;
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }

}
