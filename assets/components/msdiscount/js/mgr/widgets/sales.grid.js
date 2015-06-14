msDiscount.grid.Sales = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-sales',
		url: msDiscount.config.connector_url,
		baseParams: {
			action: 'mgr/sales/getlist'
		},
		autosave: true,
		save_action: 'mgr/sales/updatefromgrid',
		//preventSaveRefresh: false,
		fields: ['id','discount','name','description','begins','ends','active','image', 'actions'],
		autoHeight: true,
		paging: true,
		remoteSort: true,
		columns: this.getColumns(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		tbar: [{
			text: (MODx.modx23
				? '<i class="icon icon-plus"></i> '
				: '<i class="fa fa-plus"></i> ')
			+ _('msd_btn_sale_create'),
			handler: this.createSale,
			scope: this
		}],
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
				var cls = [];
				if (!rec.data.active) {
					cls.push('msd-row-disabled');
				}
				return cls.join(' ');
			}
		},
		/*
		listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateSale(grid, e, row);
			}
		}*/
	});
	msDiscount.grid.Sales.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.grid.Sales,MODx.grid.Grid,{
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = msDiscount.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	getColumns: function(config) {
		var columns = {
			id: {hidden: true, width: 50},
			name: {width: 100, editor: {xtype: 'textfield'}},
			discount: {width: 100, editor: {xtype: 'textfield'}},
			description: {hidden: true},
			begins: {width: 100, renderer: miniShop2.utils.formatDate},
			ends: {width: 100, renderer: miniShop2.utils.formatDate},
			active: {width: 50, editor: {xtype: 'combo-boolean', renderer:'boolean'}},
			image: {width: 100, renderer: this.renderThumb},
			actions: {width: 75, renderer: msDiscount.utils.renderActions, sortable: false, id: 'actions', header: _('msd_actions')}
		};
		var tmp = [];
		for (var i in columns) {
			if (columns.hasOwnProperty(i)) {
				Ext.applyIf( columns[i], {
					header: _('msd_sales_' +  i),
					dataIndex:  i
				});
				tmp.push(columns[i]);
			}
		}
		return tmp;
	},

	renderThumb: function(value) {
		if (value) {
			return '<img src="/' + value + '" height="41" style="display:block;margin:auto;"/>';
		}
		else {
			return '';
		}
	},

	createSale: function(btn,e) {
		var w = Ext.getCmp('msd-window-sale-create');
		if (w) {w.close();}

		w = MODx.load({
			xtype: 'msd-window-sale-create',
			id: 'msd-window-sale-create',
			listeners: {
				success: {fn:function(response) {
					this.refresh();
					if (response.a.result.object) {
						this.updateSale('','',{data: response.a.result.object}, 1);
					}
				},scope:this}
			}
		});
		//w.fp.getForm().reset();
		w.show(e.target);
	},

	updateSale: function(btn,e,row, tab) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		if (!tab) {tab = 0;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: msDiscount.config.connector_url,
			params: {
				action: 'mgr/sales/get',
				id: id
			},
			listeners: {
				success: {fn:function(r) {
					var w = Ext.getCmp('msd-window-sale-update');
					if (w) {w.close();}
					w = MODx.load({
						xtype: 'msd-window-sale-update',
						id: 'msd-window-sale-update',
						record: r.object,
						listeners: {
							success: {fn:function() {this.refresh();},scope:this},
							afterrender: {fn:function() {
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
	},

	saleAction: function(method) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: msDiscount.config.connector_url,
			params: {
				action: 'mgr/sales/multiple',
				method: method,
				ids: Ext.util.JSON.encode(ids),
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				},
				failure: {
					fn: function (response) {
						MODx.msg.alert(_('error'), response.message);
					}, scope: this
				},
			}
		})
	},

	enableSale: function(btn,e) {
		this.saleAction('enable');
	},

	disableSale: function(btn,e) {
		this.saleAction('disable');
	},

	removeSale: function(btn,e) {
		Ext.MessageBox.confirm(
			_('msd_action_remove'),
			_('msd_action_remove_confirm'),
			function(val) {
				if (val == 'yes') {
					this.saleAction('remove');
				}
			},
			this
		);
	},

	_getSelectedIds: function() {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

});
Ext.reg('msd-grid-sales',msDiscount.grid.Sales);


msDiscount.window.CreateSale = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecsale'+Ext.id();
	Ext.applyIf(config,{
		title: _('msd_sales_create'),
		id: this.ident,
		autoHeight: true,
		width: 600,
		url: msDiscount.config.connector_url,
		action: 'mgr/sales/create',
		fields: this.getFields(config),
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit()},scope: this}]
	});
	msDiscount.window.CreateSale.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.window.CreateSale,MODx.Window, {

	getFields: function(config) {
		return [
			{xtype: 'hidden', name: 'id'},
			{xtype: 'textfield', name: 'name', fieldLabel: _('msd_sales_name'), anchor: '100%'},
			{
				layout: 'column',
				border: false,
				anchor: '100%',
				style: {margin: '10px 0 0 0'},
				items: [{
					columnWidth: .5,
					layout: 'form',
					items: [
						{xtype: 'minishop2-xdatetime', name: 'begins', fieldLabel: _('msd_sales_begins'), anchor: '99%'},
						{xtype: 'textfield', name: 'discount', fieldLabel: _('msd_sales_discount'), anchor: '50%', value: 0},
						{xtype: 'minishop2-combo-resource', name: 'resource', fieldLabel: _('msd_sales_resource'), anchor: '99%'}
					]
				},{
					columnWidth: .5,
					layout: 'form',
					style: {margin: 0},
					items: [
						{xtype: 'minishop2-xdatetime', name: 'ends', fieldLabel: _('msd_sales_ends'), anchor: '99%'},
						{xtype: 'combo-boolean', name: 'active', fieldLabel: _('msd_sales_active'), anchor: '50%', hiddenName: 'active', value: false},
						{xtype: 'minishop2-combo-browser', name: 'image', fieldLabel: _('msd_sales_image'), anchor: '100%'}
					]
				}]
			},
			{xtype: 'textarea', name: 'description', fieldLabel: _('msd_sales_description'), anchor: '100%', height: 75}
		];
	}
});
Ext.reg('msd-window-sale-create',msDiscount.window.CreateSale);


msDiscount.window.UpdateSale = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecsale'+Ext.id();

	Ext.applyIf(config,{
		title: _('msd_sales_update') + '"' + config.record['name'] + '"',
		id: this.ident,
		autoHeight: true,
		width: 650,
		url: msDiscount.config.connector_url,
		action: 'mgr/sales/update',
		fields: [{
			xtype: 'modx-tabs',
			id: 'msd-window-sales-update-tabs',
			bodyStyle: MODx.modx23 ? '' : 'padding: 5px;',
			defaults: { border: false ,autoHeight: true },
			border: true,
			hideMode: 'offsets',
			deferredRender: false,
			activeTab: 0,
			autoHeight: true,
			stateful: true,
			stateId: 'msd-window-sales-update-tabs',
			stateEvents: ['tabchange'],
			getState:function() {return {activeTab: this.items.indexOf(this.getActiveTab())};},
			items: [{
				title: _('msd_sales_main'),
				layout: 'form',
				hideMode: 'offsets',
				items: this.getMainFields(config)
			},{
				title: _('msd_users'),
				items: {
					xtype: 'msd-grid-sales-group',
					id: 'msd-grid-sales-group-users',
					record: config.record,
					type: 'users',
					sale_id: config.record.id
				}
			},{
				title: _('msd_products'),
				items: {
					xtype: 'msd-grid-sales-group',
					id: 'msd-grid-sales-group-products',
					record: config.record,
					type: 'products',
					sale_id: config.record.id
				}
			}]
		}],
		//fields: this.getFields(config),
		keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit()},scope: this}]
	});
	msDiscount.window.UpdateSale.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.window.UpdateSale,MODx.Window, {

	getMainFields: function(config) {
		return [
			{xtype: 'hidden', name: 'id'},
			{xtype: 'textfield', name: 'name', fieldLabel: _('msd_sales_name'), anchor: '100%'},
			{
				layout: 'column',
				border: true,
				anchor: '100%',
				style: {margin: '10px 0 0 0'},
				items: [{
					columnWidth: .5,
					layout: 'form',
					items: [
						{xtype: 'minishop2-xdatetime', name: 'begins', fieldLabel: _('msd_sales_begins'), anchor: '99%'},
						{xtype: 'textfield', name: 'discount', fieldLabel: _('msd_sales_discount'), anchor: '50%', value: 0},
						{xtype: 'minishop2-combo-resource', name: 'resource', fieldLabel: _('msd_sales_resource'), anchor: '99%'}
					]
				},{
					columnWidth: .5,
					layout: 'form',
					style: {margin: 0},
					items: [
						{xtype: 'minishop2-xdatetime', name: 'ends', fieldLabel: _('msd_sales_ends'), anchor: '99%'},
						{xtype: 'combo-boolean', name: 'active', fieldLabel: _('msd_sales_active'), anchor: '50%', hiddenName: 'active'},
						{xtype: 'minishop2-combo-browser', name: 'image', fieldLabel: _('msd_sales_image'), anchor: '100%'}
					]
				}]
			},
			{xtype: 'textarea', name: 'description', fieldLabel: _('msd_sales_description'), anchor: '100%', height: 75}
		];
	}

});
Ext.reg('msd-window-sale-update',msDiscount.window.UpdateSale);


msDiscount.grid.SalesMemberGroup = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		id: 'msd-grid-sales-group',
		url: msDiscount.config.connector_url,
		baseParams: {
			action: 'mgr/sales/members/getlist',
			type: config.type,
			sale_id: config.record.id
		},
		autosave: true,
		save_action: 'mgr/sales/members/updatefromgrid',
		fields: ['sale_id', 'group_id', 'type', 'relation', 'name', 'discount', 'actions'],
		autoHeight: true,
		paging: true,
		remoteSort: true,
		pageSize: 5,
		columns: [{
			header: _('msd_group_name'),
			dataIndex: 'name',
			width: 100
		}, {
			header: _('msd_members_relation'),
			dataIndex: 'relation',
			width: 50,
			renderer: this.renderRelation,
			editor: {xtype: 'msd-combo-relation'}
		}, {
			header: _('msd_group_discount'),
			dataIndex: 'discount',
			width: 50
		}, {
			header: _('msd_actions'),
			dataIndex: 'actions',
			width: 35,
			renderer: msDiscount.utils.renderActions,
			sortable: false,
			id: 'actions',
		}],
		tbar: [{
			xtype: 'msd-combo-group',
			id: 'msd-combo-group-' + config.type,
			type: config.type,
			sale_id: config.record.id,
			listeners: {
				select: {fn: this.addGroup, scope: this}
			}
		}]
	});
	msDiscount.grid.SalesMemberGroup.superclass.constructor.call(this, config);
};
Ext.extend(msDiscount.grid.SalesMemberGroup, MODx.grid.Grid, {

	getMenu: function (grid, rowIndex) {
		var row = grid.getStore().getAt(rowIndex);
		var menu = msDiscount.utils.getMenu(row.data['actions'], this);

		this.addContextMenuItem(menu);
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	renderRelation: function(value) {
		if (value == 'in') {
			return '<span style="color:green;">' + _('msd_members_relation_in') + '</span>';
		}
		else if (value == 'out') {
			return '<span style="color:red;">' + _('msd_members_relation_out') + '</span>';
		}
		else {
			return value;
		}
	},

	addGroup: function(combo, row) {
		combo.reset();

		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/sales/members/create',
				sale_id: this.config.record.id,
				group_id: row.id,
				type: this.config.type
			},
			listeners: {
				success: {
					fn: function(r) {
						combo.getStore().reload();
						this.refresh();
					}, scope: this
				}
			}
		});
	},

	removeGroup: function(btn, e) {
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/sales/members/remove',
				sale_id: this.config.sale_id,
				group_id: this.menu.record.group_id,
				type: this.config.type
			},
			listeners: {
				success: {
					fn: function(r) {
						this.refresh();
						Ext.getCmp('msd-combo-group-' + this.config.type).getStore().reload();
					}, scope: this
				}
			}
		});
	},

});
Ext.reg('msd-grid-sales-group', msDiscount.grid.SalesMemberGroup);