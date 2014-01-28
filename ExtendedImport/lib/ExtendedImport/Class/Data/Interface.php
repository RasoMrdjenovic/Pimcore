<?php

interface ExtendedImport_Class_Data_Interface{

    /**
        * converts object data to a simple string value or CSV Export
        * @abstract
        * @param Object_Abstract $object
        * @return string
        */
      public function getForCsvExport($object);

       /**
        * fills object field data values from CSV Import String
        * @abstract
        * @param string $importValue
        * @param Object_Abstract $abstract
        * @return Object_Class_Data
        */
      public function getFromCsvImport($importValue);




}
 
