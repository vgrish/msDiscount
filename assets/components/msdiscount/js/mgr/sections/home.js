msDiscount.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'msd-panel-home'
			,renderTo: 'msd-panel-home-div'
		}]
	}); 
	msDiscount.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.page.Home,MODx.Component);
Ext.reg('msd-page-home',msDiscount.page.Home);



/** Combos */

msDiscount.combo.Group = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-combo-group'
		,fieldLabel: _('ms2_sales_group')
		,fields: ['id','name','discount']
		,valueField: 'id'
		,displayField: 'name'
		,name: 'group'
		,hiddenName: 'group'
		,allowBlank: true
		,width: '50%'
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/sales/members/getcombo'
			,sale_id: config.sale_id
			,type: config.type
		}
		,tpl: new Ext.XTemplate(''
			+'<tpl for="."><div class="minishop2-product-list-item">'
			//+'<span class="parents">'
			//+'<tpl for="parents">'
			//+'<nobr><small>{pagetitle} / </small></nobr>'
			//+'</tpl>'
			//+'</span>'
			//+'</tpl>'
			+'<span><small>({id})</small> <b>{name}</b> {discount}</span>'
			+'</div></tpl>',{
			compiled: true
		})
		,pageSize: 10
		,emptyText: _('msd_members_select')
		//,typeAhead: true
		,editable: true
	});
	msDiscount.combo.Group.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.combo.Group,miniShop2.combo.Product);
Ext.reg('msd-combo-group',msDiscount.combo.Group);