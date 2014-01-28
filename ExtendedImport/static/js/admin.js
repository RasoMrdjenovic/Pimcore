pimcore.registerNS("pimcore.plugin.extendedImport");

pimcore.plugin.extendedImport = Class.create(pimcore.admin,{
    getClassName: function() {
        return "pimcore.plugin.extendedImport";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
        this.panel;
        this.tree;
         

    },
 
    pimcoreReady: function (params,broker){
        // add a sub-menu item under "Extras" in the main menu
        var toolbar = Ext.getCmp("pimcore_panel_toolbar");
        
        var action = new Ext.Action({
            id: "extended_import_menu_item",
            text: "Extended Import/Export",
            iconCls:"pimcore_icon_object_csv_import",
            handler: this.showTab,

        });
        

        toolbar.items.items[1].menu.add(action);
   
        
    },
            
    showTab: function(){
            
        try {
            pimcore.globalmanager.get("import_panel").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("import_panel", new pimcore.plugin.workspace.panel());
        }

           
        }
       
           


});

   

var extendedImport = new pimcore.plugin.extendedImport();
