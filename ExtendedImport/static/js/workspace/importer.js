/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

pimcore.registerNS("pimcore.plugin.workspace.importer");
pimcore.plugin.workspace.importer = Class.create({

    initialize: function (parentNode, classId, className) {
        
       // this.parent = parent;
        this.parentId = parentNode.id;
        this.parentNode = parentNode;
        this.classId = classId;
        this.className = className;
        this.importId = uniqid();
        
        this.mappingStoreCustom = new Ext.data.JsonStore({
             url:                "/plugin/ExtendedImport/import-mapping/mapping-view",
             restful:            false,
             root:               "mappingView",              
             baseParams: {
                classId: this.classId,
                parentId: this.parentId
             },
             fields: [
                         "mapId",
                         "title"
                    ]
      });

    },
            
    showUpload: function () {

//    console.log("showUpload");
        pimcore.helpers.uploadDialog('/plugin/ExtendedImport/import/import-upload/?pimcore_admin_sid='
                        + pimcore.settings.sessionId + "&id=" + this.importId, "Filedata", function(res) {
            this.getFileInfo();
        }.bind(this), function () {
            Ext.MessageBox.alert(t("error"), t("error"));
        });
    },

    getFileInfo: function () {
        Ext.Ajax.request({
            url: "/plugin/ExtendedImport/import/import-get-file-info",
            params: {
                id: this.importId,
                method: "post",
                className: this.className,
                classId: this.classId
            },
            success: this.getFileInfoComplete.bind(this)
        });
    },
            
    getFileInfoComplete: function (response) {

        var data = Ext.decode(response.responseText);

        if (data.success) {
            this.showDataWindow(data);
        }
        else {
            Ext.MessageBox.alert(t("error"), t("unsupported_filetype"));
        }
    },
              
    showDataWindow: function (data) {

        this.importJobTotal = data.rows;
        
        
        var dataStore = new Ext.data.JsonStore({
            autoDestroy: true,
            data: data,
            root: 'dataPreview',
            fields: data.dataFields
        });

        var dataGridCols = [];
        for (var i = 0; i < data.dataFields.length; i++) {
            dataGridCols.push({header: t("field") + " " + i, sortable: false, dataIndex: data.dataFields[i]});
        }


        var dataGrid = new Ext.grid.GridPanel({
            store: dataStore,
            columns: dataGridCols,
            viewConfig: {
                forceFit: false
            },
            height: 390,
            width: 890,
            autoScroll: true
        });

        var headRecord = dataStore.getAt(0);

        var formPanel = new Ext.form.FormPanel({
            items: [
                {
                    xtype: "checkbox",
                    name: "hasHeadRow",
                    fieldLabel: t("importFileHasHeadRow"),
                    listeners: {
                        check: function(headRecord, dataGrid, checkbox, checked) {
                            var i;
                            if (checked) {
                                dataGrid.store.remove(headRecord);
                                this.importJobTotal = data.rows - 1;
                                this.settingsForm.getForm().findField('skipHeadRow').setValue(true);
                                for (i = 0; i < headRecord.fields.items.length; i++) {
                                    var value = headRecord.get("field_" + i);
                                    dataGrid.getColumnModel().setColumnHeader(i, value);
                                }
                            } else {
                                dataGrid.store.insert(0, headRecord);
                                this.importJobTotal = data.rows;
                                this.settingsForm.getForm().findField('skipHeadRow').setValue(false);
                                for (i = 0; i < headRecord.fields.items.length; i++) {
                                    dataGrid.getColumnModel().setColumnHeader(i, "field_" + i);
                                }
                            }
                            dataGrid.getView().refresh();
                        }.bind(this, headRecord, dataGrid)
                    }
                }
            ],
            labelWidth: 200,
            autoHeight:true,
            bodyStyle: "padding: 10px;"
        });

        var mappingStore = new Ext.data.JsonStore({
            autoDestroy: true,
            data: data,
            root: 'mappingStore',
            fields: ["source", "firstRow", "target"]
        });

        var targetFields = data.targetFields;
        targetFields.push(["",t("ignore")]);


        var sourceFields = [];
        for (i = 0; i < data.cols; i++) {
            sourceFields.push([i,t("field") + " " + i]);
        }
        

        this.mappingGrid = new Ext.grid.EditorGridPanel({
            store: mappingStore,
            region: 'south',
        //    mode: "local",
            columns: [
                {
                    header: t("source"),
                    sortable: false,
                    dataIndex: "source",
                    renderer: function(value, p, r) {                               
                        return r.data.source + " (" +  r.data.firstRow  + ")" ;
                    }.bind(this)
                    /*,editor: new Ext.form.ComboBox({
                        store: sourceFields,
                        mode: "local",
                        triggerAction: "all"
                     })*/
                },
                {
                    header: t("target"),
                    sortable: false, 
                    mode: "local",
                    dataIndex: "target",
                    editor: new Ext.form.ComboBox({
                        store: targetFields,
                        triggerAction: "all",
                        forceSelection: true, 
                        selectOnFocus: true
                    })
                }
            ],
            viewConfig: {
                forceFit: true          
            }
        });

        var filenameMappingStore = sourceFields;
        filenameMappingStore.push(["default", "default"]);
        filenameMappingStore.push(["id", "ID"]);


        this.settingsForm = new Ext.form.FormPanel({
            items: [
                {
                    xtype: "combo",
                    name: "filename",
                    store: filenameMappingStore,
                    mode: "local",
                    triggerAction: "all",
                    fieldLabel: t("filename"),
                    value: "default"
                },
                {
                    xtype:'displayfield',
                    value:t("object_import_filename_description"),
                    cls: 'pimcore_extra_label_bottom'
                },
                {
                    xtype: "checkbox",
                    name: "overwrite",
                    fieldLabel: t("overwrite_object_with_same_key")
                },
                {
                    xtype:'displayfield',
                    value:t("overwrite_object_with_same_key_description"),
                    cls: 'pimcore_extra_label_bottom'
                },
                {
                    xtype: "hidden",
                    id: 'skipHeadRow',
                    name: "skipHeadRow",
                    value: false
                }
            ],
            bodyStyle: "padding: 10px;"
        });

        this.customMappingCombo = new Ext.form.ComboBox({
        //    name: "comboCustom",                        
        //    mode: 'local',
            id: "customMappingCombo",
            emptyText:'Select a filter...',
            selectOnFocus:true,
            listWidth: 100,
            store: this.mappingStoreCustom,
            valueField: 'mapId',
            displayField: 'title',  
            triggerAction: 'all',
            listeners: {
                 "select": this.changeMappingFilter.bind(this) 
           }
        });

        this.dataWin = new Ext.Window({
            modal: true,
            width: 900,
            height: 500,
            layout: "fit",
            items: [
                {
                    xtype: "tabpanel",
                    activeTab: 0,
                    items: [
                        {
                            xtype: "panel",
                            title: t("preview"),
                            layout: "fit",
                            items: [formPanel,dataGrid]
                        },
                        {
                            xtype: "panel",
                            id: "mappingGridPanel",
                            title: t("data_mapping"),
                            layout: "fit",
                            tbar: [
                                {    
                                    text: 'Mapping',
                                    arrowAlign: 'right',
                                    iconCls: "extendedimport_icon_rainbow",
                                  //  scale: 'medium',
                                    style: {
                                        marginBottom: '5px',
                                        marginTop: '5px'
                                    },
                                    menu: [ 
                                        { 
                                            text: 'Auto Mapping',
                                            iconCls: "extendedimport_icon_refresh",
                                            handler: this.autoMapping.bind(this)
                                        },
                                        {   
                                            text: 'Import Mapping View',
                                            iconCls: "extendedimport_icon_import",
                                            handler: this.importMapping.bind(this)
                                        },
                                        {   
                                            text: 'Export Mapping View',
                                            iconCls: "extendedimport_icon_export",
                                            handler: this.exportMapping.bind(this)
                                        }
                                    ]
                                 },'->',
                                  this.customMappingCombo,
                                  {
                                    text: 'Save',
                                    arrowAlign: 'right',
                                    iconCls: 'pimcore_icon_add',
                                    handler: this.saveMapping.bind(this)
                                 },
                                 {
                                    text: 'Delete',
                                    arrowAlign: 'right',
                                    iconCls: 'pimcore_icon_delete',
                                    handler: this.deleteMapping.bind(this)
                                 }
                            ],
                            items: [this.mappingGrid]
                        },
                        {
                            xtype: "panel",
                            title: t("settings"),
                            layout: "fit",
                            items: [this.settingsForm],
                            buttons: [
                                {
                                    text: t("import"),
                                    handler: this.importStart.bind(this)
                                }
                            ]
                        }
                    ]
                }
            ],
            title: t("import")
        });

        this.dataWin.show();
    },
     
    changeMappingFilter: function (combo, record, index) { 
        
        var mappingData = [];
        var tmData = [];
        
        for (var i = 0; i < this.mappingGrid.store.data.items.length; i++) {

             tmData = [];
            var keys = Object.keys(this.mappingGrid.store.data.items[i].data);
            for (var u = 0; u < keys.length; u++) {
                tmData.push(this.mappingGrid.store.data.items[i].data[keys[u]]);
            }
            mappingData.push(tmData);
        }
        
        combo.setValue(record.data.title); 

        if (!empty(record.data.mapId)) {
            Ext.Ajax.request({
                url: "/plugin/ExtendedImport/import-mapping/change-mapping-filter",
                params: {
                    method: "post",
                    id: this.importId,
                    classId: this.classId,
                    mapId: Ext.encode(record.data.mapId),
                    title: Ext.encode(record.data.title),
                    keys: Ext.encode(keys),
                    mappingData: Ext.encode(mappingData) 

                },
                success: function (response) {

                   var rdata = Ext.decode(response.responseText);
                   this.mappingValueInGrid(rdata);

                }.bind(this)
            });
        }

    },

    autoMapping: function () {
        
        Ext.Ajax.request({
            url: "/plugin/ExtendedImport/import-mapping/auto-mapping-data-field",
            params: {
                id: this.importId,
                method: "post",
                className: this.className,
                classId: this.classId
            },
            success: function (response) {
                
               var rdata = Ext.decode(response.responseText);
               this.mappingValueInGrid(rdata);
 
            }.bind(this)
        });
    },

    mappingValueInGrid: function (data) {

        var mappingStore = new Ext.data.JsonStore({
            autoDestroy: true,
            data: data,
            root: 'mappingStore',
            fields: ["source", "firstRow", "target"]
        });  
  
        this.mappingGrid.removeAll();
        this.mappingGrid.store = mappingStore;
        this.mappingGrid.getView().refresh();

    },
        
    saveMapping: function () {
        
        var mappingData = [];
        var tmData = [];
        
        for (var i = 0; i < this.mappingGrid.store.data.items.length; i++) {

             tmData = [];
            var keys = Object.keys(this.mappingGrid.store.data.items[i].data);
            for (var u = 0; u < keys.length; u++) {
                tmData.push(this.mappingGrid.store.data.items[i].data[keys[u]]);
            }
            mappingData.push(tmData);
        }
        
     //   var store = this.customMappingCombo.store;
        var cmc = this.customMappingCombo;
        var parentId = this.parentId;
        var classId = this.classId;
        
        Ext.MessageBox.prompt('Add Mapping Filter', 'Please enter name:', function(btn, text){
            
            if (btn == 'ok') {
            
                if (text.search(/[^a-zA-Z0-9\s]+/) === -1 && !(/^\s*$/.test(text))) {
                    Ext.Ajax.request({
                        url: "/plugin/ExtendedImport/import-mapping/save-mapping-data-field",
                        params: {
                            method: "post",
                            parentId: Ext.encode(parentId),
                            classId: Ext.encode(classId),
                            name: Ext.encode(text),
                            keys: Ext.encode(keys),
                            data: Ext.encode(mappingData)
                        },
                        success: function (response) {

                            var rdata = Ext.decode(response.responseText);
                            cmc.store.reload();
                            cmc.setValue(text);
                            
                            Ext.Msg.alert(t("Info"), rdata);

                        }.bind(this)
                    });
                } else {
                    Ext.Msg.alert(t("Info"), t("You can use only a-z A-Z and 0-9"));
                }
            }
        });
    },
  
    deleteMapping: function () {
        
        var cmc = this.customMappingCombo;

        var comboValue = this.customMappingCombo.getValue();
        var parentId = this.parentId;
        var classId = this.classId;
        
        Ext.MessageBox.confirm('Delete Selected Filter', 'Do You sure to delete this records?', function(btn) {

            if (btn == 'yes') {

                Ext.Ajax.request({
                    url: "/plugin/ExtendedImport/import-mapping/delete-mapping-data-field",
                    params: {
                        method: "post",
                        parentId: Ext.encode(parentId),
                        classId: Ext.encode(classId),
                        name: Ext.encode(comboValue)
                    },
                    success: function (response) {

                           var rdata = Ext.decode(response.responseText);
                           cmc.store.reload();
                           cmc.setValue("");
                           Ext.Msg.alert(t("Info"), rdata);

                    }.bind(this)
                });                   
            }
        });
    },

    exportMapping: function () {
        
        var mappingData = [];
        var tmData = [];
        
        for (var i = 0; i < this.mappingGrid.store.data.items.length; i++) {

             tmData = [];
            var keys = Object.keys(this.mappingGrid.store.data.items[i].data);
            for (var u = 0; u < keys.length; u++) {
                tmData.push(this.mappingGrid.store.data.items[i].data[keys[u]]);
            }
            mappingData.push(tmData);
        }

        var path = "/plugin/ExtendedImport/import-mapping/export-mapping-data-field/keys/" + Ext.encode(keys) + "/data/" + Ext.encode(mappingData);

        pimcore.helpers.download(path);
        
    },

    importMapping: function () {

        this.showMappingUpload();
        
    },         
     
    showMappingUpload: function () {

        pimcore.helpers.uploadDialog('/plugin/ExtendedImport/import-mapping/import-mapping-upload/?pimcore_admin_sid='
                        + pimcore.settings.sessionId + "&id=" + this.importId, "Filedata", function(res) {
            this.importMappingInfo();
        }.bind(this), function () {
            Ext.MessageBox.alert(t("error"), t("error"));
        });
    },

    importMappingInfo: function () {
        
        Ext.Ajax.request({
            url: "/plugin/ExtendedImport/import-mapping/import-mapping-info",
            params: {
                id: this.importId,
                method: "post",
                className: this.className,
                classId: this.classId
            },
            success: function (response) {
                
               var rdata = Ext.decode(response.responseText);
               this.mappingValueInGrid(rdata);
 
            }.bind(this)
        });      
    },
   
    importStart: function () {

        // get mapping
        var data = this.mappingGrid.getStore().queryBy(function(record, id) {          
            return true;
        });

        var mappingData = [];
        var tmData = [];
        for (var i = 0; i < data.items.length; i++) {
            tmData = [];

            var keys = Object.keys(data.items[i].data);
            for (var u = 0; u < keys.length; u++) {
                tmData.push(data.items[i].data[keys[u]]);
            }
            mappingData.push(tmData);
        }

        this.jobRequest = {
            mapping: Ext.encode(mappingData),
            id: this.importId,
            className: this.className,
            classId: this.classId,
            job: 1,
            parentId: this.parentId
        };

        this.jobRequest = mergeObject(this.jobRequest, this.settingsForm.getForm().getFieldValues());

        this.dataWin.close();


        this.importProgressBar = new Ext.ProgressBar({
            text: t('Initializing'),
            style: "margin: 10px;",
            width: 500
        });


        this.importProgressWin = new Ext.Window({
            items: [this.importProgressBar],
            modal: true,
            bodyStyle: "background: #fff;",
            closable: false
        });
        this.importProgressWin.show();


        this.importErrors = [];
        this.importJobCurrent = 1;

        window.setTimeout(function() {
            this.importProcess();
        }.bind(this), 1000);

    },
            
    importProcess: function () {

        if (this.importJobCurrent > this.importJobTotal) {
            this.importProgressWin.close();

            // error handling
            if (this.importErrors.length > 0) {

                var jobs = [];
                for (var i = 0; i < this.importErrors.length; i++) {
                    jobs.push(this.importErrors[i].job);
                }
                Ext.Msg.alert(t("error"), t("error_jobs") + ": " + jobs.join(","));
            }

            this.parentNode.reload();

            return;
        }

        var status = (this.importJobCurrent / this.importJobTotal);
        var percent = Math.ceil(status * 100);
        this.importProgressBar.updateProgress(status, percent + "%");

        this.jobRequest.job = this.importJobCurrent;
        Ext.Ajax.request({
            url: "/plugin/ExtendedImport/import/import-process",
            params: this.jobRequest,
            method: "post",
            success: function (response) {

                var rdata = Ext.decode(response.responseText);
                if (rdata) {
                    if (!rdata.success) {
                        this.importErrors.push({
                            job: rdata.message
                        });
                    }
                }
                else {
                    this.importErrors.push({
                        job: response.request.parameters.job
                    });
                }

                window.setTimeout(function() {
                    this.importJobCurrent++;
                    this.importProcess();
                }.bind(this), 400);
            }.bind(this)
        });
    }

});
