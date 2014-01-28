<?php 

class ExtendedImport_Class_Data_Gender extends ExtendedImport_Class_Data_Select {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "gender";


    public function __construct() {
        $options = array(
            array("key" => "male", "value" => "male"),
            array("key" => "female", "value" => "female"),
            array("key" => "", "value" => "unknown"),
        );

        $this->setOptions($options);
    }

}
