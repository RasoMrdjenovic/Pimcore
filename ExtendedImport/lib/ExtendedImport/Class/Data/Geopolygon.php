<?php 

class ExtendedImport_Class_Data_Geopolygon extends ExtendedImport_Class_Data_Geo_Abstract {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "geopolygon";

    /**
     * Type for the column to query
     *
     * @var string
     */
    public $queryColumnType = "longtext";

    /**
     * Type for the column
     *
     * @var string
     */
    public $columnType = "longtext";

    /**
     * Type for the generated phpdoc
     *
     * @var string
     */
    public $phpdocType = "array";


    /**
     * @see Object_Class_Data::getDataForResource
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataForResource($data, $object = null) {
        return Pimcore_Tool_Serialize::serialize($data);
    }

    /**
     * @see Object_Class_Data::getDataFromResource
     * @param string $data
     * @return string
     */
    public function getDataFromResource($data) {
        return Pimcore_Tool_Serialize::unserialize($data);
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
        if (!empty($data)) {
            if (is_array($data)) {
                $points = array();
                foreach ($data as $point) {
                    $points[] = array(
                        "latitude" => $point->getLatitude(),
                        "longitude" => $point->getLongitude()
                    );
                }
                return $points;
            }
        }
        return;
    }

    /**
     * @see Object_Class_Data::getDataFromEditmode
     * @param string $data
     * @param null|Object_Abstract $object
     * @return string
     */
    public function getDataFromEditmode($data, $object = null) {

        if (is_array($data)) {
            $points = array();
            foreach ($data as $point) {
                $points[] = new Object_Data_Geopoint($point["longitude"], $point["latitude"]);
            }
            return $points;
        }
        return;
    }

    /**
     * @see Object_Class_Data::getVersionPreview
     * @param string $data
     * @return string
     */
    public function getVersionPreview($data) {
        return "";
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
        $data = $object->$getter();
        if (!empty($data)) {
            $dataArray = $this->getDataForEditmode($data);
            $rows = array();
            if (is_array($dataArray)) {
                foreach ($dataArray as $point) {
                    $rows[] = implode(";", $point);
                }
                return implode("|", $rows);
            }


        }
        return null;
    }

    /**
     * fills object field data values from CSV Import String
     * @abstract
     * @param string $importValue
     * @param Object_Abstract $abstract
     * @return Object_Class_Data
     */
    public function getFromCsvImport($importValue) {
        $rows = explode("|", $importValue);
        $points = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $coords = explode(";", $row);
                $points[] = new  Object_Data_Geopoint($coords[1], $coords[0]);
            }
        }
        return $points;
    }


    /**
     * converts data to be exposed via webservices
     * @param string $object
     * @return mixed
     */
    public function getForWebserviceExport($object) {
        $key = $this->getName();
        $getter = "get".ucfirst($key);
        $data = $object->$getter();
        if (!empty($data)) {
            return $this->getDataForEditmode($data, $object);
        } else return null;
    }

    /**
     * converts data to be imported via webservices
     * @param mixed $value
     * @return mixed
     */
    public function getFromWebserviceImport($value) {
        if(empty($value)){
            return null;
        } else if (is_array($value)) {
            $points = array();
            foreach ($value as $point) {
                $point = (array) $point;
                if($point["longitude"]!=null and  $point["latitude"]!=null){
                    $points[] = new Object_Data_Geopoint($point["longitude"], $point["latitude"]);
                } else {
                    throw new Exception("cannot get values from web service import - invalid data");
                }
            }
            return $points;
        } else {
            throw new Exception("cannot get values from web service import - invalid data");
        }
    }

    /** True if change is allowed in edit mode.
     * @return bool
     */
    public function isDiffChangeAllowed() {
        return true;
    }

    /** Generates a pretty version preview (similar to getVersionPreview) can be either html or
     * a image URL. See the ObjectMerger plugin documentation for details
     * @param $data
     * @param null $object
     * @return array|string
     */
    public function getDiffVersionPreview($data, $object = null) {
        if (!empty($data)) {
            $line = "";
            $isFirst = true;
            if (is_array($data)) {
                $points = array();
                foreach ($data as $point) {
                    if (!$isFirst) {
                        $line .= " ";
                    }
                    $line .= $point->getLatitude() . "," . $point->getLongitude();
                    $isFirst = false;
                }


                return $line;
            }
        }
        return;
    }

}
