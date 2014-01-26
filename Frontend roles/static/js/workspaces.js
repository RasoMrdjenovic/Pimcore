pimcore.registerNS("pimcore.plugin.myPlugin.workspaces");
pimcore.plugin.myPlugin.workspaces = Class.create({

    initialize: function (userPanel) {
        this.userPanel = userPanel;
        this.data = this.userPanel.data;
    },

    getPanel: function () {


//        this.asset = new pimcore.plugin.myPlugin.workspace.asset(this);
        this.document = new pimcore.plugin.myPlugin.workspace.document(this);
        this.object = new pimcore.plugin.myPlugin.workspace.object(this);

        this.panel = new Ext.Panel({
            title: t("workspaces"),
            bodyStyle: "padding:10px;",
            autoScroll: true,
            items: [this.document.getPanel(), this.object.getPanel()
//            , this.asset.getPanel()
            ]
        });

        return this.panel;
    },

    disable: function () {
        this.panel.disable();
    },

    enable: function () {
        this.panel.enable();
    },

    getValues: function () {
        return {
//            asset: this.asset.getValues(),
            object: this.object.getValues(),
            document: this.document.getValues()
        };
    }

});