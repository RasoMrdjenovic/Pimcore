<?php 

class ExtendedImport_Class_Data_Language extends ExtendedImport_Class_Data_Select {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "language";

    /**
     * @var bool
     */
    public $onlySystemLanguages = false;


    public function configureOptions () {

        $validLanguages = (array) Pimcore_Tool::getValidLanguages();
        $locales = Pimcore_Tool::getSupportedLocales();
        $options = array();

        foreach ($locales as $short => $translation) {

            if($this->getOnlySystemLanguages()) {
                if(!in_array($short, $validLanguages)) {
                    continue;
                }
            }

            $options[] = array(
                "key" => $translation,
                "value" => $short
            );
        }

        $this->setOptions($options);
    }

    /**
     * @return bool
     */
    public function getOnlySystemLanguages () {
        return $this->onlySystemLanguages;
    }

    /**
     * @param bool $value
     */
    public function setOnlySystemLanguages ($value) {
        $this->onlySystemLanguages = (bool) $value;
        return $this;
    }



    /*public function __sleep () {
        //$this->configureOptions();

        return get_object_vars($this);
    }
    */

    public function __wakeup () {
        $this->configureOptions();
    }

   
}
