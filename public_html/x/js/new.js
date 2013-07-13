var plot_id, osMap, map, layer, control, postcodeService, boundingBox;

function initHome(postcode, easting, northing)
{
	writeCookie('postcode', postcode);
	$('#map_key').hide();
	postcodeService = new OpenSpace.Postcode();
	$('#pcodesubmit').click(function(e) {
		e.preventDefault();
		var pcode = $('#pcode').attr('value');
		writeCookie('postcode', pcode);
		postcodeService.getLonLat(pcode, showPostcodeResult);
	});
	if (typeof(postcode) == "string" && postcode != '')
	{
		postcodeService.getLonLat(postcode, showPostcodeResult);
	}
	else if (easting && northing)
	{
		showPostcodeResult(new OpenSpace.MapPoint(easting,northing, 2));
	}
}

function showPostcodeResult(mapPoint)
{
	if ($('#mapdiv').hasClass('homemap')) 
	{
		$('#mapdiv').removeClass('homemap');
		$('#site_mission').hide();
		$('#photo_caption').hide();
		$('#aggregator').hide();
		$('#map_key').show();
	}

	var styleDefault = {strokeColor:"#ff0000", strokeOpacity:"1.0", strokeWidth:2, fillColor:"#990000", cursor:"pointer"};
	var styleMap = OpenLayers.Util.applyDefaults(styleDefault, OpenLayers.Feature.Vector.style["default"]);

	var plotStyle = new OpenLayers.StyleMap({ 'default': styleMap, 'select':{ strokeColor:"#990000", fillColor:"#660000"} });
	
	plotStyle.styles['default'].addRules([
			new OpenLayers.Rule({
					filter: new OpenLayers.Filter.Comparison({
							type: OpenLayers.Filter.Comparison.EQUAL_TO , property: "owner", value: "private"
					}),
					symbolizer: { strokeColor:"#ff3322", strokeOpacity:"1.0", strokeWidth:3, fillColor:"#00ff00" }
			}),
			new OpenLayers.Rule({
					elseFilter: true
			})					
	]);

	if (map == undefined) 
	{
		var mapOptions = { resolutions: [10, 5, 4, 2.5, 2, 1], restrictedExtent: new OpenSpace.MapBounds(394000, 397000, 434000, 435000) };
		map = new OpenSpace.Map('mapdiv', mapOptions);
		boundingBox = new OpenLayers.Strategy.BBOX();
	}
	var plots = new OpenLayers.Layer.Vector("Plots", {
			protocol: new OpenLayers.Protocol.HTTP({
			url: "/ajax/bboxpoints",
			format: new OpenLayers.Format.GeoJSON()
		}),   
		strategies: [boundingBox],
		styleMap: plotStyle,
		maxResolution: map.resolutions[5]
	});
	map.addLayer(plots);

	control = new OpenLayers.Control.SelectFeature(plots,{
		clickout: true,
		toggle: false,
		multiple: false,
		hover: false,
		onSelect: openPopup, 
		onUnselect: closePopup,
		id:"selectedPopupControl" 
	});
	map.addControl(control);
	control.activate();
	map.setCenter(mapPoint, 5);
	storeLocation();
	map.events.register('moveend', map, storeLocation);
}

function storeLocation()
{
	if ($('#js_easting').length > 0 && $('#js_northing').length > 0)
	{
		var _map_centre = map.getCenter();
		$('#js_easting').val(_map_centre.lon);
		$('#js_northing').val(_map_centre.lat);
		if ($('#js_place_easting').length > 0 && $('#js_place_northing').length > 0)
		{
			$('#js_place_easting').val(_map_centre.lon);
			$('#js_place_northing').val(_map_centre.lat);
		}
	}
}

function openPopup(feature)
{
	control.deactivate();
	boundingBox.deactivate();
	for (var i = 0; i < map.popups.length; i++) {
		map.removePopup = map.popups[i];
	}
	var popupContent = "<div class=\"popup\"><p>";
	if (feature.attributes.descr != '')
	{
		popupContent += feature.attributes.descr;
		popupContent += "<br /><a href=\"/plot/" + feature.attributes.plot_id + "\">View plot details</a>";
	}
	else
	{
		popupContent += "<a href=\"/plot/" + feature.attributes.plot_id + "\">View plot details</a>";
		popupContent += "<br />This plot is part of the Ramsden Estate, which was bought by the former Huddersfield Corporation in 1920, when Huddersfield became known as \"The town that bought itself\". Please go to the plot details page to add any local knowledge you have.";
	}
	popupContent += "</p></div>";

	popup = new OpenLayers.Popup.FramedCloud(
		"featurePopup",
		feature.geometry.getBounds().getCenterLonLat(),
		new OpenLayers.Size(100,100),
		popupContent,
		null, 
		true, 
		onPopupClose
	);
	feature.popup = popup;
	popup.feature = feature;
	map.addPopup(popup);  	  
  	  
}

function closePopup(feature)
{
	if (feature.popup) {
	  popup.feature = null;
	  map.removePopup(feature.popup);
	  feature.popup.destroy();
	  feature.popup = null;
	}
	boundingBox.activate();
	boundingBox.update();
	control.activate();
}

function onPopupClose(evt) {
	map.getControl("selectedPopupControl").unselect(this.feature);
}



function isNumber(n) {
	return ! isNaN(parseFloat(n)) && isFinite(n);
}

function flickrImages()
{
	if ($('#flickr_images').length > 0)
	{
		$.ajax({
			type: 'GET',
			url: '/ajax/flickr',
			success: function(content) {
				$('#flickr_images').html(content);
			}
		});
	}
}

function initPlaceEdit()
{
	if ($('#js_easting').length > 0 && $('#js_northing').length > 0 && $('#mapdiv').length > 0)
	{
		var opts = { resolutions: [10, 5, 4, 2.5, 2, 1], restrictedExtent: new OpenSpace.MapBounds(394000, 397000, 434000, 435000) };
		map = new OpenSpace.Map('mapdiv', opts);
		map.setCenter(new OpenSpace.MapPoint($('#js_easting').val(),$('#js_northing').val()), 5);
		
		var styleDefault = {strokeColor:"#ff3322", strokeOpacity:"1.0", strokeWidth:3, fillColor:"#00ff00", cursor:"pointer"};
		var styleMap = OpenLayers.Util.applyDefaults(styleDefault, OpenLayers.Feature.Vector.style["default"]);
		var plotStyle = new OpenLayers.StyleMap({ 'default': styleMap });

		layer = new OpenLayers.Layer.Vector("layer", { styleMap: plotStyle }); 
		map.addLayers([layer]); 

		var ftr = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point($('#js_easting').val(),$('#js_northing').val()));
		layer.addFeatures([ftr]); 	  
		drag = new OpenLayers.Control.DragFeature(layer); 
		map.addControl(drag); 
		drag.activate(); 
		drag.onComplete = function(f, m) {
			var lonlat = map.getLonLatFromViewPortPx(m);
   			$('#js_easting').val(lonlat.lon);
   			$('#js_northing').val(lonlat.lat);
		}; 		
	}
}

function initPlace()
{
	plot_id = $('#js_plot_id').attr('value');
	showPlace();
	plotDetails();
}

function showPlace()
{
	if ($('#js_easting').length > 0 && $('#js_northing').length > 0 && $('#map').length > 0)
	{
		var opts = { resolutions: [2.5, 2, 1], restrictedExtent: new OpenSpace.MapBounds(394000, 397000, 434000, 435000) };
		map = new OpenSpace.Map('map', opts);
		map.setCenter(new OpenSpace.MapPoint($('#js_easting').val(),$('#js_northing').val()), 2);
		
		var styleDefault = {strokeColor:"#ff3322", strokeOpacity:"1.0", strokeWidth:3, fillColor:"#00ff00"};
		var styleMap = OpenLayers.Util.applyDefaults(styleDefault, OpenLayers.Feature.Vector.style["default"]);
		var plotStyle = new OpenLayers.StyleMap({ 'default': styleMap });

		layer = new OpenLayers.Layer.Vector("layer", { styleMap: plotStyle }); 
		map.addLayers([layer]); 

		var ftr = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point($('#js_easting').val(),$('#js_northing').val()));
		layer.addFeatures([ftr]); 
	}

}

function showPlot()
{
	if ($('#map').length > 0)
	{
		var opts = { resolutions: [2.5, 2, 1], restrictedExtent: new OpenSpace.MapBounds(394000, 397000, 434000, 435000) };
		osMap = new OpenSpace.Map('map', opts);
		osMap.setCenter(new OpenSpace.MapPoint($('#js_easting').val(),$('#js_northing').val()), 2);
		vectorLayer = new OpenLayers.Layer.Vector("Vector Layer");
		osMap.addLayer(vectorLayer);
		$.getJSON("/ajax/plot/" + plot_id,
		function(data) {
			placeLoci(data);
		});
	}
}
 
function placeLoci(polygons)
{
	var gridProjection = new OpenSpace.GridProjection();
	var plot_style =
	{
		strokeColor: "#ff0000",
		strokeOpacity: 1,
		strokeWidth: 2,
		fillColor: "#990000",
		fillOpacity: 0.6
	};
	$.each(polygons, function(i, polygon){
		var points = [];
		$.each(polygon.point, function(j, point){
			var x = parseFloat(point[0]);
			var y = parseFloat(point[1]);
			points.push(new OpenLayers.Geometry.Point(x, y));
		});
		var linearRing = new OpenLayers.Geometry.LinearRing(points);
		var polygonFeature = new OpenLayers.Feature.Vector(linearRing, null, plot_style);
		vectorLayer.addFeatures([polygonFeature]);
	});
}

function initPlot()
{
	plot_id = $('#js_plot_id').attr('value');
	//showPlot();
	//flickrImages();
	plotDetails();
}

function plotDetails()
{
	if ($('#plot_details').length > 0 && isNumber(plot_id))
	{
		if ($('#js_accordion').length > 0) 
		{
			$('#js_accordion').accordion({ autoHeight: false, navigation: true });
			handleLocalNames();
			handleLocalHistory();
			handlePlotNews();
			handleLocalOrgs();
		}
		$('#js_info').click(function(e) {
			e.preventDefault();
			if ($('#js_info').hasClass('off'))
			{
				$('#js_info').removeClass('off');
				$('#js_info').addClass('on');
				if ($('#js_contacts').hasClass('on'))
				{
					$('#js_contacts').removeClass('on');
					$('#js_contacts').addClass('off');
				}
				else if ($('#js_contribute').hasClass('on'))
				{
					$('#js_contribute').removeClass('on');
					$('#js_contribute').addClass('off');
				}
				else if ($('#js_updates').hasClass('on'))
				{
					$('#js_updates').removeClass('on');
					$('#js_updates').addClass('off');
				}
				$.get('/ajax/info/' + plot_id,
					function(data){
						$('#plot_details').html(data.details);
						$('#plot_addendum').html(data.addendum);
				}, "json");
				/*
				$.ajax({
					type: 'GET',
					url: '/ajax/info/' + plot_id,
					success: function(content) {
						$('#plot_details').html(content);
					}
				});
				*/
			}
		});
		$('#js_contacts').click(function(e) {
			e.preventDefault();
			if ($('#js_contacts').hasClass('off'))
			{
				$('#js_contacts').removeClass('off');
				$('#js_contacts').addClass('on');
				if ($('#js_info').hasClass('on'))
				{
					$('#js_info').removeClass('on');
					$('#js_info').addClass('off');
				}
				else if ($('#js_contribute').hasClass('on'))
				{
					$('#js_contribute').removeClass('on');
					$('#js_contribute').addClass('off');
				}
				else if ($('#js_updates').hasClass('on'))
				{
					$('#js_updates').removeClass('on');
					$('#js_updates').addClass('off');
				}
				$.get('/ajax/contacts/' + plot_id,
					function(data){
						$('#plot_details').html(data.details);
						$('#plot_addendum').html(data.addendum);
						handleCommunityContact();
						handleContactMessage();
				}, "json");
/*
				$.ajax({
					type: 'GET',
					url: '/ajax/contacts/' + plot_id,
					success: function(content) {
						$('#plot_details').html(content);
						handleCommunityContact();
						handleContactMessage();
					}
				});
				*/
			}
		});
		$('#js_contribute').click(function(e) {
			e.preventDefault();
			if ($('#js_contribute').hasClass('off'))
			{
				$('#js_contribute').removeClass('off');
				$('#js_contribute').addClass('on');
				if ($('#js_info').hasClass('on'))
				{
					$('#js_info').removeClass('on');
					$('#js_info').addClass('off');
				}
				else if ($('#js_contacts').hasClass('on'))
				{
					$('#js_contacts').removeClass('on');
					$('#js_contacts').addClass('off');
				}
				else if ($('#js_updates').hasClass('on'))
				{
					$('#js_updates').removeClass('on');
					$('#js_updates').addClass('off');
				}
				$.get('/ajax/contribute/' + plot_id,
					function(data){
						$('#plot_details').html(data.details);
						$('#plot_addendum').html(data.addendum);
						$('#js_accordion').accordion({ autoHeight: false, navigation: true });
						handleLocalNames();
						handleLocalHistory();
						handlePlotNews();
						handleLocalOrgs();
				}, "json");
				
				/*
				$.get('/ajax/update/' + plot_id,
					function(data){
						$('#plot_details').html(data.details);
						$('#plot_addendum').html(data.addendum);
				}, "json");
				
				$.ajax({
					type: 'GET',
					url: '/ajax/contribute/' + plot_id,
					success: function(content) {
						$('#plot_details').html(content);
						$('#js_accordion').accordion({ autoHeight: false, navigation: true });
						handleLocalNames();
						handleLocalHistory();
						handlePlotNews();
						handleLocalOrgs();
					}
				});
				*/
			}
		});
		$('#js_updates').click(function(e) {
			e.preventDefault();

			if ($('#js_updates').hasClass('off'))
			{
				$('#js_updates').removeClass('off');
				$('#js_updates').addClass('on');
				if ($('#js_info').hasClass('on'))
				{
					$('#js_info').removeClass('on');
					$('#js_info').addClass('off');
				}
				else if ($('#js_contacts').hasClass('on'))
				{
					$('#js_contacts').removeClass('on');
					$('#js_contacts').addClass('off');
				}
				else if ($('#js_contribute').hasClass('on'))
				{
					$('#js_contribute').removeClass('on');
					$('#js_contribute').addClass('off');
				}
				$.get('/ajax/update/' + plot_id,
					function(data){
						$('#plot_details').html(data.details);
						$('#plot_addendum').html(data.addendum);
						handleUpdateSubscribe();
				}, "json");
			}
		});
	}
}

function handleLocalOrgs()
{
	if ($('#js_localorg_submit').length > 0)
	{
		$('#js_localorg_submit').click(function(e) {
			e.preventDefault();
			if ($('#js_localorg_name').attr('value') != '' || $('#js_localorg_url').attr('value') != '')
			{
				$.post('/ajax/localorg/' + plot_id, { "org": $('#js_localorg_name').attr('value'), "url": $('#js_localorg_url').attr('value') },
					function(data){
						pageRedirect(data.redirect);
						$('#js_localorg_name').val(data.org);
						$('#js_localorg_url').val(data.url);
						$('#js_local_org_list').prepend(data.li);
						triggerAlert(data.alert, '#js_localorg_alert');
				}, "json");
			}
		});
	}
}

function handlePlotNews()
{
	if ($('#js_localnews_submit').length > 0)
	{
		$('#js_localnews_submit').click(function(e) {
			e.preventDefault();
			if ($('#js_localnews').attr('value') != '')
			{
				$.post('/ajax/localnews/' + plot_id, { "msg": $('#js_localnews').attr('value') },
					function(data){
						pageRedirect(data.redirect);
						$('#js_localnews').val(data.msg);
						$('#js_local_news_list').prepend(data.li);
						triggerAlert(data.alert, '#js_localnews_alert');
				}, "json");
			}
		});
	}
}

function handleLocalHistory()
{
	if ($('#js_localhistory_submit').length > 0)
	{
		$('#js_localhistory_submit').click(function(e) {
			e.preventDefault();
			if ($('#js_localhistory').attr('value') != '')
			{
				$.post('/ajax/localhistory/' + plot_id, { "msg": $('#js_localhistory').attr('value') },
					function(data){
						pageRedirect(data.redirect);
						$('#js_localhistory').val(data.msg);
						$('#js_local_history_list').prepend(data.li);
						triggerAlert(data.alert, '#js_localhistory_alert');
				}, "json");
			}
		});
	}
}

function pageRedirect(url)
{
	if (typeof(url) == 'string' && url != '')
	{
		window.location.href = '/' + url;
	}
}

function handleLocalNames()
{
	if ($('#js_localname_submit').length > 0)
	{
		$('#js_localname_submit').click(function(e) {
			e.preventDefault();
			if ($('#js_localname').attr('value') != '')
			{
				$.post('/ajax/localname/' + plot_id, { "msg": $('#js_localname').attr('value') },
					function(data){
						pageRedirect(data.redirect);
						$('#js_localname').val(data.msg);
						$('#js_local_name_list').prepend(data.li);
						triggerAlert(data.alert, '#js_localname_alert');
				}, "json");
			}
		});
	}
}

function handleContactMessage()
{
	if ($('#js_contact_msg_submit').length > 0)
	{
		$('#js_contact_msg_submit').click(function(e) {
			e.preventDefault();
			if ($('#js_contact_msg').attr('value') != '')
			{
				$.post('/ajax/contactmsg/' + plot_id, { "msg": $('#js_contact_msg').attr('value') },
					function(data){
						pageRedirect(data.redirect);
						$('#js_contact_msg').val(data.msg);
						triggerAlert(data.alert, '#js_contact_msg_alert');
				}, "json");
			}
		});
	}
}

function handleCommunityContact()
{
	if ($('#js_my_contact_link').length > 0)
	{
		$('#js_my_contact_link').click(function(e) {
			e.preventDefault();
			$.get('/ajax/mycontact/' + plot_id, function(data) {
					pageRedirect(data.redirect);
					$('#js_my_contact_link').html(data.contactcount);
					$('#js_community_contacts').html(data.statement);
					triggerAlert(data.alert, '#js_my_contact_alert');
			}, "json");
		});
	}
}

function handleUpdateSubscribe()
{
	if ($('#js_my_updates_link').length > 0)
	{
		$('#js_my_updates_link').click(function(e) {
			e.preventDefault();
			$.get('/ajax/myupdates/' + plot_id, function(data) {
			//alert(data);
					pageRedirect(data.redirect);
					$('#js_my_updates_link').html(data.linkwording);
					triggerAlert(data.alert, '#js_my_updates_alert');
			}, "json");
		});
	}
}

function triggerAlert(alert, element)
{
	if ($(element).hasClass('hiddenalert'))
	{
		$(element).removeClass('hiddenalert');
		$(element).addClass('alert');
	}
	$(element).html(alert);
	activateAlerts();
}

function activateAlerts()
{
	$('p.alert').each(function() {
		$(this).hide();
		$(this).fadeIn(1200);
	});
}

function activateAccordion()
{
	if ($('#js_accordion').length > 0)
	{
		$('#js_accordion').accordion({ autoHeight: true, navigation: true });
	}
}

function captcha() {
	$('.js_refresh').click(function(e) {
		e.preventDefault();
		var rand = Math.floor(Math.random()*1000000000000000000);
		$('#js_captcha').attr('src', '/captcha.png?' + rand);
	});
}

function initSignin()
{
	captcha();
}

var writeCookie = function(name, value) {
	var date = new Date();
	date.setTime(date.getTime() + (182 * 24 * 60 * 60 * 1000));
	document.cookie = name + "=" + value + "; expires=" + date.toGMTString() + "; path=/";
}

$(document).ready(function() {
	activateAlerts();
});

