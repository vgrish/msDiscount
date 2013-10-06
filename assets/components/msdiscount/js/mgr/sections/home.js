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


msDiscount.combo.Relation = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-combo-relation'
		,store: new Ext.data.SimpleStore({
			fields: ['title','relation']
			,data: [[_('msd_members_relation_in'),'in'],[_('msd_members_relation_out'),'out']]
		})
		,valueField: 'relation'
		,displayField: 'title'
		,name: 'relation'
		,hiddenName: 'relation'
		,width: '50%'
		,mode: 'local'
		,triggerAction: 'all'
		,editable: false
		,selectOnFocus: false
		,preventRender: true
		,forceSelection: true
		,enableKeyEvents: true
	});
	msDiscount.combo.Relation.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.combo.Relation,MODx.combo.ComboBox);
Ext.reg('msd-combo-relation',msDiscount.combo.Relation);