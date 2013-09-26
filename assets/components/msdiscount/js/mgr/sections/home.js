Ext.onReady(function() {
	MODx.load({ xtype: 'msdiscount-page-home'});
});

msDiscount.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'msdiscount-panel-home'
			,renderTo: 'msdiscount-panel-home-div'
		}]
	}); 
	msDiscount.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(msDiscount.page.Home,MODx.Component);
Ext.reg('msdiscount-page-home',msDiscount.page.Home);
