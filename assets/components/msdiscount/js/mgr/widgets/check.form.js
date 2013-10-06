msDiscount.panel.CheckDiscount = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'msd-form-check'
		,cls: 'container form-with-labels'
		,labelAlign: 'left'
		,autoHeight: true
		,labelWidth: 100
		,url: msDiscount.config.connector_url
		,baseParams: {
			action: 'mgr/check/check'
		}
		,items: [{
			layout: 'form'
			,cls: 'main-wrapper'
			,border: false
			,items: this.getFields(config)
		}]
		,buttonAlign: 'left'
		,buttons: [
			{text: _('msd_submit'),handler: function() {
				this.submit(this);
			},scope: this}
		]
		,listeners: {
			success: {fn: function(response) {
				Ext.getCmp('msd-check-form-log').setValue(response.result.object.log);
			},scope: this}
		}
	});
	msDiscount.panel.CheckDiscount.superclass.constructor.call(this,config);
};

Ext.extend(msDiscount.panel.CheckDiscount,MODx.FormPanel,{
	filters: {}

	,getFields: function() {
		var fields = [];
		var tmp = {
			product: {xtype: 'minishop2-combo-product', anchor: '40%', allowBlank: false}
			,user: {xtype: 'minishop2-combo-user', anchor: '40%', allowBlank: true}
			,date: {xtype: 'minishop2-xdatetime', anchor: '40%', allowBlank: true}
			,log: {
				anchor: '90%'
				//,height: 150
				,style: 'border:1px solid #efefef; min-height: 50px; border-radius:2px; font:normal 11px Arial; padding: 10px;'
			}
		};

		for (var i in tmp) {
			if (!tmp.hasOwnProperty(i)) {continue;}
			var field = tmp[i];
			Ext.applyIf(field, {
				name: i
				,xtype: 'displayfield'
				,fieldLabel: _('msd_check_' + i)
				,emptyText: _('msd_check_select')
				,id: 'msd-check-form-' + i
			});
			fields.push(field);
		}

		return fields;
	}

});
Ext.reg('msd-form-check',msDiscount.panel.CheckDiscount);