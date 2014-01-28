pimcore.registerNS("pimcore.plugin.BMEcat");

pimcore.plugin.BMEcat = Class.create(pimcore.plugin.admin,{
    
   // treeDataUrl: "/plugin/BMEcat/admin/tree-get-childs-by-id/",
    
    getClassName: function() {
        return "pimcore.plugin.BMEcat";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
        this.roots = [];
        this.i = 0;
        this.importId = uniqid();
        /* Get folders from objects  */
        Ext.Ajax.request({
                        url: "/plugin/BMEcat/admin/get-folders",
                        method: "get",
                        success: function(response){
                             
                             this.folders = Ext.decode(response.responseText);
                             for(var i = 0; i < this.folders.length; i++){
                                BMEcat.roots.push([this.folders[i].o_key])
                             }
 
                        }
        });
       

    },
 
    pimcoreReady: function (params,broker){
        // add a sub-menu item under "Extras" in the main menu
        var toolbar = Ext.getCmp("pimcore_panel_toolbar");
        
        var action = new Ext.Action({
            id: "bmecat_menu_item",
            text: "BMEcat",
            iconCls:"bmecat_icon",
            handler: this.showWindow
        });

        toolbar.items.items[1].menu.add(action);
     
        
    },
    
            
    showWindow:function () {


         /* Form with mandatory fields for BMEcat->HEADER  */
         this.generalForm = new Ext.form.FormPanel({
           bodyStyle: "padding: 10px 20px;",
           region:"west",
           id:'generalForm',
           border: true,
           autoScroll: true,
           width:'50%',
           defaults:{
               width: 240,
               xtype: "textfield",
               allowBlank: false,
           } , 
           items:[
               {
                xtype:'fieldset',
                title:'Catalog',
                id:'catalogFieldset',
                width:400,
                collapsible:true,
                defaults:{
                   width: 240,
                   xtype: "textfield",
                   allowBlank: false,
                } ,
                items:[
               
               {
                xtype:'combo',   
                name:"bme_language",   
                id: "bme_language",
                fieldLabel: "Language",
                triggerAction: 'all',                 
                value:"DEU",
                lazyRender:true,
                mode: 'local',
                store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['AAR'],['ABK'], ['ACE'],['ACH'],['ADA'],['AFA'],['AFH'],['DEU']]
                }),
                valueField: 'displayText',
                displayField: 'displayText',
               },
               {
                name:"bme_catId",   
                id: "bme_catId",
                fieldLabel: "Catalog ID"
               },
               {
                name:"bme_catVersion",   
                id: "bme_catVersion",
                fieldLabel: "Catalog version"
               },
               {
                name:"bme_catName",   
                id: "bme_catName",
                fieldLabel: "Catalog Name"
               },
               {
                xtype:'combo',   
                name:"bme_currency",   
                id: "bme_currency",
                fieldLabel: "Currency",
                triggerAction: 'all',                 
                value:"EUR",
                lazyRender:true,
                mode: 'local',
                store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['ADP'],['AED'], ['EUR'],['USD']]
                }),
                valueField: 'displayText',
                displayField: 'displayText',
               }
               ]},
               {
                xtype:'fieldset',
                title:'Buyer',
                id:'buyerFieldset',
                width:400,
                collapsible:true,
                defaults:{
                   width: 240,
                   xtype: "textfield",
                   allowBlank: false,
                } ,
                items:[
                   {
                    name:"bme_buyerId",   
                    id: "bme_buyerId",
                    fieldLabel: "Buyer ID"
                   },
                   {
                    name:"bme_buyerName",   
                    id: "bme_buyerName",
                    fieldLabel: "Buyer Name"
                   } 
                    
                ]  
               },
               {
                xtype:'fieldset',
                title:'Supplier',
                id:'supplierFieldset',
                width:400,
                collapsible:true,
                defaults:{
                   width: 240,
                   xtype: "textfield",
                   allowBlank: false,
                } ,
                items:[
                    {
                    name:"bme_supplierId",   
                    id: "bme_supplierId",
                    fieldLabel: "Supplier ID"
                   },
                   {
                    name:"bme_supplierName",   
                    id: "bme_supplierName",
                    fieldLabel: "Supplier Name"
                   }
                ]
               }
               ,
               {
                xtype:'combo',   
                name:"bme_suppIdRef",   
                id: "bme_suppIdRef",
                triggerAction: 'all',                 
                value:"buyer_specific",
                lazyRender:true,
                mode: 'local',
                store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['buyer_specific'],['customer_specific'], ['duns'],['iln'],['gln'],['party_specific'],['supplier_specific']]
                }),
                valueField: 'displayText',
                displayField: 'displayText',
                fieldLabel: "Supplier ID Ref"
               },
               {
                xtype:'combo', 
                name:"bme_docCreator",   
                id: "bme_docCreator",
                triggerAction: 'all',                 
                value:"buyer_specific",
                lazyRender:true,
                mode: 'local',
                store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['buyer_specific'],['customer_specific'], ['duns'],['iln'],['gln'],['party_specific'],['supplier_specific']]
                }),
                valueField: 'displayText',
                displayField: 'displayText',
                fieldLabel: "Document creator ID Ref"
               }
           ]  
         });

        /*  Optional fields for BMEcat->HEADER  */  
        this.additionalFields = new Ext.Panel({
            id:         "additionalFieldsPanel",
            border:     true,
            autoScroll:true,
            height: 'auto',
            width:'50%',
            region:'center',
            closable:   false,
            items:      [
                {
                xtype: 'fieldset',
                title: 'Additional fields',
                style:'width:90%;margin:10px auto',
                defaults:{
                    style:'margin-bottom:10px'
                },
                items:[
                    {
                        xtype:'multiselect',
                        fieldLabel:'Catalog',
                        id:'additionalCatalogFields',
                        width:220,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText'
                            ],
                            data: [['datetime'],['valid-start-date'],['valid-end-date'], ['teritories'],['mime-root'],['product-type'],['price-flag'],['price-factor']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'displayText',
                        displayField: 'displayText'
                        
                    },
                    {
                        xtype:'multiselect',
                        fieldLabel:'Buyer',
                        id:'additionalBuyerFields',
                        width:220,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText'
                            ],
                            data: [['department'],['street'],['zip'], ['city'],['country'],['country-coded'],['phone'],['fax'],['email'],['url']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'displayText',
                        displayField: 'displayText'
                        
                    },
                    {
                        xtype:'multiselect',
                        fieldLabel:'Supplier',
                        id:'additionalSupplierFields',
                        width:220,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText'
                            ],
                            data: [['department'],['street'],['zip'], ['city'],['country'],['country-coded'],['phone'],['fax'],['email'],['url']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'displayText',
                        displayField: 'displayText'
                        
                    },
                    {
                        xtype:'button',
                        text: 'Add fields',
                        style:'float:right;margin-right:112px;margin-top:15px',
                        iconCls: "pimcore_icon_add",
                        handler: BMEcat.addField
                    }
                ]
                
                }
                
                
            ]
            
            
        });


        var classStore = pimcore.globalmanager.get("object_types_store"); // get all existing classes
        var categoryClassStore = pimcore.globalmanager.get("object_types_store");

        /* make array with existing class name + folder, using to declare categories for objects  */
        var classes = [];
        for (var i = 0; i < categoryClassStore.data.items.length; i++){
            classes.push([categoryClassStore.data.items[i].data.translatedText]);
        }
        classes.push(['folder']);
   
        /*  T_NEW_CATALOG (mostly) mandatory fields   */
        var T_NEW_CATALOG =[
            {
                xtype: 'fieldset',
                title: 'Catalog groups system',
                style:'margin-left:5px;margin-top:10px',
                width:370,
                collapsible: true,
                collapsed:true,
                defaults:{
                   width: 200,
                   xtype: "textfield",
                   labelStyle:"margin-left:5px"
                },
                items: [
                    {
                        xtype:"combo",
                        fieldLabel:'Group catalog root',
                        lazyRender:true,
                        mode: 'local',
                        store: new Ext.data.ArrayStore({
                          id: 0,
                          fields: [
                            'displayText'
                          ],
                        data: BMEcat.roots
                        }),
                        value:BMEcat.roots[0],
                        triggerAction: 'all',
                        valueField: 'displayText',
                        displayField: 'displayText',
                        id:'catalogRoot'
                    },
                    {
                        xtype:"combo",
                        fieldLabel:'Select categorie class',
                        id:'categoryClassName',
                        lazyRender:true,
                        mode: 'local',
                        store: new Ext.data.ArrayStore({
                          id: 0,
                          fields: [
                            'displayText'
                          ],
                        data: classes
                        }),
                        value:classes[0],
                        triggerAction: 'all',
                        valueField: 'displayText',
                        displayField: 'displayText'
                        
                    },
                    
                    {
                        fieldLabel:'Group system name',
                        id:'groupSystemName'                       
                    }
   
                ]
            },
            
            {
                xtype: 'fieldset',
                title: 'Products',
                style:'margin-left:5px',
                width:370,
                collapsible: true,
                defaults:{
               width: 200,
               xtype: "textfield",
               labelStyle:"margin-left:5px",
               listeners:{
                     "render": function (el) {
                                new Ext.dd.DropZone(el.getEl(), {
                                    reference: this,
                                    ddGroup: "element",
                                    getTargetFromEvent: function(e) {
                                        return this.getEl();
                                    }.bind(el),

                                    onNodeOver : function(target, dd, e, data) {
                              
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                          
                                            return Ext.dd.DropZone.prototype.dropAllowed;
                                        }
                                        return Ext.dd.DropZone.prototype.dropNotAllowed;
                                    },

                                    onNodeDrop : function (target, dd, e, data) {
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                        
                                                this.setValue(data.node.attributes.text);

                                   
                                            return true;
                                        }
                                        return false;
                                    }.bind(el)
                                });
                            }
               }
   
           },
                
                items: [
              {
                  fieldLabel:'Product ID',
                  id:'pid',
                  style:'margin-top:5px',
                  allowBlank:false
 
              }, 
              {
               xtype:'fieldset',
               title:'product details',
               id:'product_details',
               collapsible:true,
               width:330,
               defaults:{
                    width: 200,
                    xtype: "textfield",
                    labelStyle:"margin-left:5px",
                    listeners:{
                          "render": function (el) {
                                     new Ext.dd.DropZone(el.getEl(), {
                                         reference: this,
                                         ddGroup: "element",
                                         getTargetFromEvent: function(e) {
                                             return this.getEl();
                                         }.bind(el),

                                         onNodeOver : function(target, dd, e, data) {

                                             if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {

                                                 return Ext.dd.DropZone.prototype.dropAllowed;
                                             }
                                             return Ext.dd.DropZone.prototype.dropNotAllowed;
                                         },

                                         onNodeDrop : function (target, dd, e, data) {
                                             if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {

                                                     this.setValue(data.node.attributes.text);


                                                 return true;
                                             }
                                             return false;
                                         }.bind(el)
                                     });
                                 }
                    }
               },
               items:[
                  {
                      fieldLabel:'Short desc.',
                      id:'short_desc',
                      allowBlank:false
                  }
              ]},
               {
               xtype:'fieldset',
               title:'product order details',
               id:'product_order_details',
               collapsible:true,
               width:330,
               defaults:{
                width: 200,
               xtype: "textfield",
               labelStyle:"margin-left:5px",
               listeners:{
                     "render": function (el) {
                                new Ext.dd.DropZone(el.getEl(), {
                                    reference: this,
                                    ddGroup: "element",
                                    getTargetFromEvent: function(e) {
                                        return this.getEl();
                                    }.bind(el),

                                    onNodeOver : function(target, dd, e, data) {
                              
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                            
                                            return Ext.dd.DropZone.prototype.dropAllowed;
                                        }
                                        return Ext.dd.DropZone.prototype.dropNotAllowed;
                                    },

                                    onNodeDrop : function (target, dd, e, data) {
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                        
                                                this.setValue(data.node.attributes.text);

                                   
                                            return true;
                                        }
                                        return false;
                                    }.bind(el)
                                });
                            }
               }
               },
               items:[
                  
              {
                  fieldLabel:'Order unit',
                  id:'order_unit',
                  allowBlank:false
              },
              {
                  fieldLabel:'Content unit',
                  id:'content_unit',
                  allowBlank:false
              }],
              },
              {
               xtype:'fieldset',
               title:'product price details',
               id:'product_price_details',
               collapsible:true,
               width:330,
               defaults:{
                width: 200,
               xtype: "textfield",
               labelStyle:"margin-left:5px",
               listeners:{
                     "render": function (el) {
                                new Ext.dd.DropZone(el.getEl(), {
                                    reference: this,
                                    ddGroup: "element",
                                    getTargetFromEvent: function(e) {
                                        return this.getEl();
                                    }.bind(el),

                                    onNodeOver : function(target, dd, e, data) {
                              
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                            
                                            return Ext.dd.DropZone.prototype.dropAllowed;
                                        }
                                        return Ext.dd.DropZone.prototype.dropNotAllowed;
                                    },

                                    onNodeDrop : function (target, dd, e, data) {
                                        if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {
                                        
                                                this.setValue(data.node.attributes.text);

                                   
                                            return true;
                                        }
                                        return false;
                                    }.bind(el)
                                });
                            }
               }
               },
               items:[    
              
              {
          
                  xtype:"combo",
                  fieldLabel:'Price type',
                  triggerAction: 'all',                 
                    value:"net_customer",
                    lazyRender:true,
                    mode: 'local',
                    store: new Ext.data.ArrayStore({
                        id: 0,
                        fields: [
                            'displayText'
                        ],
                        data: [['net_customer'],['net_list'], ['nrp'],['on_request']]
                    }),
                    valueField: 'displayText',
                    displayField: 'displayText',
                  id:'price_type'
              },
              {
                
                  fieldLabel:'Ammount',
                  id:'ammount',
                  allowBlank:false
              }
              ,
              {
                  xtype:'combo',
                  fieldLabel:'Currency',
                  triggerAction: 'all',                 
                    value:"EUR",
                    lazyRender:true,
                    mode: 'local',
                    store: new Ext.data.ArrayStore({
                        id: 0,
                        fields: [
                            'displayText'
                        ],
                        data: [['EUR'],['USD']]
                    }),
                    valueField: 'displayText',
                    displayField: 'displayText',
                  id:'currency'
              }]},
              {
               xtype:'fieldset',
               title:'Mime Info',
               id:'mime_info',
               collapsible:true,
               width:330,
               defaults:{
                    width: 200,
                    xtype: "textfield",
                    labelStyle:"margin-left:5px",
                    listeners:{
                          "render": function (el) {
                                     new Ext.dd.DropZone(el.getEl(), {
                                         reference: this,
                                         ddGroup: "element",
                                         getTargetFromEvent: function(e) {
                                             return this.getEl();
                                         }.bind(el),

                                         onNodeOver : function(target, dd, e, data) {

                                             if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {

                                                 return Ext.dd.DropZone.prototype.dropAllowed;
                                             }
                                             return Ext.dd.DropZone.prototype.dropNotAllowed;
                                         },

                                         onNodeDrop : function (target, dd, e, data) {
                                             if (data.node.attributes.elementType == "element" && data.node.attributes.qtipCfg.type == "data") {

                                                     this.setValue(data.node.attributes.text);

                                                 return true;
                                             }
                                             return false;
                                         }.bind(el)
                                     });
                                 }
                    }
                },
                items:[    
                    {
                        fieldLabel:'Mime Source',
                        id:'mime_source',
                        allowBlank:false
                    }
              
                ]
                },

                {
          
                  xtype:'textarea',
                  fieldLabel:'Features',
                  id:'features',
                   listeners:{
                     "render": function (el) {
                                new Ext.dd.DropZone(el.getEl(), {
                                    reference: this,
                                    ddGroup: "element",
                                    getTargetFromEvent: function(e) {
                                        return this.getEl();
                                    }.bind(el),

                                    onNodeOver : function(target, dd, e, data) {
                              
                                        if (data.node.attributes.elementType == "element") {
                                            
                                            return Ext.dd.DropZone.prototype.dropAllowed;
                                        }
                                        return Ext.dd.DropZone.prototype.dropNotAllowed;
                                    },

                                    onNodeDrop : function (target, dd, e, data) {
                                
                                          if(data.node.childNodes.length > 0 && data.node.attributes.elementType == "element"){
                                              for(var i=0; i<data.node.childNodes.length; i++){
                                                  if(this.value){
                                                      this.setValue(Ext.getCmp('features').getValue() + data.node.childNodes[i].attributes.text + ';');
                                                  }else{
                                                      this.setValue(data.node.childNodes[i].attributes.text + ';');
                                                  }    
                                              }
                                              return true;
                                          }
                                          else{
                                               if(this.value){
                                                   this.setValue(Ext.getCmp('features').getValue() + data.node.attributes.text + ';');
                                               }else{
                                                   this.setValue(data.node.attributes.text + ';');
                                               }
                                               return true;
                                          }
                                        return false;
                                    }.bind(el)
                                });
                            }
               }
              },
               {
                  xtype:'fieldset',
                  width:360,
                  title:'product references',
                  collapsible:true,
                  collapsed:true,
                  id:'product_references',
                  items:[
                      
                      {
                        xtype:'button',
                        text: 'Add reference',
                        style:'float:right;margin-right:8px;margin-top:15px',
                        iconCls: "pimcore_icon_add",
                        handler: BMEcat.addReference
                          
                      }
                      
                  ]   
               }
                ]
            
            }];
        
        /* T_UPDATE_PRODUCTS fields  */
        var T_UPDATE_PRODUCTS=[{
                fieldLabel:'test'
        }
            
        ];
        
        /* T_UPDATE_PRICES fields */
        var T_UPDATE_PRICES=[
            
        ];
        
        /* Select fields for choosing class which will be mapped and exported, and bmecat transaction */
        this.composite = new Ext.form.CompositeField({
            region:'north',
            border:true,
            height:50,
            style:"margin:10px 20px",
            items: [
                {   
                    xtype:'displayfield',
                    value:'Select class:'
                },
                {
                    xtype: 'combo',
                    id:'className',
                    store: classStore,
                    value:classStore.data.items[0].data.text,
                    triggerAction: 'all',
                    valueField: 'translatedText',
                    displayField: 'translatedText',
                    width:220,
                    listeners: {
                            "select": function(){
                                BMEcat.tree.enable();
                                BMEcat.tree.getLoader().dataUrl = '/plugin/BMEcat/admin/get-class/className/' + Ext.getCmp('className').getValue();
                                BMEcat.tree.getLoader().load(BMEcat.tree.root);
                                BMEcat.tree.getRootNode().expand();
                                BMEcat.mappingPanel.doLayout();
                           
                            }
                        }
                    
                },
                {
                    xtype:'displayfield',
                    value:'Select transaction:'
                },
                {
                    xtype     : 'combo',
                    id: "bme_transaction",
                    triggerAction: 'all',                 
                    value:"T_NEW_CATALOG",
                    lazyRender:true,
                    mode: 'local',
                    store: new Ext.data.ArrayStore({
                        id: 0,
                        fields: [
                            'displayText'
                        ],
                        data: [['T_NEW_CATALOG'],['T_UPDATE_PRODUCTS'], ['T_UPDATE_PRICES']]
                    }),
                    valueField: 'displayText',
                    displayField: 'displayText',
                    width:220,
                    listeners: {
                            "select": function(){
                                
                                
                                BMEcat.bmePanel.removeAll(true);
                                if(this.value == 'T_NEW_CATALOG'){
                                    for(var i = 0; i < T_NEW_CATALOG.length; i++){
                                        BMEcat.bmePanel.add(T_NEW_CATALOG[i]);
                                    }
                                }
                                else if(this.value == 'T_UPDATE_PRODUCTS'){
                                    for(var i = 0; i < T_UPDATE_PRODUCTS.length; i++){
                                        BMEcat.bmePanel.add(T_UPDATE_PRODUCTS[i]);
                                    }
                                }
                                else if(this.value == 'T_UPDATE_PRICES'){
                                    for(var i = 0; i < T_UPDATE_PRICES.length; i++){
                                        BMEcat.bmePanel.add(T_UPDATE_PRICES[i]);
                                    }
                                }
                                
                              
                                BMEcat.bmePanel.doLayout();
                           
                            }
                        }
                    
                }
            ] 
            
            
        });
        
        
        /* Class tree */
        BMEcat.tree = new Ext.tree.TreePanel({
                    id: "bme_class_tree",
                    region: "west",
                    useArrows:true,
                    autoScroll:true,
                    animate:true,
                    enableDD:true,
                    border: true,
                    split:true,
                    width: '28%',
                    height:'100%',
                    minSize: 100,
                    ddAppendOnly: true,
                    ddGroup: "element",

                    root: {
                        nodeType: 'async',
                        draggable:false,
                        id: 'root',
                        text: t("class"),
                        allowChildren: true
                    },
                    loader: new Ext.tree.TreeLoader({
                        dataUrl: '/plugin/BMEcat/admin/get-class/className/' + Ext.getCmp('className').getValue(),
                        requestMethod: "GET",
                        baseAttrs: {
//                            listeners: BMEcat.getTreeNodeListeners(),
                            reference: this,
                            allowDrop: true,
                            allowChildren: true,
                            isTarget: true
                        }
                    })
                });


        BMEcat.tree.on("render", function () {
            this.getRootNode().expand();
        });
        
        BMEcat.importTree = new Ext.tree.TreePanel({
            
            id: "bme_import_tree",
            region: "center",
            useArrows:true,
            autoScroll:true,
            animate:true,
            enableDD:true,
            split:true,
            width: '100%',
            height: 550,
            minSize: 100,
            ddAppendOnly: true,
            ddGroup: "element",

            root: {
                nodeType: 'async',
                draggable:false,
                iconCls: "bmecat_icon_import_folder_home",
                id: 'bmecat',
                text: "BMEcat",
                allowChildren: true
            },
            loader: new Ext.tree.TreeLoader({
                dataUrl: '/plugin/BMEcat/admin/get-xml-tree',
                requestMethod: "GET",
                baseAttrs: {
//                            listeners: BMEcat.getTreeNodeListeners(),
                    reference: this,
                    allowDrop: true,
                    allowChildren: true,
                    isTarget: true
                }
            })
        });
        
        BMEcat.importTree.on("render", function () {
            this.getRootNode().expand();
        });

        BMEcat.importButton = new Ext.form.CompositeField({
            id: "bme_import_button",
            border: true,
            height: 50,
            style:"padding:10px 30px",
            region:'north',
            items:  [ {
                        xtype:'button',
                        text: 'Upload',
                        iconCls: "pimcore_icon_add",
                        handler: BMEcat.showUpload

                    }]
         });
        
        
        BMEcat.importLeftTargetPanel = new Ext.Panel({
            id:         "bme_import_left_target_panel", 
            border:     true,
            autoScroll:true,
            height: 'auto',
            width: 250,
            region:'west',
            closable:   true,
            split: true,
            layout: "border",
            items:      [ BMEcat.importButton, BMEcat.importTree ]
         });
         

         
        
        /* Form with dropping fields for objects mapping */
        BMEcat.bmePanel = new Ext.form.FormPanel({
           region:'center',
           id:'bmecatMappingPanel',
           border: true,
           split:true,
           height:'100%',
           autoScroll:true,
           defaults:{
               width: 200,
               xtype: "textfield",
               labelStyle:"margin-left:5px",

   
           } , 
           items:T_NEW_CATALOG
        });
        
        
        /* Optional fields for objects mapping  */
        BMEcat.additionalMappingFields = new Ext.Panel({
            id:'additionalMappingFields',
            border:true,
            split:true,
            region:'east',
            autoScroll:true,
            width:'30%',
            items:[
                {
                  xtype:'fieldset',
                  id:'additionalMapping',
                  title:'Additional mapping fields',
                  style:'margin:10px auto',
                  width:270,
                  defaults:{
                    style:'margin-bottom:10px;'  
                  },
                  items:[
                      {
                          
                        xtype:'multiselect',
                        fieldLabel:'Product details',
                        id:'additionalProductDetails',
                        width:140,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText',
                                'valueText'
                            ],
                            data: [['Long description','desc_long'],['Manufacturer name','manufacturer'],['Delivery time','delivery'],['Keywords','keyword'],['Product order','product_order'],['Product type','product_type'],['Product category','product_category']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'valueText',
                        displayField: 'displayText'
                        
                    
                          
                      },
                      {
                          
                        xtype:'multiselect',
                        fieldLabel:'Product order details',
                        id:'additionalProductOrderDetails',
                        width:140,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText',
                                'valueText'
                            ],
                            data: [['Number order units per c.u.','ou_per_cu'],['Price quantity','price_quantity'],['Quantity minimum','quant_min'],['Quantity interval','quant_interv'],['Quantity maximum','quant_max']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'valueText',
                        displayField: 'displayText'
                        
                    
                          
                      },
                      {
                          
                        xtype:'multiselect',
                        fieldLabel:'Product price details',
                        id:'additionalProductPriceDetails',
                        width:140,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText',
                                'valueText'
                            ],
                            data: [['Tax','tax'],['Price factor','price_factor'],['Lower bound','lower_bound'],['Teritories','teritories']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'valueText',
                        displayField: 'displayText'
                        
                    
                          
                      },
                      {  
                        xtype:'multiselect',
                        fieldLabel:'Mime info',
                        id:'additionalMimeInfo',
                        width:140,
                        height:80,
                        store: new Ext.data.ArrayStore({
                            id: 0,
                            fields: [
                                'displayText',
                                'valueText'
                            ],
                            data: [['Type','mime_type'],['Description','mime_descr'],['Alternative Description','mime_alt'],['Purpose','mime_purpose']]
                        }),
                        value:'',
                        triggerAction: 'all',
                        valueField: 'valueText',
                        displayField: 'displayText'
                        
                      },
                      {
                         
                        xtype:'button',
                        text: 'Add mapping fields',
                        style:'float:right;margin-right:5px;margin-top:15px',
                        iconCls: "pimcore_icon_add",
                        handler: BMEcat.addMappingFields
                     
                      }
                  ]
                }
            ]
            
            
            
        });
        
        BMEcat.messagePanel = new Ext.Panel({
            id:         "bme_message_panel",
            border:     true,
            height: 25,
            style:'font-size:14px;text-align:center;color:#aaaaaa',
            region:'north',
            items:      [
                {
                    xtype:'displayfield',
                    value:'Drag and drop fields from class tree to map objects data and bmecat tags'
                    
                }
            ]
         });
                    
         BMEcat.mappingPanel = new Ext.Panel({
            id:         "bme_mapping_panel",
            border:     true,
            autoScroll:true,
            height: 'auto',
            layout:'border',
            region:'center',
            closable:   true,
            items:      [BMEcat.tree,BMEcat.bmePanel,BMEcat.additionalMappingFields,BMEcat.messagePanel]
         });        
          
        BMEcat.compositeImport = new Ext.form.CompositeField({
            region:'north',
            border:true,
            height:50,
            style:"padding:10px 20px",
            width:400,
            items: [
                {   
                    xtype:'displayfield',
                    value:'Select class:'
                },
                {
                    xtype  : 'combo',
                    id:'classNameImport',
                    store: classStore,
                    value:classStore.data.items[0].data.text,
                    triggerAction: 'all',
                    valueField: 'translatedText',
                    displayField: 'translatedText',
                    width:120,
                    listeners: {
                            "select": function(){
                                
                                Ext.Ajax.request({
                                    url: "/plugin/BMEcat/admin/get-class-fields",
                                    method: "post",
                                    params: {
                                        className: this.value

                                    },
                                    success: function(response){
                                      //  alert(response.responseText);
                                        console.log(Ext.decode(response.responseText));
                                        var rsp = Ext.decode(response.responseText);
                                        console.log(rsp.availableFields);
                                        
                                        var formFields = [];
                                        var availableFieldLabel;
                                        for (var i = 0; i < rsp.availableFields.length; i++) {
                                            
                                           
                                            
                                            if (rsp.availableFields[i].length > 25) {
                                                availableFieldLabel = (rsp.availableFields[i].substr(0,25)) + ".";
                                            } else {
                                                availableFieldLabel = rsp.availableFields[i];
                                            }
                                            
                                            console.log(availableFieldLabel);
                                            
                                            var item = {
                                                id: rsp.availableFields[i],
                                                fieldLabel : availableFieldLabel //rsp.availableFields[i]
                                            };
                                            formFields.push(item);
                                        }
                                        BMEcat.importObjectFields.removeAll();
                                        BMEcat.importObjectFields.add(formFields);
                                        BMEcat.importObjectFields.doLayout();
                                    }
                                });                          
                            }
                        }
                    
                },
                {   
                    xtype:'displayfield',
                    value:'Object(s) Name:',
                    style:"padding-left:15px",
                },
                {
                        xtype:'textfield',
                        id:'import_object_name_field',
                        width:120,
                        allowBlank:false
                }
//                {
//                    xtype  : 'combo',
//                    id:'targetFolderImport',
//                    store: classStore,
//                    value:classStore.data.items[0].data.text,
//                    triggerAction: 'all',
//                    valueField: 'translatedText',
//                    displayField: 'translatedText',
//                    width:220, 
//                }
            ]
         });

         BMEcat.importObjectFields = new Ext.form.FormPanel({
           bodyStyle: "padding: 10px 20px;",
           region:"center",
           id:'import_object_fields',
           border: true,
           autoScroll: true,
           labelWidth: 160,
        //   width:'50%',
           defaults:{
                width: 250,
                xtype: "textfield",
                
                listeners:{
                 "render": function (el) {
                            new Ext.dd.DropZone(el.getEl(), {
                                reference: this,
                                ddGroup: "element",
                                getTargetFromEvent: function(e) {
                                    return this.getEl();
                                }.bind(el),
                                onNodeOver : function(target, dd, e, data) {                   
                                    if (data.node.attributes.elementType == "element") {
                                        return Ext.dd.DropZone.prototype.dropAllowed;
                                    }
                                    return Ext.dd.DropZone.prototype.dropNotAllowed;
                                },
                                onNodeDrop : function (target, dd, e, data) {
                                      if(data.node.attributes.elementType == "element") {
                                          
                                          
                                      //   var parentObject = BMEcat.findParent(data.node);
                                      //    console.log(data.node.parentNode.text);
                                       var tree = Array();
                                        var parentTree = BMEcat.recursiveParentNode(data.node, tree);
                                        
                                        parentTree = parentTree.reverse();
                                        var strTree = "";
                                        for (var i = 0; i < parentTree.length; i++) {
                                            if (strTree !== "") {
                                                strTree = strTree + "->" +  parentTree[i];
                                            } else {
                                                strTree = parentTree[i];
                                            } 
                                        }
                                        strTree = strTree + "->" + data.node.attributes.text;
                                        
                                        this.setValue(strTree);
                                        
                                          console.log(strTree);

                                          return true;
                                      }
                                    return false;
                                }.bind(el)
                            });
                        }
                   }
           } , 
           
           items:[
               
           ]
           
        });
        
        
        BMEcat.importClassFields = new Ext.Panel({
            id:"bme_class_fields",
            border:true,
            autoScroll:true,
            height: 'auto',
            region:'center',
            layout:'border',
            closable:   true,
            items:      [BMEcat.compositeImport , BMEcat.importObjectFields]
         });

         BMEcat.tabExport = new Ext.TabPanel({
            autoTabs:true,
            activeTab:0,
            deferredRender:false,
            border:false,
            items:[
                {
                    xtype: "panel",
                    title: "General information",
                    border:true,
                    layout: "border",
                    items: [this.generalForm,this.additionalFields]
                },
                {
                    xtype: "panel",
                    border:  true,
                    title: t("data_mapping"),
                    layout: "border",
                    items: [this.composite,BMEcat.mappingPanel]
                }
            ],
            buttons: [{
                    text:'Submit',
                    handler: BMEcat.submitData.bind(this)
                }
            ]
        });
      
        BMEcat.messageImportPanel = new Ext.Panel({
            id:         "bme_message_import_panel",
            border:     true,
            height: 25,
            style:'font-size:14px;text-align:center;color:#aaaaaa',
            region:'north',
            items:[
                {
                    xtype:'displayfield',
                    value:'Drag and drop bmecat field tree tags to map objects data'                  
                }
            ]
         }); 
      
        BMEcat.importTargetTree = new Ext.tree.TreePanel({
            
            id: "bme_import_target_tree",
            region: "center",
            useArrows:true,
            autoScroll:true,
            animate:true,
            enableDD:true,
            split:true,
            width: '100%',
            height: 550,
            minSize: 100,
            ddAppendOnly: true,
            ddGroup: "element",

            root: {
                nodeType: 'async',
                draggable:false,
                id: 1,
                text: "Home (Objects)",
                iconCls: "pimcore_icon_home",
                allowChildren: true
            },
            loader: new Ext.ux.tree.PagingTreeLoader({
                dataUrl: '/plugin/BMEcat/admin/tree-get-childs-by-id/',
                requestMethod: "GET",
                baseParams: {
                        "node": 1,
                        "limit": 30,
                        "start": 0
                        
                    },
                baseAttrs: {
                    reference: this,
                    allowDrop: true,
                    allowChildren: true,
                    isTarget: true
                }
            })
        });
      
        BMEcat.importTargetTopComposite = new Ext.form.CompositeField({
            id:         "bme_import_target_textfield",
            border:     true,
            autoScroll: true,
            height: 50,
            region:'north',

             style:"padding:10px 20px",

            items: [{   
                        xtype:'displayfield',
                        value:'Destination:'
                    },{
                        xtype:'textfield',
                        id:'import_target_field',
                        width:120,
                        allowBlank:false,
                        listeners:{
                              "render": function (el) {
                                         new Ext.dd.DropZone(el.getEl(), {
                                             reference: this,
                                             ddGroup: "element",
                                             getTargetFromEvent: function(e) {
                                                 return this.getEl();
                                             }.bind(el),

                                             onNodeOver : function(target, dd, e, data) {

                                                 if (data.node.attributes.elementType == "folder" || 
                                                         data.node.attributes.elementType == "object") {

                                                     return Ext.dd.DropZone.prototype.dropAllowed;
                                                 }
                                                 return Ext.dd.DropZone.prototype.dropNotAllowed;
                                             },

                                             onNodeDrop : function (target, dd, e, data) {
                                                 if (data.node.attributes.elementType == "folder" || 
                                                         data.node.attributes.elementType == "object") {

                                                         console.log(data.node);
                                                         this.setValue(data.node.attributes.path);


                                                     return true;
                                                 }
                                                 return false;
                                             }.bind(el)
                                         });
                                     }
                            }

                     }
                    ]
         });
      
      
        BMEcat.importRightTargetPanel = new Ext.Panel({
            id:         "bme_import_right_target_panel",
            border:     true,
            autoScroll:true,
            height: 'auto',
            width: 250,
            region:'east',
            closable:   true,
            split: true,
            layout: "border",
            items:      [
                BMEcat.importTargetTopComposite, 
                BMEcat.importTargetTree
            ]
         });
      
      
      
        BMEcat.tabImport = new Ext.TabPanel({
            autoTabs:true,
            activeTab:0,
            deferredRender:false,
            border:false,
            items:[
                {
                    xtype: "panel",
                    border:  true,
                    title: t("data_mapping"),
                    layout: "border",
                    items: [
                BMEcat.importLeftTargetPanel , BMEcat.importClassFields, BMEcat.messageImportPanel, BMEcat.importRightTargetPanel]
                }
            ],
            buttons: [{
                    text:'Submit',
                    handler: BMEcat.submitImportData.bind(this)
                }
            ]
        });  
               
            
            

            
                
     if(!BMEcat.win){
         BMEcat.win = new Ext.Window({
                id: "bmecat_window",
                modal: true,
                width: 1000,
                height: 750,
                style: "z-index:0",
                layout: "fit",
                closeAction:'hide',
                plain: true,

                items: new Ext.TabPanel({
                            autoTabs:true,
                            activeTab:0,
                            deferredRender:false,
                            border:false,
                            items:[{
                                    xtype: "panel",
                                    title: "Import",
                                    border:true,
                                    layout: "fit",
                                    items: [BMEcat.tabImport]
                                },
                                {
                                    xtype: "panel",
                                    title: "Export",
                                    border:true,
                                    layout: "fit",
                                    items: [BMEcat.tabExport]
                                }
                         ]
                        }),
                
                    
                        

                buttons: [/*{
                    text:'Submit',
                    handler: BMEcat.submitData.bind(this)
                },*/{
                    text: 'Close',
                    handler: function(){
                       BMEcat.win.hide();
                    }
                }]
            });
     
            BMEcat.win.show();
         }else{
              BMEcat.win.show();
         }

    },

    recursiveParentNode: function (data, tree) {

        if(data.parentNode) {
            tree.push(data.parentNode.text);
            this.recursiveParentNode(data.parentNode, tree);
        }
        return tree;
    },



    getTreeNodeListeners: function () {
        var treeNodeListeners = {
            "click" : this.onTreeNodeClick.bind(this),
         //   "contextmenu": this.onTreeNodeContextmenu,
            
        };

        return treeNodeListeners;
    },
            
    onTreeNodeClick: function(){

    },
      
    onTreeNodeContextmenu: function () {

         this.select();



    },
    
    addField: function(){

       /* Define all optional fields and add them to the general form if they are chosen  */
       var catalogFields = Ext.getCmp('additionalCatalogFields').getValue().split(',');
       var buyerFields = Ext.getCmp('additionalBuyerFields').getValue().split(',');
       var supplierFields = Ext.getCmp('additionalSupplierFields').getValue().split(',');
       var datetime = {
         xtype:'compositefield',
         id:'datetime',
         fieldLabel:'Datetime',
         allowBlank:true,
         items:[
          {
              xtype:'datefield',
              width:115,
              id:'dateGeneration'
          },
          {
              xtype:'timefield',
              width:115,
              id:'timegeneration'
          }
         ]
       };
       var startDate = {
           xtype:'datefield',
           fieldLabel:'Valid start date',
           id:'valid_start',
           allowBlank:true
       };
       var endDate = {
           xtype:'datefield',
           fieldLabel:'Valid end date',
           id:'valid_end',
           allowBlank:true
       };
       var teritories = {
           xtype:'multiselect',
           fieldLabel:'Teritories',
           id:'teritories',
            store: new Ext.data.ArrayStore({
                id: 0,
                fields: [
                    'displayText'
                ],
                data: [['EU'],['CH'],['SR'],['MN']]
            }),
            value:'',
            expandData:false,
            allowBlank:true,
            triggerAction: 'all',
            height:50,
            valueField: 'displayText',
            displayField: 'displayText'
           
       };
       var mime = {
           fieldLabel:'Mime root',
           id:'mime_root',
           allowBlank:true
       };
       var productType = {
           xtype:'combo',
           fieldLabel:'Product type',
           id:'product_type',
           mode:'local',
           lazyRender:true,
           store: new Ext.data.ArrayStore({
                id: 0,
                fields: [
                    'displayText'
                ],
                data: [['bundle'],['component'],['configurable'],['contract'],['license'],['major'],['minor'],['must_be_configured'],['physical'],['professional_services'],['service']]
            }),
            value:'',
            allowBlank:true,
            triggerAction: 'all',
            valueField: 'displayText',
            displayField: 'displayText'
           
       };
       var priceFlag = {
           id:'price_flag_group',
            allowBlank:true,
            xtype: 'checkboxgroup',
            fieldLabel: 'Price flag',
            itemCls: 'x-check-group-alt',
            // Put all controls in a single column with width 100%
            columns: 2,
            items: [
                {boxLabel: 'Including duty', id: 'incl_duty'},
                {boxLabel: 'Including freight', id: 'incl_freight'},
                {boxLabel: 'Including insurance', id: 'incl_insurance'},
                {boxLabel: 'Including packing', id: 'incl_packing'}
            ]
       };
       var priceFactor = {
         xtype:'spinnerfield',
         fieldLabel:'Price factor',        
         id:'price_factor',
         minValue: 0.1,
         maxValue: 1,
         allowDecimals: true,
         decimalPrecision: 1,
         incrementValue: 0.1,
         value:1  
       };
       
       var buyerDepartment = {
         id:'buyer_department',
         fieldLabel:'Department',
         allowBlank:true
       };
       var buyerStreet = {
         id:'buyer_street',
         fieldLabel:'Street',
         allowBlank:true
       };
       var buyerZip = {
           xtype:'spinnerfield',
           id:'buyer_zip',
           fieldLabel:'Zip',
           allowBlank:true
       };
       var buyerCity = {
           id:'buyer_city',
           fieldLabel:'City',
           allowBlank:true
       };
       var buyerCountry = {
           id:'buyer_country',
           fieldLabel:'Country',
           allowBlank:true
       };
       var buyerPhone = {
         xtype:'compositefield',
         id:'buyer_phone',
         fieldLabel:'Phone',
         allowBlank:true,
         items:[
          {
              xtype:'multiselect',
              width:115,
              id:'buyer_phone_type',
              mode:'local',
              lazyRender:true,
              store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['mobile'],['office'],['private']]
                }),
               value:'',
               allowBlank:true,
               triggerAction: 'all',
               valueField: 'displayText',
               displayField: 'displayText'
          },
          {
              xtype:'spinnerfield',
              allowBlank:true,
              width:115,
              id:'buyer_phone_number'
          }
         ]
       };
       var buyerFax = {
          xtype:'spinnerfield',
          id:'buyer_fax',
          fieldLabel:'Fax'
       };
       var buyerUrl = {
          id:'buyer_url',
          fieldLabel:'Url',
          allowBlank:true
       };
       
       var supplierDepartment = {
         id:'supplier_department',
         fieldLabel:'Department',
         allowBlank:true
       };
       var supplierStreet = {
         id:'supplier_street',
         fieldLabel:'Street',
         allowBlank:true
       };
       var supplierZip = {
           xtype:'spinnerfield',
           id:'supplier_zip',
           fieldLabel:'Zip',
           allowBlank:true
       };
       var supplierCity = {
           id:'supplier_city',
           fieldLabel:'City',
           allowBlank:true
       };
       var supplierCountry = {
           id:'supplier_country',
           fieldLabel:'Country',
           allowBlank:true
       };
       var supplierPhone = {
         xtype:'compositefield',
         id:'supplier_phone',
         fieldLabel:'Phone',
         allowBlank:true,
         items:[
          {
              xtype:'multiselect',
              width:115,
              id:'supplier_phone_type',
              mode:'local',
              lazyRender:true,
              store: new Ext.data.ArrayStore({
                    id: 0,
                    fields: [
                        'displayText'
                    ],
                    data: [['mobile'],['office'],['private']]
                }),
               value:'',
               allowBlank:true,
               triggerAction: 'all',
               valueField: 'displayText',
               displayField: 'displayText'
          },
          {
              xtype:'spinnerfield',
              allowBlank:true,
              width:115,
              id:'supplier_phone_number'
          }
         ]
       };
       var supplierFax = {
          xtype:'spinnerfield',
          id:'supplier_fax',
          fieldLabel:'Fax',
          allowBlank:true
       };
       var supplierUrl = {
          id:'supplier_url',
          fieldLabel:'Url',
          allowBlank:true
       };
      
       for(var i = 0; i < catalogFields.length; i++){
           switch(catalogFields[i]){
               case 'datetime':
                   if(!Ext.getCmp('datetime')){
                       Ext.getCmp('catalogFieldset').add(datetime);
                   }
                   break;
               case 'valid-start-date':
                   if(!Ext.getCmp('valid_start')){
                       Ext.getCmp('catalogFieldset').add(startDate);
                   }
                   break;
               case 'valid-end-date':
                   if(!Ext.getCmp('valid_end')){
                       Ext.getCmp('catalogFieldset').add(endDate);
                   }
                   break; 
               case 'teritories':
                   if(!Ext.getCmp('teritories')){
                       Ext.getCmp('catalogFieldset').add(teritories);
                   }
                   break;
               case 'mime-root':
                   if(!Ext.getCmp('mime_root')){
                       Ext.getCmp('catalogFieldset').add(mime);
                   }
                   break; 
               case 'product-type':
                   if(!Ext.getCmp('product_type')){
                       Ext.getCmp('catalogFieldset').add(productType);
                   }
                   break;
               case 'price-flag':
                   if(!Ext.getCmp('price_flag_group')){
                       Ext.getCmp('catalogFieldset').add(priceFlag);
                   }
                   break; 
               case 'price-factor':
                   if(!Ext.getCmp('price_factor')){
                       Ext.getCmp('catalogFieldset').add(priceFactor);
                   }
                   break;
           }
       }
       for(var i = 0; i < buyerFields.length; i++){
           switch(buyerFields[i]){
               case 'department':
                   if(!Ext.getCmp('buyer_department')){
                       Ext.getCmp('buyerFieldset').add(buyerDepartment);
                   }
                   break;
               case 'street':
                   if(!Ext.getCmp('buyer_street')){
                       Ext.getCmp('buyerFieldset').add(buyerStreet);
                   }
                   break;
               case 'zip':
                   if(!Ext.getCmp('buyer_zip')){
                       Ext.getCmp('buyerFieldset').add(buyerZip);
                   }
                   break; 
               case 'city':
                   if(!Ext.getCmp('buyer_city')){
                       Ext.getCmp('buyerFieldset').add(buyerCity);
                   }
                   break;
               case 'country':
                   if(!Ext.getCmp('buyer_country')){
                       Ext.getCmp('buyerFieldset').add(buyerCountry);
                   }
                   break; 
               case 'phone':
                   if(!Ext.getCmp('buyer_phone')){
                       Ext.getCmp('buyerFieldset').add(buyerPhone);
                   }
                   break;
               case 'fax':
                   if(!Ext.getCmp('buyer_fax')){
                       Ext.getCmp('buyerFieldset').add(buyerFax);
                   }
                   break; 
               case 'url':
                   if(!Ext.getCmp('buyer_url')){
                       Ext.getCmp('buyerFieldset').add(buyerUrl);
                   }
                   break;
           }
       }
       for(var i = 0; i < supplierFields.length; i++){
           switch(supplierFields[i]){
               case 'department':
                   if(!Ext.getCmp('supplier_department')){
                       Ext.getCmp('supplierFieldset').add(supplierDepartment);
                   }
                   break;
               case 'street':
                   if(!Ext.getCmp('supplier_street')){
                       Ext.getCmp('supplierFieldset').add(supplierStreet);
                   }
                   break;
               case 'zip':
                   if(!Ext.getCmp('supplier_zip')){
                       Ext.getCmp('supplierFieldset').add(supplierZip);
                   }
                   break; 
               case 'city':
                   if(!Ext.getCmp('supplier_city')){
                       Ext.getCmp('supplierFieldset').add(supplierCity);
                   }
                   break;
               case 'country':
                   if(!Ext.getCmp('supplier_country')){
                       Ext.getCmp('supplierFieldset').add(supplierCountry);
                   }
                   break; 
               case 'phone':
                   if(!Ext.getCmp('supplier_phone')){
                       Ext.getCmp('supplierFieldset').add(supplierPhone);
                   }
                   break;
               case 'fax':
                   if(!Ext.getCmp('supplier_fax')){
                       Ext.getCmp('supplierFieldset').add(supplierFax);
                   }
                   break; 
               case 'url':
                   if(!Ext.getCmp('supplier_url')){
                       Ext.getCmp('supplierFieldset').add(supplierUrl);
                   }
                   break;
           }
       }
       Ext.getCmp('generalForm').doLayout();


    },
    
    addMappingFields: function(){

//additionalMappingFields
         var fields = Ext.getCmp('additionalProductDetails').getValue().split(',');
         var orderfields = Ext.getCmp('additionalProductOrderDetails').getValue().split(',');
         var pricefields = Ext.getCmp('additionalProductPriceDetails').getValue().split(',');
         var mimefields = Ext.getCmp('additionalMimeInfo').getValue().split(',');
         
         var desc_long = {
             id:'desc_long',
             fieldLabel:'Long description',
             allowBlank:true
         };
         var manufacturer = {
             id:'manufacturer',
             fieldLabel:'Manufacturer name',
             allowBlank:true
         };
         var delivery = {
             id:'delivery',
             fieldLabel:'Delivery time',
             allowBlank:true
         };
         var keywords = {
             id:'keywords',
             fieldLabel:'keywords',
             allowBlank:true
         };
         var product_order = {
             id:'product_order',
             fieldLabel:'Product order',
             allowBlank:true
         };
         var product_type = {
             id:'product_type',
             fieldLabel:'Product type',
             allowBlank:true
         };
         var product_category = {
             id:'product_category',
             fieldLabel:'Product category',
             allowBlank:true
         }; 
         var ou_per_cu = {
             id:'ou_per_cu',
             fieldLabel:'Number order units per c.u.',
             allowBlank:true
         };      
         var price_quantity = {
             id:'price_quantity',
             fieldLabel:'Price quantity',
             allowBlank:true
         };
         var quant_min = {
             id:'quant_min',
             fieldLabel:'Quantity minimum',
             allowBlank:true
         };
         var quant_interv = {
             id:'quant_interv',
             fieldLabel:'Quantity interval',
             allowBlank:true
         };
         var quant_max = {
             id:'quant_max',
             fieldLabel:'Quantity maximum',
             allowBlank:true
         };
         var tax = {
             id:'tax',
             fieldLabel:'Tax',
             allowBlank:true
         };
         var price_factor = {
             id:'price_factor',
             fieldLabel:'Price factor',
             allowBlank:true
         };
         var lower_bound = {
             id:'lower_bound',
             fieldLabel:'Lower bound',
             allowBlank:true
         };
         var teritories = {
             id:'teritories',
             fieldLabel:'Teritories',
             allowBlank:true
         };         
         var mime_type = {
             id:'mime_type',
             fieldLabel:'Type',
             allowBlank:true
         }; 
         var mime_descr = {
             id:'mime_descr',
             fieldLabel:'Description',
             allowBlank:true
         }; 
         var mime_alt = {
             id:'mime_alt',
             fieldLabel:'Alternative Description',
             allowBlank:true
         }; 
         var mime_purpose = {
             id:'mime_purpose',
             fieldLabel:'Purpose',
             allowBlank:true
         }; 
        
        
        for(var i = 0; i < fields.length; i++){
           switch(fields[i]){
               case 'desc_long':
                   if(!Ext.getCmp('desc_long')){
                       Ext.getCmp('product_details').add(desc_long);
                   }
                   break;
               case 'manufacturer':
                   if(!Ext.getCmp('manufacturer')){
                       Ext.getCmp('product_details').add(manufacturer);
                   }    
                   break;
               case 'delivery':
                   if(!Ext.getCmp('delivery')){
                       Ext.getCmp('product_details').add(delivery);
                   }
                   break; 
               case 'keyword':
                   if(!Ext.getCmp('keywords')){
                       Ext.getCmp('product_details').add(keywords);
                   }
                   break;
               case 'product_order':
                   if(!Ext.getCmp('product_order')){
                       Ext.getCmp('product_details').add(product_order);
                   }
                   break; 
               case 'product_type':
                   if(!Ext.getCmp('product_type')){
                       Ext.getCmp('product_details').add(product_type);
                   }
                   break;
               case 'product_category':
                   if(!Ext.getCmp('product_category')){
                       Ext.getCmp('product_details').add(product_category);
                   }
                   break; 
               
           }
       }       
        for(var i = 0; i < orderfields.length; i++){
           switch(orderfields[i]){
               case 'ou_per_cu':
                   if(!Ext.getCmp('ou_per_cu')){
                       Ext.getCmp('product_order_details').add(ou_per_cu);
                   }
                   break;
               case 'price_quantity':
                   if(!Ext.getCmp('price_quantity')){
                       Ext.getCmp('product_order_details').add(price_quantity);
                   }    
                   break;
               case 'quant_min':
                   if(!Ext.getCmp('quant_min')){
                       Ext.getCmp('product_order_details').add(quant_min);
                   }
                   break; 
               case 'quant_interv':
                   if(!Ext.getCmp('quant_interv')){
                       Ext.getCmp('product_order_details').add(quant_interv);
                   }
                   break;
               case 'quant_max':
                   if(!Ext.getCmp('quant_max')){
                       Ext.getCmp('product_order_details').add(quant_max);
                   }
                   break;  
           }
       }       
        for(var i = 0; i < pricefields.length; i++){
           switch(pricefields[i]){
               case 'tax':
                   if(!Ext.getCmp('tax')){
                       Ext.getCmp('product_price_details').add(tax);
                   }
                   break;
               case 'price_factor':
                   if(!Ext.getCmp('price_factor')){
                       Ext.getCmp('product_price_details').add(price_factor);
                   }    
                   break;
               case 'lower_bound':
                   if(!Ext.getCmp('lower_bound')){
                       Ext.getCmp('product_price_details').add(lower_bound);
                   }
                   break; 
               case 'teritories':
                   if(!Ext.getCmp('teritories')){
                       Ext.getCmp('product_price_details').add(teritories);
                   }
                   break;
           }
       }      
        for(var i = 0; i < mimefields.length; i++){
           switch(mimefields[i]){
               case 'mime_type':
                   if(!Ext.getCmp('mime_type')){
                       Ext.getCmp('mime_info').add(mime_type);
                   }
                   break;
               case 'mime_descr':
                   if(!Ext.getCmp('mime_descr')){
                       Ext.getCmp('mime_info').add(mime_descr);
                   }    
                   break;
               case 'mime_alt':
                   if(!Ext.getCmp('mime_alt')){
                       Ext.getCmp('mime_info').add(mime_alt);
                   }
                   break; 
               case 'mime_purpose':
                   if(!Ext.getCmp('mime_purpose')){
                       Ext.getCmp('mime_info').add(mime_purpose);
                   }
                   break;
           }
       } 
        
        Ext.getCmp('bmecatMappingPanel').doLayout();
    },
            
   findParent: function(data){
     if(data.parentNode && data.parentNode.attributes.qtipCfg.fieldtype == 'objects'){
         return data.parentNode.attributes.text;
     }
     else if(data.parentNode && data.parentNode.attributes.qtipCfg.fieldtype !== 'objects'){
         return BMEcat.findParent(data.parentNode);
     }
     else{
         return false;
     }
   }, 
           
   addReference: function(){
       
       var item = {
           
          xtype:'compositefield',
          fieldLabel:'Ref(type,object id,catalog id)',        
          items:[
             {
                  xtype:'combo',
                  width:80,
                  triggerAction: 'all',                 
                  value:"accessories",
                  lazyRender:true,
                  mode: 'local',
                  store: new Ext.data.ArrayStore({
                        id: 0,
                        fields: [
                            'displayText'
                        ],
                        data: [['accessories'],['base_product'],['consist_of'],['diff_orderunit'],['followup'],['mandatory'],['similar'],['select'],['sparepart'],['other']]
                    }),
                  valueField: 'displayText',
                  displayField: 'displayText',
                  id:'reference_type_' + BMEcat.i

             },
             {
                 xtype:'textfield',
                 id:'reference_field_' + BMEcat.i,
                 width:80,
                 listeners:{
                         "render": function (el) {
                                    new Ext.dd.DropZone(el.getEl(), {
                                        reference: this,
                                        ddGroup: "element",
                                        getTargetFromEvent: function(e) {
                                            return this.getEl();
                                        }.bind(el),

                                        onNodeOver : function(target, dd, e, data) {
                                            var parentObject = BMEcat.findParent(data.node);
                                            if (data.node.attributes.elementType == "element" && parentObject) {

                                                return Ext.dd.DropZone.prototype.dropAllowed;
                                            }
                                            return Ext.dd.DropZone.prototype.dropNotAllowed;
                                        },

                                        onNodeDrop : function (target, dd, e, data) {
                                              var parentObject = BMEcat.findParent(data.node);
                                              if(data.node.attributes.elementType == "element" && parentObject){
                                                  this.setValue(parentObject + '->' + data.node.attributes.text);
                                                  return true;
                                              }

                                            return false;
                                        }.bind(el)
                                    });
                                }
                   }
             },
             {
                 xtype:'textfield',
                 id:'catalog_id_' + BMEcat.i,
                 width:60
                 
                 
             }
          ]
                      
       };
       
       Ext.getCmp('product_references').insert(0,item);
       Ext.getCmp('bmecatMappingPanel').doLayout();
       BMEcat.i++;
   },   
   
   showUpload: function () {
        console.log(BMEcat.importId);
   //    Ext.getCmp("bmecat_window").hide();
        pimcore.helpers.uploadDialog('/plugin/BMEcat/admin/import-upload/?pimcore_admin_sid='
                        + pimcore.settings.sessionId + "&id=" + BMEcat.importId, "Filedata", function(res) {
            BMEcat.getFileInfo();
        }.bind(this), function () {
            Ext.MessageBox.alert(t("error"), t("error"));
        });
    },

    getFileInfo: function () {
//        Ext.Ajax.request({
//            url: "/plugin/BMEcat/admin/import-get-file-info",
//            params: {
//                id: this.importId,
//                method: "post"
//            },
//            success: function(data) {
        //         Ext.getCmp("bmecat_window").show();
                BMEcat.importTree.enable();
                BMEcat.importTree.getLoader().dataUrl = '/plugin/BMEcat/admin/import-get-file-info/id/' + BMEcat.importId;
                BMEcat.importTree.getLoader().load(BMEcat.importTree.root);
                BMEcat.importTree.getRootNode().expand();
                BMEcat.importLeftTargetPanel.doLayout();
               
//            }
//        });
    },
   

   submitData: function(){
          var headerInfo = Ext.encode(this.generalForm.getForm().getValues());
          var mappingInfo = Ext.encode(BMEcat.bmePanel.getForm().getValues());
       
          if(this.generalForm.getForm().isValid() && BMEcat.bmePanel.getForm().isValid()){
              Ext.Ajax.request({
                        url: "/plugin/BMEcat/admin/create-xml",
                        method: "post",
                        params: {
                            headerInfo: headerInfo,
                            mapping: mappingInfo,
                            refNum:BMEcat.i, //number of reference fields, important because we add counter i at the end of reference id (id:'reference_field_' + i )
                            class: Ext.getCmp('className').getValue(),
                            transaction: Ext.getCmp('bme_transaction').getValue()
                        },
                        success: function(response){
//                            alert(response.responseText);
                            pimcore.helpers.showNotification(t("success"), response.responseText, "success");

                        }
                    });
          
          
            }
            else{
                Ext.msg.alert('Fill all required fields!')
            }
        },

   submitImportData: function() {
       
       //   var headerInfo = Ext.encode(this.generalForm.getForm().getValues());
          var mappingInfo = Ext.encode(BMEcat.importObjectFields.getForm().getValues());
          

     //   var mappingInfo = Ext.getCmp('import_object_fields').getForm().getValues();
        
          console.log(mappingInfo);
      
       
          if(BMEcat.importObjectFields.getForm().isValid() && 
                Ext.getCmp('import_target_field').isValid() && 
                    Ext.getCmp('import_object_name_field').isValid()
            ) {
              Ext.Ajax.request({
                        url: "/plugin/BMEcat/admin/create-objects",
                        method: "post",
                        params: {
                            mapping: mappingInfo,
                     //       refNum:BMEcat.i, //number of reference fields, important because we add counter i at the end of reference id (id:'reference_field_' + i )
                            classImport: Ext.getCmp('classNameImport').getValue(),
                            id: BMEcat.importId,
                            targetPath: Ext.getCmp('import_target_field').getValue(),
                            objectName: Ext.getCmp('import_object_name_field').getValue()
                            
                   //         transaction: Ext.getCmp('bme_transaction').getValue()
                            
                        },
                        success: function(response){
//                            alert(response.responseText);
                            pimcore.helpers.showNotification(t("success"), response.responseText, "success");

                        }
                    });

            }
            else{
                Ext.msg.alert('Fill all required fields!')
            }
        }


    });

var BMEcat = new pimcore.plugin.BMEcat();
