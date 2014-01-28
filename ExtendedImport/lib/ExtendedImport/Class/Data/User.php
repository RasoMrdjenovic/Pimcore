<?php

class ExtendedImport_Class_Data_User extends ExtendedImport_Class_Data_Select {

    /**
     * Static type of this element
     *
     * @var string
     */
    public $fieldtype = "user";


    /**
     * @see Object_Class_Data::getDataFromResource
     * @param string $data
     * @return string
     */
    public function getDataFromResource($data) {

        if(!empty($data)) {
            try {
                $this->checkValidity($data, true);
            } catch (Exception $e) {
                $data = null;
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @param null $object
     */
    public function getDataForResource($data, $object = null) {
        if(!empty($data)) {
            try {
                $this->checkValidity($data, true);
            } catch (Exception $e) {
                $data = null;
            }
        }

        return $data;
    }


    /**
     *
     */
    public function configureOptions() {

        $list = new User_List();
        $list->setOrder("asc");
        $list->setOrderKey("name");
        $users = $list->load();

        $options = array();
        if (is_array($users) and count($users) > 0) {
            foreach ($users as $user) {
                if($user instanceof User) {
                    $value = $user->getName();
                    $first = $user->getFirstname();
                    $last = $user->getLastname();
                    if (!empty($first) or !empty($last)) {
                        $value .= " (" . $first . " " . $last . ")";
                    }
                    $options[] = array(
                        "value" => $user->getId(),
                        "key" => $value
                    );
                }
            }
        }
        $this->setOptions($options);
    }


    /**
     * Checks if data is valid for current data field
     *
     * @param mixed $data
     * @param boolean $omitMandatoryCheck
     * @throws Exception
     */
    public function checkValidity($data, $omitMandatoryCheck = false){

        if(!$omitMandatoryCheck and $this->getMandatory() and empty($data)){
            throw new Exception("Empty mandatory field [ ".$this->getName()." ]");
        }
        
        if(!empty($data)){
            $user = User::getById($data);
            if(!$user instanceof User){
                throw new Exception("invalid user reference");
            }
        }
    }

    public function __wakeup() {
        $options = $this->getOptions();
        if(Pimcore::inAdmin() || empty($options)) {
            $this->configureOptions();
        }
    }


}
