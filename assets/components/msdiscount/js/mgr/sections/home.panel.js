msDiscount.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'msd-panel-home',
			renderTo: 'msd-panel-home-div'
		}]
	});
	msDiscount.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(msDiscount.page.Home, MODx.Component);
Ext.reg('msd-page-home', msDiscount.page.Home);

msDiscount.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false,
		deferredRender: true,
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		items: [{
			html: '<h2>' + _('msdiscount') + '</h2>',
			border: false,
			cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs',
			//bodyStyle: 'padding: 5px',
			defaults: { border: false ,autoHeight: true },
			border: true,
			hideMode: 'offsets',
			stateful: true,
			stateId: 'msd-panel-home',
			stateEvents: ['tabchange'],
			getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};},
			cls: MODx.modx23 ? 'modx23' : 'modx22',
			items: [{
				title: _('msd_sales'),
				deferredRender: true,
				items: [{
					html: '<p>' + _('msd_sales_desc') + '</p>',
					border: false,
					bodyCssClass: 'panel-desc',
				},{
					xtype: 'msd-grid-sales',
					cls: 'main-wrapper',
				}]
			},{
				title: _('msd_users'),
				deferredRender: true,
				items: [{
					html: '<p>' + _('msd_users_desc') + '</p>',
					border: false,
					bodyCssClass: 'panel-desc',
				},{
					xtype: 'msd-grid-users',
					cls: 'main-wrapper',
				}]
			},{
				title: _('msd_products'),
				deferredRender: true,
				items: [{
					html: '<p>' + _('msd_products_desc') + '</p>',
					border: false,
					bodyCssClass: 'panel-desc',
				},{
					xtype: 'msd-grid-products',
					cls: 'main-wrapper',
				}]
			},{
				title: _('msd_check'),
				deferredRender: true,
				items: [{
					html: '<p>' + _('msd_check_desc') + '</p>',
					border: false,
					bodyCssClass: 'panel-desc',
				},{
					xtype: 'msd-form-check',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	msDiscount.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.panel.Home,MODx.Panel);
Ext.reg('msd-panel-home',msDiscount.panel.Home);