msDiscount.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'msd-panel-settings'
			,renderTo: 'msd-panel-settings-div'
		}]
	});
	msDiscount.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.page.Home,MODx.Component);
Ext.reg('msd-page-home',msDiscount.page.Home);

msDiscount.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,deferredRender: true
		,baseCls: 'modx-formpanel'
		,items: [{
			html: '<h2>'+_('msdiscount') +'</h2>'
			,border: false
			,cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs'
			,bodyStyle: 'padding: 5px'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,hideMode: 'offsets'
			,stateful: true
			,stateId: 'msd-panel-home'
			,stateEvents: ['tabchange']
			,getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};}
			,items: [{
				title: _('msd_sales')
				,deferredRender: true
				,items: [{
					html: '<p>'+_('msd_sales_desc')+'</p>'
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'msd-grid-sales'
				}]
			},{
				title: _('msd_users')
				,deferredRender: true
				,items: [{
					html: '<p>'+_('msd_users_desc')+'</p>'
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'msd-grid-users'
				}]
			},{
				title: _('msd_products')
				,deferredRender: true
				,items: [{
					html: '<p>'+_('msd_products_desc')+'</p>'
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'msd-grid-products'
				}]
			}]
		}]
	});
	msDiscount.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.panel.Home,MODx.Panel);
Ext.reg('msd-panel-home',msDiscount.panel.Home);