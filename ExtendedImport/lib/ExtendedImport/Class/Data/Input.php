<?php 

class ExtendedImport_Class_Data_Input extends ExtendedImport_Class_Data {
/**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "input";

    /**
     * @var integer
     */
    public $width;

    /**
     * Type for the column to query
     *
     * @var string
     */
    public $queryColumnType = "varchar";

    /**
     * Type for the column
     *
     * @var string
     */
    public $columnType = "varchar";
    
    /**
     * Column length
     *
     * @var integer
     */
    public $columnLength = 255;

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "string";

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
        $this->width = $width;
        return $this;
    }

    /**
     * @see Object_Class_Data::getDataForResource
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForResource($data, $object = null) {
        return $data;
    }

    /**
     * @see Object_Class_Data::getDataFromResource
     * @param string $data
     * @return string
     */
    public function getDataFromResource($data) {
        return $data;
    }

    /**
     * @see Object_Class_Data::getDataForQueryResource
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForQueryResource($data, $object = null) {
        return $data;
    }

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForEditmode($data, $object = null) {
        return $this->getDataForResource($data, $object);
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataFromEditmode($data, $object = null) {
        return $this->getDataFromResource($data);
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param string $data
     * @return string
     */
    public function getVersionPreview($data) {
        return $data;
    }
    
    /**
     * @return integer
     */
    public function getColumnLength() {
        return $this->columnLength;
    }
    
    /**
     * @param integer $columnLength
     */
    public function setColumnLength($columnLength) {
        if($columnLength) {
            $this->columnLength = $columnLength;
        }
        return $this;
    }
    
    /**
     * @return string
     */
    public function getColumnType() {
        return $this->columnType . "(" . $this->getColumnLength() . ")";
    }

    /**
     * @return string
     */
    public function getQueryColumnType() {
        return $this->queryColumnType . "(" . $this->getColumnLength() . ")";
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }

}
