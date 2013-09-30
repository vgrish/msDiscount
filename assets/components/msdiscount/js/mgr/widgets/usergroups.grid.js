msDiscount.grid.Users = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-grid-users'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/usergroups/getlist'
		}
		,fields: ['id','name','discount','joinsum']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: this.getColumns(config)
		,tbar: [{
			text: _('msd_group_create')
			,handler: this.createUserGroup
			,scope: this
		}]
	});
	msDiscount.grid.Users.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.grid.Users,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('msd_menu_update')
			,handler: this.updateUserGroup
		});
		this.addContextMenuItem(m);
	}

	,getColumns: function(config) {
		var columns = {
			id: {width: 50}
			,name: {width: 200, editor: {xtype: 'textfield'}}
			,discount: {editor: {xtype: 'textfield'}}
			,joinsum: {editor: {xtype: 'numberfield', allowNegative: false, allowDecimals: true}}
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

	,createUserGroup: function(btn,e) {
		var createPage = MODx.action
			? MODx.action['security/permission']
			: 'security/permission';

		window.open(MODx.config.manager_url + '?a=' + createPage);
	}
	,updateUserGroup: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;
		var updatePage = MODx.action
			? MODx.action['security/usergroup/update']
			: 'security/usergroup/update';

		window.open(MODx.config.manager_url + '?a=' + updatePage + '&id=' + id);
	}

});
Ext.reg('msd-grid-users',msDiscount.grid.Users);