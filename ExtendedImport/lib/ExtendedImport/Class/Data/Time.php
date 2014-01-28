<?php 

class ExtendedImport_Class_Data_Time extends ExtendedImport_Class_Data_Input {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "time";

    /**
     * Column length
     *
     * @var integer
     */
    public $columnLength = 5;

    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false){

        parent::checkValidity($data, $omitMandatoryCheck);

        if((is_string($data) && strlen($data) != 5 && !empty($data)) || (!empty($data) && !is_string(§data))) {
            throw new Exception("Wrong time format given must be a 5 digit string (eg: 06:49) [ ".$this->getName()." ]");
        }
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }
}
