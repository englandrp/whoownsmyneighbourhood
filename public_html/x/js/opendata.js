var osMap,vectorLayer,boundaryLayer,lookup,clusterControl;

function home_init()
{
    if ($('#home_map').length > 0)
	{
		osMap = new OpenSpace.Map('home_map');
		boundaryLayer = createBoundaryLayer();
		var clickControl = new OpenLayers.Control.SelectFeature(boundaryLayer, {click:true, onSelect: onFeatureClick});

		// Add the (click) control to the map and activate.
		osMap.addControl(clickControl);
		clickControl.activate();
		osMap.addLayer(boundaryLayer);
		osMap.setCenter(new OpenSpace.MapPoint(413000, 416000), 5);
	}
}

function onFeatureClick(feature)
{
    window.location.href = '/region/' + feature.attributes.ADMIN_UNIT_ID;
}

function createBoundaryLayer()
{
    var symbolizer = OpenLayers.Util.applyDefaults({}, OpenLayers.Feature.Vector.style["default"]);
    var styleMap = new OpenLayers.StyleMap(symbolizer);
    var lookup = {
        "MTW": {
            fillColor: "#ff0000",
            strokeColor: "#00ffff",
            strokeWidth: 3
        }
    };
    styleMap.addUniqueValueRules("default", "AREA_CODE", lookup);
    var boundaryLayer = new OpenSpace.Layer.Boundary("Boundaries", {
        strategies: [new OpenSpace.Strategy.BBOX()],
        area_code: ["MTW"], // Metropolitan District Wards
        admin_unit_ids: [
            "16672", // Almondbury Ward
            "16686", // Ashbrow Ward
            "16754", // Batley East Ward
            "42456", // Batley West Ward
            "42455", // Birstall and Birkenshaw
            "16678", // Cleckheaton Ward
            "16738", // Colne Valley
            "16683", // Crosland Moor and Netherton Ward
            "42454", // Dalton Ward
            "16669", // Denby Dale Ward
            "16676", // Dewsbury East Ward	
            "16677", // Dewsbury South Ward	
            "16666", // Dewsbury West Ward
            "16718", // Golcar
            "16684", // Greenhead Ward
            "16762", // Heckmondwike Ward
            "16737", // Holme Valley North
            "16671", // Holme Valley South
            "42453", // Kirkburton Ward
            "16719", // Lindley Ward
            "16664", // Liversedge and Gomersal Ward
            "16668", // Mirfield Ward
            "16682"  // Newsome Ward
        ],
        styleMap: styleMap
    });
    return boundaryLayer;
};

function region_init()
{    
    if ($('#region_map').length > 0)
	{
		// Creates the map	
		osMap = new OpenSpace.Map('region_map');
		//osMap.setCenter(new OpenSpace.MapPoint(404798.02,411615.29), 7);
		osMap.setCenter(new OpenSpace.MapPoint(411817,417098), 8);
		$.getJSON("/json/10",
			function(data) {
				placeMarkers(data);
		});
	}
}

function placeMarkers(data)
{
	$.each(data, function(i, flag){
		var x = parseFloat(flag.easting);
		var y = parseFloat(flag.northing);
		var pos = new OpenSpace.MapPoint(x, y);
		var popupText = '<a href="/plot/' + flag.plot_id + '">';
		if (flag.location == '') popupText += 'View plot details</a>';
		else popupText += flag.location + '</a>';
		osMap.createMarker(pos, null, popupText);
        clusterControl = new OpenSpace.Control.ClusterManager();
        osMap.addControl(clusterControl);
	});
    clusterControl.activate();
}

function plot_init()
{
    if ($('#plot_map').length > 0)
	{
		osMap = new OpenSpace.Map('plot_map');
		osMap.setCenter(new OpenSpace.MapPoint($('#js_easting').val(),$('#js_northing').val()), 10);
		vectorLayer = new OpenLayers.Layer.Vector("Vector Layer");
		osMap.addLayer(vectorLayer);
		var plot_id = $('#js_plot_id').val();
		$.getJSON("/json/plot/" + plot_id,
		function(data) {
			//placePoints(data);
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
		fillColor: "#ffffff",
		fillOpacity: 0.6
	};
	$.each(polygons, function(i, polygon){
		var points = [];
		$.each(polygon.point, function(j, point){
			var x = parseFloat(point[0]);
			var y = parseFloat(point[1]);

			var lonlat = new OpenLayers.LonLat(x,y);
			var pos = gridProjection.getMapPointFromLonLat(lonlat);
			var pos_x = pos.lon;
			var pos_y = pos.lat
			points.push(new OpenLayers.Geometry.Point(pos_x, pos_y));
		});

		var linearRing = new OpenLayers.Geometry.LinearRing(points);
		var polygonFeature = new OpenLayers.Feature.Vector(linearRing, null, plot_style);
		vectorLayer.addFeatures([polygonFeature]);

	});
}

function placePoints(polygon)
{
	var plot_style =
	{
		strokeColor: "#ff0000",
		strokeOpacity: 1,
		strokeWidth: 2,
		fillColor: "#ffffff",
		fillOpacity: 0.6
	};

	var points = [];
	$.each(polygon, function(j, point){
		points.push(new OpenLayers.Geometry.Point(point[0],point[1]));
	});
	var linearRing = new OpenLayers.Geometry.LinearRing(points);
	var polygonFeature = new OpenLayers.Feature.Vector(linearRing, null, plot_style);
	vectorLayer.addFeatures([polygonFeature]);
}

function captcha() {
	$('.js_refresh').click(function(e) {
		e.preventDefault();
		var rand = Math.floor(Math.random()*1000000000000000000);
		$('#js_captcha').attr('src', '/captcha.png?' + rand);
	});
}

$(document).ready(function() {
	home_init();
	region_init();
	plot_init();
	captcha();
});


/*    var osMap;
    function plot_init()
    {
        if ($('#map').length > 0)
        {
            var opts = { resolutions: [2.5, 2, 1], restrictedExtent: new OpenSpace.MapBounds(394000, 397000, 434000, 435000) };
            osMap = new OpenSpace.Map('map', opts);
            osMap.setCenter(new OpenSpace.MapPoint($('#js_easting').val(),$('#js_northing').val()), 0);
            vectorLayer = new OpenLayers.Layer.Vector("Vector Layer");
            osMap.addLayer(vectorLayer);
            var plot_id = $('#js_plot_id').val();
            $.getJSON("/json/plot/" + plot_id,
            function(data) {
                place_loci(data);
            });
        }
    }

    function place_loci(polygons)
    {
        var gridProjection = new OpenSpace.GridProjection();
        var plot_style = { strokeColor:"#ff0000", strokeOpacity:1, strokeWidth:2, fillColor:"#ffffff", fillOpacity:0.6 };
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

    $(document).ready(function() {
        plot_init();
    });
*/
