msDiscount.grid.Sales = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-sales'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/sales/getlist'
		}
		,fields: ['id','discount','name','description','begins','ends','active','image']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,save_action: 'mgr/sales/updatefromgrid'
		,autosave: true
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
			,discount: {width: 100, editor: {xtype: 'textfield'}}
			,name: {width: 100, editor: {xtype: 'textfield'}}
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
				'success': {fn:function() { this.refresh(); },scope:this}
			}
		});
		w.fp.getForm().reset();
		w.show(e.target);
	}

	,updateSale: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
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
						,record: r
						,listeners: {
							'success': {fn:function() { this.refresh(); },scope:this}
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
				'success': {fn:function(r) { this.refresh(); },scope:this}
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
		title: _('msd_sales_update') + '"' + config.record.object['name'] + '"'
		,id: this.ident
		,height: 250
		,width: 600
		,url: msDiscount.config.connector_url
		,action: 'mgr/sales/update'
		,fields: this.getFields(config)
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit()},scope: this}]
	});
	msDiscount.window.UpdateSale.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.window.UpdateSale,MODx.Window, {

	getFields: function(config) {
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