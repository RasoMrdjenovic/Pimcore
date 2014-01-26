pimcore.registerNS("pimcore.plugin.myPlugin.tab");
pimcore.plugin.myPlugin.tab = Class.create({

    initialize: function (parentPanel, id) {
        this.parentPanel = parentPanel;
        this.id = id;
        this.data;

        Ext.Ajax.request({
            url: "/plugin/MyPlugin/admin/getrole",
            success: this.loadComplete.bind(this),
            params: {
                id: this.id
            }
        });
    },

    loadComplete: function (transport) {
        var response = Ext.decode(transport.responseText);
        if(response && response.success) {
            this.data = response;
            this.initPanel();
        }
    },

    initPanel: function () {

        this.panel = new Ext.TabPanel({
            title: this.data.role.name,
            closable: true,
            activeTab: 0,
            iconCls: "pimcore_icon_roles",
            buttons: [{
                text: t("save"),
                handler: this.save.bind(this),
                iconCls: "pimcore_icon_accept"
            }],
           items:[
             this.getPanel()
           ]
           
        });

        this.panel.on("beforedestroy", function () {
            delete this.parentPanel.panels["role_" + this.id];
        }.bind(this));
//
//        this.settings = new pimcore.plugin.myPlugin.settings(this);
        this.workspaces = new pimcore.plugin.myPlugin.workspaces(this);
////
//        this.panel.add(this.settings.getPanel());
        this.panel.add(this.workspaces.getPanel());
//
        this.parentPanel.getEditPanel().add(this.panel);
        this.parentPanel.getEditPanel().activate(this.panel);
    },

    getPanel: function () {

        var availPermsItems = [];
        // add available permissions
//        for (var i = 0; i < this.data.availablePermissions.length; i++) {
//            availPermsItems.push({
//                xtype: "checkbox",
//                fieldLabel: t(this.data.availablePermissions[i].key),
//                name: "permission_" + this.data.availablePermissions[i].key,
//                checked: false,
//                labelStyle: "width: 200px;"
//            });
//        }
        for (var i = 0; i < this.data.documents.length; i++) {
            var checked = false;
            if(this.data.role.settings){
                if(in_array(this.data.documents[i].roleSettings,this.data.role.settings.split(","))){
                    checked = true;
                }
                else{
                    checked = false;
                }

            }
            availPermsItems.push({
                xtype: "checkbox",
                fieldLabel: this.data.documents[i].roleSettings,
                name: this.data.documents[i].roleSettings,
                checked: checked,
                labelStyle: "width: 200px;"
            });
        }

        this.permissionsSet = new Ext.form.FieldSet({
            title: t("permissions"),
            items: [availPermsItems]
        });

        this.settingspanel = new Ext.form.FormPanel({
            title: t("settings"),
            items: [this.permissionsSet],
            bodyStyle: "padding:10px;",
            autoScroll: true,
            buttons: [{
                text: "Add setting",
                handler: this.addSetting.bind(this),
                iconCls: "pimcore_icon_cog_add"
          
            }]
        });

        return this.settingspanel;
    },
    addSetting:function(){
        Ext.MessageBox.prompt('add setting', t('please_enter_the_name'), function (button, value, object) {
            if(button=='ok' && value != ''){
                Ext.Ajax.request({
                    url: "/plugin/MyPlugin/admin/addsetting",
                    params: {
                        parentId: this.id,
                        name: value,
                        active: 1
                    },
                    success:  function(response){
                        Ext.Msg.alert('Status',response.responseText );
                        this.settingspanel.reload();
                   }
//                            this.attributes.reference.addComplete.bind(this.attributes.reference, this.id)
                });
            }
        }.bind(this));
    },
   getValues: function () {
        return this.settingspanel.getForm().getFieldValues();
    },

    activate: function () {
        this.parentPanel.getEditPanel().activate(this.panel);
    },

    save: function () {

        var data = {
            id: this.id
        };

        try {
            data.data = Ext.encode(this.getValues());
        } catch (e) {
            console.log(e);
        }

        try {
            data.workspaces = Ext.encode(this.workspaces.getValues());
        } catch (e2) {
            console.log(e2);
        }

        Ext.Ajax.request({
            url: "/plugin/MyPlugin/admin/update",
            method: "post",
            params: data,
            success: function (transport) {
                 Ext.Msg.alert('Status',transport.responseText );
//                try{
//                    var res = Ext.decode(transport.responseText);
//                    if (res.success) {
//                        pimcore.helpers.showNotification(t("success"), t("role_save_success"), "success");
//                    } else {
//                        pimcore.helpers.showNotification(t("error"), t("role_save_error"), "error",t(res.message));
//                    }
//                } catch(e){
//                    pimcore.helpers.showNotification(t("error"), t("role_save_error"), "error");
//                }
            }.bind(this)
        });
    }

});
