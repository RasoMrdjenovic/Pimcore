<?php 

class ExtendedImport_Class_Data_Password extends ExtendedImport_Class_Data {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "password";

    /**
     * @var integer
     */
    public $width;

    /**
     * Type for the column to query
     *
     * @var string
     */
    public $queryColumnType = "varchar(32)";

    /**
     * Type for the column
     *
     * @var string
     */
    public $columnType = "varchar(32)";

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
        $this->width = $this->getAsIntegerCast($width);
        return $this;
    }

    /**
     * @see Object_Class_Data::getDataForResource
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForResource($data, $object = null) {


        // is already a md5 string
        if(strlen($data) == 32) {
            return $data;
        } else if (empty($data)) {
            return null;
        }

        $hashed = md5($data);

        // set the hashed password back to the object, to be sure that is not plain-text after the first save
        // this is especially to aviod plaintext passwords in the search-index see: PIMCORE-1406
        if($object) {
            $setter = "set" . ucfirst($this->getName());
            $object->$setter($hashed);
        }
        return $hashed;
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
        return $this->getDataForResource($data, $object);
    }

    /**
     * @see Object_Class_Data::getDataForEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForEditmode($data, $object = null) {
        return $data;
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataFromEditmode($data, $object = null) {
        return $data;
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param string $data
     * @return string
     */
    public function getVersionPreview($data) {
        return "******";
    }

    public function getDataForGrid ($data, $object) {
        return "******";
    }




    /**
     * fills object field data values from CSV Import String
     * @abstract
     * @param string $importValue
     * @param Object_Abstract $abstract
     * @return Object_Class_Data
     */
    public function getFromCsvImport($importValue) {
        return $this->getDataFromEditmode($importValue);
    }

    /**
     * converts data to be exposed via webservices
     * @param string $object
     * @return mixed
     */
    public function getForWebserviceExport ($object) {
        //neither hash nor password is exported via WS
        return null;
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }

    /** See parent class.
     * @param $data
     * @param null $object
     * @return null|Pimcore_Date
     */

    public function getDiffDataFromEditmode($data, $object = null) {
        return $data[0]["data"];
    }


    /** See parent class.
     * @param mixed $data
     * @param null $object
     * @return array|null
     */
    public function getDiffDataForEditMode($data, $object = null) {
        $diffdata = array();
        $diffdata["data"] = $data;
        $diffdata["disabled"] = !($this->isDiffChangeAllowed());
        $diffdata["field"] = $this->getName();
        $diffdata["key"] = $this->getName();
        $diffdata["type"] = $this->fieldtype;

        if ($data) {
            $diffdata["value"] = $this->getVersionPreview($data);
            // $diffdata["value"] = $data;
        }

        $diffdata["title"] = !empty($this->title) ? $this->title : $this->name;

        $result = array();
        $result[] = $diffdata;
        return $result;
    }

}
