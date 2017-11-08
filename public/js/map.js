/// <reference path="../Scripts/typings/jquery/jquery.d.ts" />
/// <reference path="../typings/google.maps.d.ts" />
function googleMapButton(text, className) {
    "use strict";
    var controlDiv = document.createElement("div");
    controlDiv.className = className;
    controlDiv.index = 1;
    controlDiv.style.padding = "10px";
    // set CSS for the control border.
    var controlUi = document.createElement("div");
    controlUi.style.backgroundColor = "rgb(255, 255, 255)";
    controlUi.style.color = "#565656";
    controlUi.style.cursor = "pointer";
    controlUi.style.textAlign = "center";
    controlUi.style.boxShadow = "rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px";
    controlDiv.appendChild(controlUi);
    // set CSS for the control interior.
    var controlText = document.createElement("div");
    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
    controlText.style.fontSize = "11px";
    controlText.style.paddingTop = "8px";
    controlText.style.paddingBottom = "8px";
    controlText.style.paddingLeft = "8px";
    controlText.style.paddingRight = "8px";
    controlText.innerHTML = text;
    controlUi.appendChild(controlText);
    $(controlUi).on("mouseenter", function () {
        controlUi.style.backgroundColor = "rgb(235, 235, 235)";
        controlUi.style.color = "#000";
    });
    $(controlUi).on("mouseleave", function () {
        controlUi.style.backgroundColor = "rgb(255, 255, 255)";
        controlUi.style.color = "#565656";
    });
    return controlDiv;
}
function FullScreenControl(map, enterFull, exitFull) {
    "use strict";
    if (enterFull === void 0) { enterFull = null; }
    if (exitFull === void 0) { exitFull = null; }
    if (enterFull == null) {
        enterFull = "Full screen";
    }
    if (exitFull == null) {
        exitFull = "Exit full screen";
    }
    var controlDiv = googleMapButton(enterFull, "fullScreen");
    var fullScreen = false;
    var interval;
    var mapDiv = map.getDiv();
    var divStyle = mapDiv.style;
    if (mapDiv.runtimeStyle) {
        divStyle = mapDiv.runtimeStyle;
    }
    var originalPos = divStyle.position;
    var originalWidth = divStyle.width;
    var originalHeight = divStyle.height;
    // ie8 hack
    if (originalWidth === "") {
        originalWidth = mapDiv.style.width;
    }
    if (originalHeight === "") {
        originalHeight = mapDiv.style.height;
    }
    var originalTop = divStyle.top;
    var originalLeft = divStyle.left;
    var originalZIndex = divStyle.zIndex;
    var bodyStyle = document.body.style;
    if (document.body.runtimeStyle) {
        bodyStyle = document.body.runtimeStyle;
    }
    var originalOverflow = bodyStyle.overflow;
    controlDiv.goFullScreen = function () {
        $('.main').css('z-index', "123331");
        var center = map.getCenter();
        mapDiv.style.position = "fixed";
        mapDiv.style.width = "100%";
        mapDiv.style.height = "100%";
        mapDiv.style.top = "0";
        mapDiv.style.left = "0";
        mapDiv.style.zIndex = "100";
        document.body.style.overflow = "hidden";
        $(controlDiv).find("div div").html(exitFull);
        fullScreen = true;
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        // this works around street view causing the map to disappear, which is caused by Google Maps setting the
        // css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
        interval = setInterval(function () {
            if (mapDiv.style.position !== "fixed") {
                mapDiv.style.position = "fixed";
                google.maps.event.trigger(map, "resize");
            }
        }, 100);
    };
    controlDiv.exitFullScreen = function () {
        $('.main').css('z-index', "");

        var center = map.getCenter();
        if (originalPos === "") {
            mapDiv.style.position = "relative";
        }
        else {
            mapDiv.style.position = originalPos;
        }
        mapDiv.style.width = originalWidth;
        mapDiv.style.height = originalHeight;
        mapDiv.style.top = originalTop;
        mapDiv.style.left = originalLeft;
        mapDiv.style.zIndex = originalZIndex;
        document.body.style.overflow = originalOverflow;
        $(controlDiv).find("div div").html(enterFull);
        fullScreen = false;
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        clearInterval(interval);
    };
    // setup the click event listener
    google.maps.event.addDomListener(controlDiv, "click", function () {
        if (!fullScreen) {
            controlDiv.goFullScreen();
        }
        else {
            controlDiv.exitFullScreen();
        }
    });
    return controlDiv;
}


/// <reference path="../Scripts/typings/jquery/jquery.d.ts" />
/// <reference path="../typings/google.maps.d.ts" />

function NightModeControl(map, enterFull, exitFull) {
    "use strict";
    if (enterFull === void 0) { enterFull = null; }
    if (exitFull === void 0) { exitFull = null; }
    if (enterFull == null) {
        enterFull = "Full screen";
    }
    if (exitFull == null) {
        exitFull = "Exit full screen";
    }

    var controlDiv = googleMapButton(enterFull, "fullScreen");
    var nightMode = false;
    var interval;
    var mapDiv = map.getDiv();
    var divStyle = mapDiv.style;
    if (mapDiv.runtimeStyle) {
        divStyle = mapDiv.runtimeStyle;
    }
    var originalPos = divStyle.position;
    var originalWidth = divStyle.width;
    var originalHeight = divStyle.height;
    // ie8 hack
    if (originalWidth === "") {
        originalWidth = mapDiv.style.width;
    }
    if (originalHeight === "") {
        originalHeight = mapDiv.style.height;
    }
    var originalTop = divStyle.top;
    var originalLeft = divStyle.left;
    var originalZIndex = divStyle.zIndex;
    var bodyStyle = document.body.style;
    if (document.body.runtimeStyle) {
        bodyStyle = document.body.runtimeStyle;
    }
    var originalOverflow = bodyStyle.overflow;
    controlDiv.goFullScreen = function () {

        map.setOptions({styles: [
            {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
                featureType: 'administrative.locality',
                elementType: 'labels.text.fill',
                stylers: [{color: '#d59563'}]
            },
            {
                featureType: 'poi',
                elementType: 'labels.text.fill',
                stylers: [{color: '#d59563'}]
            },
            {
                featureType: 'poi.park',
                elementType: 'geometry',
                stylers: [{color: '#263c3f'}]
            },
            {
                featureType: 'poi.park',
                elementType: 'labels.text.fill',
                stylers: [{color: '#6b9a76'}]
            },
            {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{color: '#38414e'}]
            },
            {
                featureType: 'road',
                elementType: 'geometry.stroke',
                stylers: [{color: '#212a37'}]
            },
            {
                featureType: 'road',
                elementType: 'labels.text.fill',
                stylers: [{color: '#9ca5b3'}]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry',
                stylers: [{color: '#746855'}]
            },
            {
                featureType: 'road.highway',
                elementType: 'geometry.stroke',
                stylers: [{color: '#1f2835'}]
            },
            {
                featureType: 'road.highway',
                elementType: 'labels.text.fill',
                stylers: [{color: '#f3d19c'}]
            },
            {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{color: '#2f3948'}]
            },
            {
                featureType: 'transit.station',
                elementType: 'labels.text.fill',
                stylers: [{color: '#d59563'}]
            },
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{color: '#17263c'}]
            },
            {
                featureType: 'water',
                elementType: 'labels.text.fill',
                stylers: [{color: '#515c6d'}]
            },
            {
                featureType: 'water',
                elementType: 'labels.text.stroke',
                stylers: [{color: '#17263c'}]
            }
        ]});

        $(controlDiv).find("div div").html(exitFull);
        nightMode = true;
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
        // this works around street view causing the map to disappear, which is caused by Google Maps setting the
        // css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
        interval = setInterval(function () {
            if (mapDiv.style.position !== "fixed") {
                mapDiv.style.position = "fixed";
                google.maps.event.trigger(map, "resize");
            }
        }, 100);
    };
    controlDiv.exitFullScreen = function () {
        map.setOptions({styles: []});


        $(controlDiv).find("div div").html(enterFull);
        nightMode = false;

    };
    // setup the click event listener
    google.maps.event.addDomListener(controlDiv, "click", function () {
        if (!nightMode) {
            controlDiv.goFullScreen();
        }
        else {
            controlDiv.exitFullScreen();
        }
    });
    return controlDiv;
}

rad = function(x) {return x*Math.PI/180;}

distHaversine = function(p1, p2) {
    var R = 6371; // earth's mean radius in km
    var dLat  = rad(p2.lat() - p1.lat());
    var dLong = rad(p2.lng() - p1.lng());

    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) * Math.sin(dLong/2) * Math.sin(dLong/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;

    return (d * 0.621371).toFixed(1);
}

function linkColor(link) {
    if (location.hostname == 'www.hamwan.org' || location.hostname == 'hamwan.org') {
        return link.STRENGTH && link.TYPE != 'SURVEY' ? '#0086db' : '#666';
    } else {
        return link.LINK_COLOR;
    }
}

function linkOpacity() {
    return location.hostname == 'www.hamwan.org' || location.hostname == 'hamwan.org' ? 0.5 : 0.7;
}

function linkWidth(link) {
    return Math.sqrt((link.SPEED || 1000000) / 10000000) + 2
}

var coverageOverlays = [];
function updateOverlays() {
    var coverage = [];
    $.each( $("input[name='showSites[]']:checked"), function() {
        var v = $(this).val();
        var site = v.split('|')[0];
        var sector = v.split('|')[1];
        c = jQuery.extend({}, globalCoverages[site]);
        c['id'] = site + "|" + sector;
        c['site'] = site;
        c['sector'] = sector;
        c['src'] = 'http://portal.hamwan.ca/bcih-portal/public/coverages/' + site + '-' + sector + '-' + $('#clientGain').val() + '.png';
        coverage.push(
            c
        );
    });

    // Hide
    for (var id in coverageOverlays) {
        coverageOverlays[ id ].setMap(null);
    }


    // Add coverage maps
    for (var i in coverage) {
        var overlay = coverage[i];
        var overlaybounds = new google.maps.LatLngBounds(
            new google.maps.LatLng( overlay.s, overlay.w),
            new google.maps.LatLng( overlay.n, overlay.e));
        if ( !             coverageOverlays[ overlay['id']] ) {

        coverageOverlays[ overlay['id']] = new google.maps.GroundOverlay(overlay.src,
            overlaybounds, {opacity:0.5});
        }
        coverageOverlays[ overlay['id']].setMap(map);
    }
}

var map;
function initialize() {
    //var coverage = [];

    var sites = {};

    // $.each( $("input[name='showSites[]']:checked"), function() {
    //     var v = $(this).val();
    //     var site = v.split('|')[0];
    //     var sector = v.split('|')[1];
    //     c = jQuery.extend({}, globalCoverages[site]);
    //     c['site'] = site;
    //     c['sector'] = sector;
    //     c['src'] = 'http://portal.hamwan.ca/bcih-portal/public/coverages/' + site + '-' + sector + '-' + $('#clientGain').val() + '.png';
    //     coverage.push(
    //         c
    //     );
    // });

    //     {
    //         NAME: 'BGM',
    //         src: 'http://portal.hamwan.ca/bcih-portal/public/coverages/BGM-' + $('#showSectors').val() + '-' + $('#clientGain').val() + '.png',
    //         n: 50.416943, e: -118.831222, s: 49.517241, w: -120.229844},
    //
    //     // {
    //     //     NAME: 'LMK',
    //     //     src: '/images/LMK-COVERAGE-123.png',
    //     //     n: 50.77874, e: -118.0649, s: 48.9801, w: -120.8561},
    //     // {
    //     //     NAME: 'KUI',
    //     //     src: '/images/KUI-COVERAGE-13.png',
    //     //     n: 50.69602, e: -118.0843, s: 48.89737, w: -120.8707},
    //     // {
    //     //     NAME: 'BKM',
    //     //     src: '/images/BKM-COVERAGE-13.png',
    //     //     n: 50.77594, e: -117.9111, s: 48.97729, w: -120.7021},
    //     // {   NAME: 'first 3',
    //     //     src: 'http://hamwan.org/map/hamwancoverage.png',
    //     //     n: 48.34369, e: -121.6021, s: 46.90387, w: -123.0283},
    //     // {   NAME: 'East Tiger',
    //     //     src: 'http://hamwan.org/map/etiger-240.png',
    //     //     n: 47.7114, e: -121.7513, s: 46.99149, w: -122.8139},
    //     // {   NAME: 'Gold',
    //     //     src: 'http://hamwan.org/map/gold-coverage.png',
    //     //     n: 47.86332, e: -122.3212, s: 47.2334, w: -123.2544},
    //     // {   NAME: 'Triangle',
    //     //     src: 'http://hamwan.org/map/triangle-coverage.png',
    //     //     n: 48.82953, e: -122.5549, s: 47.92965, w: -123.9097},
    //     // {   NAME: 'Capitol Peak',
    //     //     src: 'http://hamwan.org/map/capitolpeak-coverage.png',
    //     //     n: 47.42304, e: -122.4789, s: 46.52316, w: -123.7977},
    //     // //{   NAME: 'Mirrormont',
    //     // //    src: '//dl.dropboxusercontent.com/u/8174/mirrormontcoverage.png',
    //     // //    n: 47.89927, e: -121.5867, s: 46.63943, w: -123.0717},
    //     // {   NAME: 'Haystack',
    //     //     src: 'http://hamwan.org/map/haystackcoverage.png',
    //     //     n: 48.43857, e: -121.4244, s: 47.17873, w: -122.9248}
    // ];

    var mapOptions = {
        center: new google.maps.LatLng(49.8924,-119.4153), // Capitol Parkish
        zoom:  11,

        scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        }
    };

    if (typeof zoom !== 'undefined') {
        mapOptions['zoom'] = zoom;
    }
    if ( typeof centerLat !== 'undefined' || typeof centerLon !== 'undefined' ) {
        mapOptions['center'] = new google.maps.LatLng( centerLat,centerLon); // Capitol Parkish;
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    updateOverlays();
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
        FullScreenControl(map, "Full Screen",
            "Exit Full Screen"));
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
        NightModeControl(map, "Dark View",
            "Light View"));
    // Set up search/autocomplete

    var input = /** @type {HTMLInputElement} */(document.getElementById('searchTextField'));
    // var autocomplete = new google.maps.places.Autocomplete(input);

    // autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map
    });

    // google.maps.event.addListener(autocomplete, 'place_changed', function() {
    //     infowindow.close();
    //     marker.setVisible(false);
    //     input.className = '';
    //     var place = autocomplete.getPlace();
    //     if (!place.geometry) {
    //         // Inform the user that the place was not found and return.
    //         input.className = 'notfound';
    //         return;
    //     }
    //
    //     // If the place has a geometry, then present it on a map.
    //     if (place.geometry.viewport) {
    //         map.fitBounds(place.geometry.viewport);
    //     } else {
    //         map.setCenter(place.geometry.location);
    //         map.setZoom(13);
    //     }
    //     marker.setIcon(/** @type {google.maps.Icon} */({
    //         url: place.icon,
    //         size: new google.maps.Size(71, 71),
    //         origin: new google.maps.Point(0, 0),
    //         anchor: new google.maps.Point(17, 34),
    //         scaledSize: new google.maps.Size(35, 35)
    //     }));
    //     marker.setPosition(place.geometry.location);
    //     marker.setVisible(true);
    //
    //     var address = '';
    //     if (place.address_components) {
    //         address = [
    //             (place.address_components[0] && place.address_components[0].short_name || ''),
    //             (place.address_components[1] && place.address_components[1].short_name || ''),
    //             (place.address_components[2] && place.address_components[2].short_name || '')
    //         ].join(' ');
    //     }
    //
    //     infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    //     infowindow.open(map, marker);
    // });

    // // Add coverage maps
    // for (var i in coverage) {
    //     var overlay = coverage[i];
    //     var overlaybounds = new google.maps.LatLngBounds(
    //         new google.maps.LatLng( overlay.s, overlay.w),
    //         new google.maps.LatLng( overlay.n, overlay.e));
    //     var coveragemap = new google.maps.GroundOverlay(overlay.src,
    //         overlaybounds, {opacity:0.5});
    //     coveragemap.setMap(map);
    // }

    // Get network status (site/clients/link data)
    $.getJSON( statusSourceURL, function(data) {
        for (var siteid in data.SITES) {
            var site = data.SITES[siteid];
            sites[siteid] = site;
            sites[siteid].position = new google.maps.LatLng(site.LATITUDE, site.LONGITUDE);
        }
    })
        .fail(function() {
            //alert("Could not load network status data! Only the coverage map will be displayed. Internet Explorer 8 is not supported.");
        })
        .always(function() {
            // yellow dot
            var clienticon = {
                url: '//maps.gstatic.com/mapfiles/mv/imgs2.png',
                anchor: new google.maps.Point(6, 6),
                origin: new google.maps.Point(25, 104),
                size: new google.maps.Size(12, 12)
            }

            // orangered dot
            var surveyicon = {
                url: '//maps.gstatic.com/mapfiles/mv/imgs2.png',
                anchor: new google.maps.Point(4, 4),
                origin: new google.maps.Point(3, 124),
                size: new google.maps.Size(9, 9)
            }

            // Add sites to map
            for (var siteid in sites) {
                var site = sites[siteid]
                var sitemarker = new google.maps.Marker({
                    position: site.position,
                    icon: {
                        url: site.ICON,
                        anchor: new google.maps.Point(24, 25),
                        scaledSize: new google.maps.Size(50, 50)
                    },
                    map: map,
                    title: site.NAME,
                    comment: "<h3>" + site.NAME + "</h3><p>" + site.COMMENT + "</p>"
                });

                // Add clients to map
                for (var clientid in site.CLIENTS) {
                    var client = site.CLIENTS[clientid];
                    client.position = new google.maps.LatLng(client.LATITUDE, client.LONGITUDE);
                    var clientmarker = new google.maps.Marker({
                        position: client.position,
                        icon: surveyicon,
                        map: map,
                        title: client.NAME,
                        comment: "<h3>" + client.NAME + "</h3>" + (client.TYPE == "SURVEY" ? "<p>Signal survey</p>" : "") + "<ul><li>distance: " + distHaversine(client.position, site.position) + " miles </li><li>signal strength: " + client.STRENGTH + " dBm</li>" + (client.SPEED ? "<li>speed: " + client.SPEED/1000/1000 + " Mbps</li>" : '') + "</ul><p>" + client.COMMENT + "</p>"
                    });
                    google.maps.event.addListener(clientmarker, 'click', function() {
                        infowindow.setContent(this.comment);
                        infowindow.open(map, this);
                    });


                    // Add client links to map
                    if (client.STRENGTH) {
                        var linkpolyline = new google.maps.Polyline({
                            path: [site.position, client.position],
                            strokeColor: linkColor(client),
                            strokeOpacity: linkOpacity(),
                        });


                        linkpolyline.setMap(map);
                    }
                }

                // Add PtP links to map
                for (var linkid in site.LINKS) {
                    var link = site.LINKS[linkid];


                    if ( link.LINESTYLE == 'dotted') {
                        var lineSymbol = {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillOpacity: 1,
                            scale: 2,
                            fillColor: linkColor(link),
                            fillOpacity: linkOpacity(),
                            strokeWeight: linkWidth(link),
                        };
                        // only plot each link once (ignore reciprocal links)
                        if (siteid == link.SITE1_ID) {
                            var linkpolyline = new google.maps.Polyline({
                                path: [site.position, sites[link.SITE2_ID].position],
                                strokeColor: linkColor(link),
                                strokeOpacity: 0,
                                strokeWeight: 0,
                                icons: [{
                                    icon: lineSymbol,
                                    offset: '0',
                                    repeat: '10px'
                                }],
                            });
                            linkpolyline.setMap(map);
                        }
                    } else {
                        if (siteid == link.SITE1_ID) {
                            var linkpolyline = new google.maps.Polyline({
                                path: [site.position, sites[link.SITE2_ID].position],
                                strokeColor: linkColor(link),
                                strokeOpacity: linkOpacity(),
                                strokeWeight: linkWidth(link),
                            });
                            linkpolyline.setMap(map);
                        }
                    }
                    // Add link data to comment of both associated sites
                    sitemarker.comment += "<h3>" + link.NAME + " link</h3><ul><li>distance: " + distHaversine(sites[link.SITE1_ID].position, sites[link.SITE2_ID].position) + " miles </li><li>signal strength: " + link.STRENGTH + " dBm</li>" + "<li>speed: " + link.SPEED/1000/1000 + " Mbps</li></ul><p>" + link.COMMENT + "</p>"
                }

                // create site infowindow (now that link data is incorporated)
                google.maps.event.addListener(sitemarker, 'click', function() {
                    infowindow.setContent(this.comment);
                    infowindow.open(map, this);
                });

            }
        });

    // Add mesh nodes kml map
    //var ActiveNodesLayer = new google.maps.KmlLayer('http://nodes.map.nwmesh.us/');
    //ActiveNodesLayer.setMap(map);


    // Add the controls to the map
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(document.getElementById('search'));
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('fullscreen'));

}
var globalCoverages = [];
$.getJSON( "http://portal.hamwan.ca/bcih-portal/public/coverages", function(data) {
    globalCoverages = data;
    google.maps.event.addDomListener(window, 'load', initialize);
});
