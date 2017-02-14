jQuery(function() {
	var _scroll = {
		delay: 1000,
		easing: 'linear',
		items: 1,
		duration: 0.07,
		timeoutDuration: 0,
	};
	jQuery('#ticker').carouFredSel({
		width: 1000,
		align: false,
		items: {
			width: 'variable',
			height: 35,
			visible: 1
		},
		scroll: _scroll
	});
});
