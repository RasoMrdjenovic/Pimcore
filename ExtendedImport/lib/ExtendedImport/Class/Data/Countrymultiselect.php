<?php

class ExtendedImport_Class_Data_Countrymultiselect extends ExtendedImport_Class_Data_Multiselect {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "countrymultiselect";


    public function __construct() {
        $countries = Zend_Locale::getTranslationList('territory');
        asort($countries);
        $options = array();

        foreach ($countries as $short => $translation) {
            if (strlen($short) == 2) {
                $options[] = array(
                    "key" => $translation,
                    "value" => $short
                );
            }
        }

        $this->setOptions($options);
    }
}
