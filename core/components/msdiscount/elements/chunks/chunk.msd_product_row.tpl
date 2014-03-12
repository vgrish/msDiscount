<div class="row ms2_product">
	<div class="span2 col-md-2"><img src="[[+thumb:default=`[[++assets_url]]components/minishop2/img/web/ms2_small.png`]]" width="120" height="90" /></div>
	<div class="row span10 col-md-10">
		<form method="post" class="ms2_form">
			<a href="[[~[[+id]]]]">[[+pagetitle]]</a>
			<span class="flags">[[+new:eq=`0`:then=``:else=`<i class="glyphicon glyphicon-flag" title="[[%ms2_frontend_new]]"></i>`]] [[+popular:eq=`0`:then=``:else=`<i class="glyphicon glyphicon-star" title="[[%ms2_frontend_popular]]"></i>`]] [[+favorite:eq=`0`:then=``:else=`<i class="glyphicon glyphicon-bookmark" title="[[%ms2_frontend_favorite]]"></i>`]]</span>
			<span class="price">[[+price]] [[%ms2_frontend_currency]] (-[[+sale_discount]] — [[+remains]])</span>
			[[+old_price:eq=`0`:then=``:else=`<span class="old_price">[[+old_price]] [[%ms2_frontend_currency]]</span>`]]
			<button class="btn btn-default" type="submit" name="ms2_action" value="cart/add"><i class="glyphicon glyphicon-barcode"></i> [[%ms2_frontend_add_to_cart]]</button>
			<input type="hidden" name="id" value="[[+id]]">
			<input type="hidden" name="count" value="1">
			<input type="hidden" name="options" value="[]">
		</form>
		<p><small>[[+introtext]]</small></p>
	</div>
</div>
<br/><br/>