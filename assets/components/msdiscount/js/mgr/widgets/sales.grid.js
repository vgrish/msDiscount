msDiscount.grid.Sales = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-sales'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/sales/getlist'
		}
		,autosave: true
		,save_action: 'mgr/sales/updatefromgrid'
		,preventSaveRefresh: false
		,fields: ['id','discount','name','description','begins','ends','active','image']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: this.getColumns(config)
		,tbar: [{
			text: _('msd_menu_create')
			,handler: this.createSale
			,scope: this
		}]
		/*
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateSale(grid, e, row);
			}
		}*/
	});
	msDiscount.grid.Sales.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.grid.Sales,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('msd_menu_update')
			,handler: this.updateSale
		});
		m.push('-');
		m.push({
			text: _('msd_menu_remove')
			,handler: this.removeSale
		});
		this.addContextMenuItem(m);
	}

	,getColumns: function(config) {
		var columns = {
			id: {hidden: true, width: 50}
			,name: {width: 100, editor: {xtype: 'textfield'}}
			,discount: {width: 100, editor: {xtype: 'textfield'}}
			,description: {hidden: true}
			,begins: {width: 100, renderer: miniShop2.utils.formatDate}
			,ends: {width: 100, renderer: miniShop2.utils.formatDate}
			,active: {width: 50, editor: {xtype: 'combo-boolean', renderer:'boolean'}}
			,image: {width: 100, renderer: this.renderThumb}
		};
		var tmp = [];
		for (var i in columns) {
			if (columns.hasOwnProperty(i)) {
				Ext.applyIf( columns[i], {
					header: _('msd_sales_' +  i)
					,dataIndex:  i
				});
				tmp.push(columns[i]);
			}
		}
		return tmp;
	}

	,renderThumb: function(value) {
		if (value) {
			return '<img src="/' + value + '" height="41" style="display:block;margin:auto;"/>';
		}
		else {
			return '';
		}
	}

	,createSale: function(btn,e) {
		var w = Ext.getCmp('msd-window-sale-create');
		if (w) {w.hide().getEl().remove();}

		w = MODx.load({
			xtype: 'msd-window-sale-create'
			,id: 'msd-window-sale-create'
			,listeners: {
				success: {fn:function(response) {
					this.refresh();
					if (response.a.result.object) {
						this.updateSale('','',{data: response.a.result.object}, 1);
					}
				},scope:this}
			}
		});
		w.fp.getForm().reset();
		w.show(e.target);
	}

	,updateSale: function(btn,e,row, tab) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		if (!tab) {tab = 0;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: msDiscount.config.connector_url
			,params: {
				action: 'mgr/sales/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					var w = Ext.getCmp('msd-window-sale-update');
					if (w) {w.hide().getEl().remove();}

					w = MODx.load({
						xtype: 'msd-window-sale-update'
						,id: 'msd-window-sale-update'
						,record: r.object
						,listeners: {
							success: {fn:function() {this.refresh();},scope:this}
							,afterrender: {fn:function() {
								if (tab != 0) {
									Ext.getCmp('msd-window-sales-update-tabs').setActiveTab(tab);
								}
							}}
						}
					});
					w.fp.getForm().reset();
					w.fp.getForm().setValues(r.object);
					w.show(e.target);
				},scope:this}
			}
		});
	}

	,removeSale: function(btn,e) {
		MODx.msg.confirm({
			title: _('msd_menu_remove')
			,text: _('msd_menu_remove_confirm')
			,url: msDiscount.config.connector_url
			,params: {
				action: 'mgr/sales/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				success: {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
});
Ext.reg('msd-grid-sales',msDiscount.grid.Sales);




msDiscount.window.CreateSale = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecsale'+Ext.id();
	Ext.applyIf(config,{
		title: _('msd_sales_create')
		,id: this.ident
		,height: 250
		,width: 600
		,url: msDiscount.config.connector_url
		,action: 'mgr/sales/create'
		,fields: this.getFields(config)
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit()},scope: this}]
	});
	msDiscount.window.CreateSale.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.window.CreateSale,MODx.Window, {

	getFields: function(config) {
		var fields = {
			name: {}
			,discount: {anchor: '50%', value: 0}
			,begins: {xtype: 'minishop2-xdatetime', anchor: '50%'}
			,ends: {xtype: 'minishop2-xdatetime', anchor: '50%'}
			,active: {xtype: 'xcheckbox'}
			,resource: {xtype: 'minishop2-combo-resource', anchor: '75%'}
			,image: {xtype: 'minishop2-combo-browser'}
			,description: {xtype: 'textarea', height: 75}
		};
		var tmp = [];
		for (var i in fields) {
			if (fields.hasOwnProperty(i)) {
				Ext.applyIf( fields[i], {
					xtype: 'textfield'
					,id: 'msd-'+ this.ident + i
					,name: i
					,fieldLabel: _('msd_sales_' +  i)
					,anchor: '99%'
				});
				tmp.push(fields[i]);
			}
		}
		return tmp;
	}

});
Ext.reg('msd-window-sale-create',msDiscount.window.CreateSale);




msDiscount.window.UpdateSale = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecsale'+Ext.id();

	Ext.applyIf(config,{
		title: _('msd_sales_update') + '"' + config.record['name'] + '"'
		,id: this.ident
		,height: 250
		,width: 600
		,url: msDiscount.config.connector_url
		,action: 'mgr/sales/update'
		,fields: [{
			xtype: 'modx-tabs'
			,id: 'msd-window-sales-update-tabs'
			,bodyStyle: 'padding: 5px;'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,hideMode: 'offsets'
			,deferredRender: false
			,activeTab: 0
			,autoHeight: true
			,stateful: true
			,stateId: 'msd-window-sales-update-tabs'
			,stateEvents: ['tabchange']
			,getState:function() {return {activeTab: this.items.indexOf(this.getActiveTab())};}
			,items: [{
				title: _('msd_sales_main')
				,layout: 'form'
				,hideMode: 'offsets'
				,items: this.getMainFields(config)
			},{
				title: _('msd_users')
				,items: {
					xtype: 'msd-grid-sales-group'
					,id: 'msd-grid-sales-group-users'
					,record: config.record
					,type: 'users'
					,sale_id: config.record.id
				}
			},{
				title: _('msd_products')
				,items: {
					xtype: 'msd-grid-sales-group'
					,id: 'msd-grid-sales-group-products'
					,record: config.record
					,type: 'products'
					,sale_id: config.record.id
				}
			}]
		}]
		//,fields: this.getFields(config)
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit()},scope: this}]
	});
	msDiscount.window.UpdateSale.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.window.UpdateSale,MODx.Window, {

	getMainFields: function(config) {
		var fields = {
			id: {xtype: 'hidden'}
			,name: {}
			,discount: {anchor: '50%', value: 0}
			,begins: {xtype: 'minishop2-xdatetime', anchor: '50%'}
			,ends: {xtype: 'minishop2-xdatetime', anchor: '50%'}
			,active: {xtype: 'xcheckbox'}
			,resource: {xtype: 'minishop2-combo-resource', anchor: '75%'}
			,image: {xtype: 'minishop2-combo-browser'}
			,description: {xtype: 'textarea', height: 75}
		};
		var tmp = [];
		for (var i in fields) {
			if (fields.hasOwnProperty(i)) {
				Ext.applyIf( fields[i], {
					xtype: 'textfield'
					,id: 'msd-'+ this.ident + i
					,name: i
					,fieldLabel: _('msd_sales_' +  i)
					,anchor: '99%'
				});
				tmp.push(fields[i]);
			}
		}
		return tmp;
	}

});
Ext.reg('msd-window-sale-update',msDiscount.window.UpdateSale);







msDiscount.grid.SalesMemberGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-sales-group'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/sales/members/getlist'
			,type: config.type
			,sale_id: config.record.id
		}
		,fields: ['sale_id','group_id','group','relation','name','discount']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,pageSize: 5
		,columns: [
			{header: _('msd_group_name'), dataIndex: 'name', width: 100}
			,{header: _('msd_members_relation'), dataIndex: 'relation', width: 50}
			,{header: _('msd_group_discount'), dataIndex: 'discount', width: 50}
		]
		,tbar: [{
			xtype: 'msd-combo-group'
			,id: 'msd-combo-group-' + config.type
			,type: config.type
			,sale_id: config.record.id
			,listeners: {
				select: {fn: this.addGroup, scope: this}
			}
		}]
	});
	msDiscount.grid.SalesMemberGroup.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.grid.SalesMemberGroup,MODx.grid.Grid, {

	getMenu: function(grid,index,event) {
		var record = grid.getStore().getAt(index).data;
		var m = [];
		m.push({
			text: _('msd_menu_remove')
			,handler: this.removeGroup
		});
		this.addContextMenuItem(m);
	}

	,addGroup: function(combo, row) {
		combo.reset();

		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/sales/members/create'
				,sale_id: this.config.record.id
				,group_id: row.id
				,type: this.config.type
			}
			,listeners: {
				success: {fn:function(r) {
					combo.getStore().reload();
					this.refresh();
				},scope:this}
			}
		});
	}

	,removeGroup: function(btn,e) {
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				action: 'mgr/sales/members/remove'
				,sale_id: this.config.sale_id
				,group_id: this.menu.record.group_id
				,type: this.config.type
			}
			,listeners: {
				'success': {fn:function(r) {
					this.refresh();
					Ext.getCmp('msd-combo-group-' + this.config.type).getStore().reload();
				}, scope:this}
			}
		});
	}

});
Ext.reg('msd-grid-sales-group',msDiscount.grid.SalesMemberGroup);