pimcore.registerNS("pimcore.plugin.myPlugin");

pimcore.plugin.myPlugin = Class.create(pimcore.plugin.admin, {
    getClassName: function() {
        return "pimcore.plugin.myPlugin";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
        this.panels = {};
    },
 
    pimcoreReady: function (params,broker){
        // add a sub-menu item under "Extras" in the main menu
        var toolbar = Ext.getCmp("pimcore_panel_toolbar");
        
        var action = new Ext.Action({
            id: "my_plugin_menu_item",
            text: "FrontEnd roles",
            iconCls:"pimcore_icon_roles",
            handler: this.showTab
        });
        
    

        toolbar.items.items[1].menu.add(action);
    },


     



    showTab: function() {


        myPlugin.panel = new Ext.Panel({
            id:         "frontend_role_panel",
            title:      "Roles",
            iconCls:    "pimcore_icon_roles",
            border:     false,
            layout:     "border",
            closable:   true,
            items:      [myPlugin.getRoleTree(), myPlugin.getEditPanel()]
        });

        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        tabPanel.add(myPlugin.panel);
        tabPanel.activate("frontend_role_panel");

        pimcore.layout.refresh();
    },
    
  
    getRoleTree: function () {
            if (!this.tree) {
                this.tree = new Ext.tree.TreePanel({
                    id: "pimcore_frontend_panel_roles_tree",
                    region: "west",
                    useArrows:true,
                    autoScroll:true,
                    animate:true,
                    enableDD:true,
                    ddGroup: "roles",
                    containerScroll: true,
                    border: true,
                    split:true,
                    width: 150,
                    minSize: 100,
                    maxSize: 350,
                    root: {
                        nodeType: 'async',
                        draggable:false,
                        id: '0',
                        text: t("all_roles"),
                        allowChildren: true
                    },
                    loader: new Ext.tree.TreeLoader({
                        dataUrl: '/plugin/MyPlugin/admin/roletree',
                        requestMethod: "GET",
                        baseAttrs: {
                            listeners: this.getTreeNodeListeners(),
                            reference: this,
                            allowDrop: true,
                            allowChildren: true,
                            isTarget: true
                        }
                    })
                });


                this.tree.on("render", function () {
                    this.getRootNode().expand();
                });
            }

            return this.tree;
        },
        
      getTreeNodeListeners: function () {
        var treeNodeListeners = {
            'click' : this.onTreeNodeClick.bind(this),
            "contextmenu": this.onTreeNodeContextmenu,
//            "move": this.onTreeNodeMove
        };

        return treeNodeListeners;
      }, 
                
                
      onTreeNodeClick: function (node) {
  
  

        if(!node.attributes.allowChildren && node.id > 0) {
            var rolePanelKey = "role_" + node.id;
            if(this.panels[rolePanelKey]) {
                this.panels[rolePanelKey].activate();
            } else {
                var rolePanel = new pimcore.plugin.myPlugin.tab(this, node.id);
                this.panels[rolePanelKey] = rolePanel;
            }
        }
    },     
                
      onTreeNodeContextmenu: function () {

//      var user = pimcore.globalmanager.get("user");
//        if (user.admin) {

            this.select();
            var menu = new Ext.menu.Menu();

            if (this.allowChildren) {
                
                menu.add(new Ext.menu.Item({
                    text: t('add_role'),
                    iconCls: "pimcore_icon_role_add",
                    listeners: {
                        "click": this.attributes.reference.add.bind(this, "role")
                    }
                }));
            }

            menu.add(new Ext.menu.Item({
                text: t('delete'),
                iconCls: "pimcore_icon_delete",
                listeners: {
                    "click": this.attributes.reference.remove.bind(this)
                }
            }));

//            if(typeof menu.items != "undefined" && typeof menu.items.items != "undefined"
//                                                                        && menu.items.items.length > 0) {
                menu.show(this.ui.getAnchor());
//            }
//        }
        
    },      
            
       
       remove: function () {
        Ext.Ajax.request({
            url: "/plugin/MyPlugin/admin/deleterole",
            params: {
                id: this.id
            }
        });
     
        this.remove();
    },

    add: function (type) {
        Ext.MessageBox.prompt(t('add'), t('please_enter_the_name'), function (button, value, object) {
            if(button=='ok' && value != ''){
                Ext.Ajax.request({
                    url: "/plugin/MyPlugin/admin/addrole",
                    params: {
                        parentId: this.id,
                        type: type,
                        name: value,
                        active: 1
                    },
                    success:  function(response){
                        Ext.Msg.alert('Status',response.responseText );
                        this.tree.reload();
                   }
//                            this.attributes.reference.addComplete.bind(this.attributes.reference, this.id)
                });
            }
        }.bind(this));
    },
           
        getEditPanel: function () {
        if (!this.editPanel) {
            this.editPanel = new Ext.TabPanel({
               activeTab: 0,
                items: [],
                region: 'center'
            });
        }

        return this.editPanel;
    },
            
      activate: function () {
        Ext.getCmp("pimcore_panel_tabs").activate("frontend_role_panel");
    }      

   

});

var myPlugin = new pimcore.plugin.myPlugin();
