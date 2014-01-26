 <?php

class MyPlugin_AdminController extends Pimcore_Controller_Action_Admin {
    

    public function installAction(){
        $class = Object_Class::getByName('news');
        if(!$class){
            $install = new MyPlugin_Install();
            $newsClass = $install->createClass();
            $install->setClassmap();
            $newsFolders = $install->createFolders();
            $install->createCustomView($newsFolders, array(
                $newsClass->getId()
                
            ));
            echo "News successfuly installed";
        }
        else{
            echo 'News already installed';
        }
    }
    
    
    
   
    public function roletreeAction(){
        $src = array();
        $roles = new Object_Role_List();
        
        foreach ($roles as $role){   
            $src[] = array(
            'text'      =>    $role->getName(),
            'id'        => $role->getId(),
            'iconCls' => 'pimcore_icon_roles',
            'leaf' => true,
            "elementType" => "role",
            'allowChildren' => false,
            'qtipCfg' => array(
                "title" => "ID: " . $role->getId()
            )
            
        );     
        }
        

       return $this->_helper->json($src);
        
    }
    
    public function getroleAction(){
         $role = Object_Role::getById(intval($this->getParam("id")));

        // workspaces
//        $types = "document";
//        foreach ($types as $type) {
//            $workspaces = $role->{"getWorkspaces" . ucfirst($type)}();
//            foreach ($workspaces as $workspace) {
//                $el = Element_Service::getElementById($type, $workspace->getCid());
//                if($el) {
//                    // direct injection => not nice but in this case ok ;-)
//                    $workspace->path = $el->getFullPath();
//                }
//            }
//        }

        // get available permissions
        $availableUserPermissionsList = new User_Permission_Definition_List();
        $availableUserPermissions = $availableUserPermissionsList->load();
  
        $settingsList = new Object_RoleSettings_List();
        $settings = $settingsList->load();

//      $list = new Document_List();
//      $documents= $list->load();

        $this->_helper->json(array(
            "success" => true,
            "role" => $role,
//            "permissions" => $role->generatePermissionList(),
            "availablePermissions" => $availableUserPermissions,
            "documents" => $settings
        ));
    }
    
    public function getworkspacesAction(){
        $role = Object_Role::getByName($this->getParam("name"),1);

        $objects = array();
//        $roleObjects = $role->getWorkspacesObject();
//         $testObject = $roleObjects[0];
         $objects[] = array(
             "path" => 'ccrc',
             "list"=> true
         );
     return   $this->_helper->json(array(
                "success" => true,
                "workspacesObject" => $objects
            ));
        
    }

    public function addroleAction(){
        
        $name = $this->getParam("name");
        $parent = Object_Abstract::getByPath('/users/roles');
        try{
            $role = Object_Role::create();
            $role->setName($name);
            $role->setParentId($parent->getId());
            $role->setKey($name);
            $role->setPublished(true);
            $role->save();

            $this->_helper->json(array(
                    "success" => true,
                    "id" => $role->getId()
                ));
        }
         catch (Exception $e){
            $this->_helper->json(array("success" => false, "message" => $e->getMessage()));
        }
        $this->_helper->json(false);
    }
    
    public function addsettingAction(){
        $name = $this->getParam("name");
        $parent = Object_Abstract::getByPath('/users/roles/settings');
        $roleSetting = Object_RoleSettings::create();
        $roleSetting->setRoleSettings($name);
        $roleSetting->setParentId($parent->getId());
        $roleSetting->setKey(self::parseKey($name));
        $roleSetting->setPublished(true);
        
        if($roleSetting->save()){
            echo "Role setting saved";
            
        }
    }
    public function deleteroleAction(){
        $user = Object_Abstract::getById(intval($this->getParam("id")));
        $user->delete();

        $this->_helper->json(array("success" => true));
    }
    
    public function treeAction(){
        
        $src = array();
        $news = new Object_Role_List();
        
        foreach ($news as $news){   
            $src[] = array(
            'text'      =>    $news->getName(),
            'id'        => $news->getId(),
            'cls' => 'file',
            'leaf' => true    
            
        );     
        }
        
      
            
       
       return $this->_helper->json($src);
        
    }
    
    public function updateAction(){
      $role = Object_Role::getById(intval($this->getParam("id")));
      $values = Zend_Json::decode($this->getParam("data"));
      $settings = array();
      foreach ($values as $key=>$value){
          if($value == 1){
          $settings []= $key;
          }
      }
      if($settings){
          $settings = implode(',', $settings);
      }
      

        $workspaces = Zend_Json::decode($this->getParam("workspaces"));
        $docArray = array();
        foreach($workspaces as $documents){
            foreach($documents as $document){
                if($document["visible"] == 1)
                   $doc = Document::getByPath($document["path"]);
                   $docArray[] = $doc;
            }
        }
     
        $role->setSettings($settings);
        $role->setWorkspacesDocument($docArray);
        if($role->save()){
          echo "Role successfuly saved";
      }
    }
    public function parseKey($key){
        $key = str_replace(' ', '_', $key);
        $key = strtolower(utf8_encode($key));
        return $key;
    }

   
}
