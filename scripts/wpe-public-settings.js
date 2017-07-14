jQuery(document).ready(function() {
	var baseURL="//"+window.location.hostname;
	var currentURL=window.location;
	
	jQuery('.wpe_twitter_interact a').click(function(e) {
		var width  = 575,
		height = 400,
		left   = (jQuery(window).width()  - width)  / 2,
		top    = (jQuery(window).height() - height) / 2,
		url    = this.href,
		opts   = 'status=1' +
		',width='  + width  +
		',height=' + height +
		',top='    + top    +
		',left='   + left;
		window.open(url, 'twitter', opts);
		e.preventDefault();
	});

	if (typeof trackOutboundLink != 'undefined') {
		jQuery('a').each(function() {
			var a = new RegExp('/' + window.location.host + '/');
			if(!a.test(this.href)) {
				jQuery(this).attr("onclick","trackOutboundLink('"+this.href+"');return false;");
			}
		});
	}
});

(function(){function addIcon(el,entity){var html=el.innerHTML;el.innerHTML='<span style="font-family:\'wpe_twitter\'">'+entity+'</span>'+html;}var icons={'wpe-redo':'&#xe600;','wpe-loop':'&#xe601;','wpe-star':'&#xe602;','0':0},els=document.getElementsByTagName('*'),i,c,el;for(i=0;;i+=1){el=els[i];if(!el){break;}c=el.className;c=c.match(/wpe-[^\s'"]+/);if(c&&icons[c[0]]) {addIcon(el,icons[c[0]]);}}}());