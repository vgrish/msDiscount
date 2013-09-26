msDiscount.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,baseCls: 'modx-formpanel'
		,items: [{
			html: '<h2>'+_('msdiscount')+'</h2>'
			,border: false
			,cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs'
			,bodyStyle: 'padding: 10px'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,activeItem: 0
			,hideMode: 'offsets'
			,items: [{
				title: _('msdiscount_items')
				,items: [{
					html: _('msdiscount_intro_msg')
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'msdiscount-grid-items'
					,preventRender: true
				}]
			}]
		}]
	});
	msDiscount.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.panel.Home,MODx.Panel);
Ext.reg('msdiscount-panel-home',msDiscount.panel.Home);
