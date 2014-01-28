pimcore.registerNS("pimcore.plugin.workspace.panel");
pimcore.plugin.workspace.panel = Class.create( {

    treeDataUrl: "/plugin/ExtendedImport/admin/tree-get-childs-by-id/",

    initialize: function(config) {
        
        this.importId = uniqid();
        this.getTabPanel();
        this.classCombobox;
        this.panels = {};

        this.position = "left";

        if (!config) {
            this.config = {
                rootId: 1,
                rootVisible: true,
                allowedClasses: "all",
                loaderBaseParams: {},
                treeId: "pimcore_panel_tree_objects",
                treeIconCls: "pimcore_icon_object",
                treeTitle: t('objects'),
                parentPanel: Ext.getCmp("pimcore_panel_tree_left"),
                index: 1
            };
        }
        else {
            this.config = config;
        }
        
        pimcore.layout.treepanelmanager.register(this.config.treeId);
        
        // get root node config
        Ext.Ajax.request({
            url: "/plugin/ExtendedImport/admin/tree-get-root",
            params: {
                id: this.config.rootId
            },
            success: function (response) {
                var res = Ext.decode(response.responseText);
                var callback = function () {};
                if(res["id"]) {
                    callback = this.init.bind(this, res);
                }
                pimcore.layout.treepanelmanager.initPanel(this.config.treeId, callback);
            }.bind(this)
        });
    },


    getEditPanel: function () {

    var classStore = pimcore.globalmanager.get("object_types_store");
    var toolbarConfig;
    
    this.classCombobox = new Ext.form.ComboBox({
                        name: "selectClass",
                        id: "combo_classes",
                        listWidth: 'auto',
                        store: classStore,
                        valueField: 'translatedText',
                        displayField: 'translatedText',
                        triggerAction: 'all',                      
                        listeners: {
//                            "select": this.changeClassSelect.bind(this)
                        }
                    });

        if (!this.editPanel) {
            this.editPanel = new Ext.TabPanel({
                activeTab: 0,
                items: [],
                region: 'center'
                        
            });
        }

        return this.editPanel;
    },

    getTabPanel: function () {

        if (!this.panel) {
            this.panel = new Ext.Panel({
                id: "pimcore_import_panel",
                title: "import/export",
                iconCls: "pimcore_icon_object_csv_import",
                border: false,
                layout: "border",
                closable:true,
                items: [this.init(),this.getEditPanel()]
            });

            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.panel);
            tabPanel.activate("pimcore_import_panel");

            pimcore.layout.refresh();


        }

        return this.panel;
    },
            
    init: function(){

       if (!this.tree) {
            this.tree = new Ext.tree.TreePanel({
                id: "pimcore_panel_object_tree",
                region: "west",
                useArrows:true,
                autoScroll:true,
                animate:true,
                enableDD:true,
                ddGroup: "objects",
                containerScroll: true,
                border: true,
                split:true,
                width: 250,
                minSize: 100,
                maxSize: 350,
                
                root: {
                    nodeType: 'async',
                    draggable:true,
                    id: '1',
                    text: t("objects"),
                    allowChildren: true
                },
                
                loader: new Ext.ux.tree.PagingTreeLoader({ 
                    
                    dataUrl:this.treeDataUrl,
                    pageSize:30,
                    enableTextPaging:false,
                    pagingModel:'remote',
                    requestMethod: "GET",
                    baseAttrs: {
                        listeners: this.getTreeNodeListeners(),
                        reference: this,
                        allowDrop: true,
                        allowChildren: true,
                        isTarget: true
                    },

                    baseParams: {
                        "node": 1,
                        "limit": 30,
                        "start": 0
                        
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
            "click" : this.onTreeNodeClick.bind(this),
            "contextmenu": this.onTreeNodeContextmenu,
            
        };

        return treeNodeListeners;
    },
    
    onTreeNodeClick: function(){

    },

   onTreeNodeContextmenu: function(){
        
        this.select();
        var menu = new Ext.menu.Menu();

        var object_types = pimcore.globalmanager.get("object_types_store");
                 
        var objectMenu = {
            objects: [],
            importer: [],
            ref: this
        };
        
        var tmpMenuEntry;
        var tmpMenuEntryImport;

        object_types.each(function(record) {

            if (this.ref.attributes.reference.config.allowedClasses == "all" || in_array(record.get("id"),
                                                                this.ref.attributes.reference.config.allowedClasses)) {
                // for create new object
                tmpMenuEntry = {
                    text: record.get("translatedText"),
                    iconCls: "pimcore_icon_object_add" //,
             //     handler: this.ref.attributes.reference.addObject.bind(this.ref, record.get("id"),
             //                                                   record.get("text"))
                };                             
                
                if (record.get("icon")) {
                    tmpMenuEntry.icon = record.get("icon");
                    tmpMenuEntry.iconCls = "";
                }
                this.objects.push(tmpMenuEntry);

                tmpMenuEntryImport = {
                    text: record.get("translatedText"),
                    iconCls: "pimcore_icon_object_import",

                    handler: function(){
                     
                            this.importer = new pimcore.plugin.workspace.importer(this.ref, record.get("id"),
                                                                record.get("text"));
                            this.importer.showUpload();

                         }.bind(this)
          
                            
                };

                if (record.get("icon")) {
                    tmpMenuEntryImport.icon = record.get("icon");
                    tmpMenuEntryImport.iconCls = "";
                }
                this.importer.push(tmpMenuEntryImport);
            }

        }, objectMenu);
                 
                menu.add(new Ext.menu.Item({
                    text: t('import_csv'),
                    iconCls: "pimcore_icon_object_csv_import",
                    menu: objectMenu.importer

                }));
                menu.add(new Ext.menu.Item({
                    text: t('export'),
                    iconCls: "pimcore_icon_export",
                    handler: function(){
                        this.attributes.reference.startExport(this);
                    }.bind(this)
                }));
                
                if (this.reload) {
                    menu.add(new Ext.menu.Item({
                        text: t('refresh'),
                        iconCls: "pimcore_icon_reload",
                        handler: this.reload.bind(this)
                        }));
                 };

                if(typeof menu.items != "undefined" && typeof menu.items.items != "undefined"
                                                                            && menu.items.items.length > 0) {
                    menu.show(this.ui.getAnchor());
                }
   },


   startExport: function (object) {
        if(object.attributes.type == 'object'){
             var path = "/plugin/ExtendedImport/admin/export/className/" + object.attributes.className + "/objectId/" + object.id ;
             pimcore.helpers.download(path);
            
        } else if(object.attributes.type == 'folder'){
            var window;
             if(!window){
            window = new Ext.Window({
                title: 'Select class',
                layout:'fit',
                width:200,
                height:100,
                closeAction: 'hide',
                closable:true,
                plain: true,
                items: this.classCombobox,
                buttons: [{
                    text: 'Select',
                    handler: function(){
                        var className = Ext.getCmp("combo_classes").getValue();
                        var path = "/plugin/ExtendedImport/admin/export/className/" + className + "/folderId/" + object.id ;
                        pimcore.helpers.download(path);
                        window.hide();
                    }
                }, {
                    text: 'Cancel',
                    handler: function(){
                        window.hide();
                    }
                }]
            });
        }
            window.show(this);
        }    
    }
     
            
});


