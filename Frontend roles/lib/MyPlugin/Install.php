<?php 
class MyPlugin_Install
{

    protected $_user;

    public function createClass()
    {
        $conf = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH . "/MyPlugin/install/class_news.xml", null, true);

//        if ($name == 'BlogEntry' && !class_exists('Tagfield_Plugin')) {
//            unset($conf->layoutDefinitions->childs->childs->{4});
//        }

        $class = Object_Class::create();
        $class->setName('news');
        $class->setUserOwner($this->_getUser()->getId());
        $class->setLayoutDefinitions(
            Object_Class_Service::generateLayoutTreeFromArray(
                $conf->layoutDefinitions->toArray()
            )
        );
        $class->setIcon($conf->icon);
        $class->setAllowInherit($conf->allowInherit);
        $class->setAllowVariants($conf->allowVariants);
        $class->setParentClass($conf->parentClass);
        $class->setPreviewUrl($conf->previewUrl);
        $class->setPropertyVisibility($conf->propertyVisibility);
        $class->save();

        return $class;
    }
    
    public function setClassmap()
    {
        $classmapXml = PIMCORE_CONFIGURATION_DIRECTORY . '/classmap.xml';

        try {
            $conf = new Zend_Config_Xml($classmapXml);
            $classmap = $conf->toArray();
        } catch(Exception $e) {
            $classmap = array();
        }

        $classmap['Object_News'] = 'news';

        $writer = new Zend_Config_Writer_Xml(array(
            'config' => new Zend_Config($classmap),
            'filename' => $classmapXml
        ));
        $writer->write();
    }
    
    public function createFolders()
    {
        $root = Object_Folder::create(array(
            'o_parentId' => 1,
            'o_creationDate' => time(),
            'o_userOwner' => $this->_getUser()->getId(),
            'o_userModification' => $this->_getUser()->getId(),
            'o_key' => 'news',
            'o_published' => true,
        ));
        Object_Folder::create(array(
            'o_parentId' => $root->getId(),
            'o_creationDate' => time(),
            'o_userOwner' => $this->_getUser()->getId(),
            'o_userModification' => $this->_getUser()->getId(),
            'o_key' => 'news',
            'o_published' => true,
        ));
      

        return $root;
    }
    
    public function createCustomView($rootFolder, array $classIds)
    {
        $customViews = Pimcore_Tool::getCustomViewConfig();
        if (!$customViews) {
            $customViews = array();
            $customViewId = 1;
        } else {
            $last = end($customViews);
            $customViewId = $last['id'] + 1;
        }
        $customViews[] = array(
            'name' => 'News',
            'condition' => '',
            'icon' => '/pimcore/static/img/icon/newspaper.png',
            'id' => $customViewId,
            'rootfolder' => $rootFolder->getFullPath(),
            'showroot' => false,
            'classes' => implode(',', $classIds),
        );
        $writer = new Zend_Config_Writer_Xml(array(
            'config' => new Zend_Config(array('views'=> array('view' => $customViews))),
            'filename' => PIMCORE_CONFIGURATION_DIRECTORY . '/customviews.xml'
        ));
        $writer->write();
    }
    
    
    protected function _getUser()
    {
        if (!$this->_user) {
            $this->_user = Zend_Registry::get('pimcore_admin_user');
        }

        return $this->_user;
    }
    
 
    
}



?>