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
