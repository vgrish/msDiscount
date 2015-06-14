var msDiscount = function(config) {
	config = config || {};
	msDiscount.superclass.constructor.call(this, config);
};
Ext.extend(msDiscount, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {},
});
Ext.reg('msdiscount', msDiscount);

msDiscount = new msDiscount();
