<?php 


class ExtendedImport_Class_Data_Country extends ExtendedImport_Class_Data_Select {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "country";


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

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }
   
}
