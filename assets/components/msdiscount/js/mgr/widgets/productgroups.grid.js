msDiscount.grid.Products = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-pProducts'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/productgroups/getlist'
		}
		,autosave: true
		,save_action: 'mgr/productgroups/updatefromgrid'
		//,preventSaveRefresh: false
		,fields: ['id','name','discount']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: this.getColumns(config)
		,tbar: [{
			text: _('msd_group_create')
			,handler: this.createResourceGroup
			,scope: this
		}]
	});
	msDiscount.grid.Products.superclass.constructor.call(this,config);
};

Ext.extend(msDiscount.grid.Products,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('msd_menu_update')
			,handler: this.updateResourceGroup
		});
		this.addContextMenuItem(m);
	}

	,getColumns: function(config) {
		var columns = {
			id: {width: 50}
			,name: {width: 200, editor: {xtype: 'textfield'}}
			,discount: {editor: {xtype: 'textfield'}}
		};
		var tmp = [];
		for (var i in columns) {
			if (columns.hasOwnProperty(i)) {
				Ext.applyIf( columns[i], {
					header: _('msd_group_' +  i)
					,dataIndex:  i
				});
				tmp.push(columns[i]);
			}
		}
		return tmp;
	}

	,createResourceGroup: function(btn,e) {
		var createPage = MODx.modx23
			? 'security/resourcegroup'
			: MODx.action['security/resourcegroup/index'];

		window.open(MODx.config.manager_url + '?a=' + createPage);
	}

	,updateResourceGroup: function(btn,e,row) {
		var updatePage = MODx.modx23
			? 'security/resourcegroup'
			: MODx.action['security/resourcegroup/index'];

		window.open(MODx.config.manager_url + '?a=' + updatePage);
	}
});
Ext.reg('msd-grid-products',msDiscount.grid.Products);