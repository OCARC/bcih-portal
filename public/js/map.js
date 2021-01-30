/// <reference path="../Scripts/typings/jquery/jquery.d.ts" />
/// <reference path="../typings/google.maps.d.ts" />
var globalCoverages = [];
var map;
var siteMarkers = [];
var linkMarkers = [];

$(document).ready( function() {

    $('#map_status').text( "Loading Coverage Data..." ).show();

    $.getJSON( "//portal.hamwan.ca/coverages", function(data) {
        globalCoverages = data;




        $('#map_status').text( "" ).hide();

        setTimeout( function() {
            initialize();
            google.maps.event.addDomListener(window, 'load', initialize);
        }, 250);
    });

});

var infowindow = new google.maps.InfoWindow();

function drawMarkers() {

    // Get network status (site/clients/link data)
    $.getJSON( statusSourceURL, function(data) {
        for (var siteid in data.SITES) {
            var site = data.SITES[siteid];

            if ( site.VISIBLE == 'hide') { continue; }
            if (!siteMarkers[siteid]) {
                siteMarkers[siteid] = [];
                siteMarkers[siteid].clientMarkers = [];
            }
            siteMarkers[siteid].data = site;
            siteMarkers[siteid].position = new google.maps.LatLng(site.LATITUDE, site.LONGITUDE);

            var siteIcon = {
                url: 'http://portal.hamwan.ca/status/icon/site.svg?siteID=' + siteid,
                anchor: new google.maps.Point(20, 20),
                scaledSize: new google.maps.Size(40, 40)

            };
            if (typeof siteMarkers[siteid].data.ICON != 'object') {
                siteIcon = {
                    url: 'http://portal.hamwan.ca/status/icon/site.svg?siteID=' + siteid,
                    anchor: new google.maps.Point(20, 20),
                    scaledSize: new google.maps.Size(40, 40)

                }
            }
            siteMarkers[siteid].marker = new google.maps.Marker({
                position: siteMarkers[siteid].position,
                icon:  siteIcon,
                map: map,
                title: siteMarkers[siteid].data.NAME,
                comment: "<h3>" + siteMarkers[siteid].data.NAME + "</h3><p>" + siteMarkers[siteid].data.COMMENT + "</p>"
            });

                // create site infowindow (now that link data is incorporated)
                google.maps.event.addListener(siteMarkers[siteid].marker, 'click', function() {
                    infowindow.setContent(this.comment);
                    infowindow.open(map, this);
                });
            // Handle the clients
            for (var clientID in data.SITES[siteid].CLIENTS) {
                var client = data.SITES[siteid].CLIENTS[clientID];

                console.log( client.age );
                if ( client.age >= 86400)
    {
        continue;
    }
                if (!siteMarkers[siteid].clientMarkers[clientID]) {
                    siteMarkers[siteid].clientMarkers[clientID] = [];
                    siteMarkers[siteid].clientMarkers[clientID].link = [];
                }

                siteMarkers[siteid].clientMarkers[clientID].data = client;
                siteMarkers[siteid].clientMarkers[clientID].position = new google.maps.LatLng(data.SITES[siteid].CLIENTS[clientID].LATITUDE, data.SITES[siteid].CLIENTS[clientID].LONGITUDE);

                siteMarkers[siteid].clientMarkers[clientID].marker = new google.maps.Marker({
                    position: siteMarkers[siteid].clientMarkers[clientID].position,
                    map: map,
                    icon: {
                        url: 'http://portal.hamwan.ca/status/icon/station.svg?clientID=' + clientID,
                        anchor: new google.maps.Point(25, 25),
                        scaledSize: new google.maps.Size(50, 50)

                    },
                    title: siteMarkers[siteid].clientMarkers[clientID].data.NAME,
                    comment: "<h3>" + siteMarkers[siteid].clientMarkers[clientID].data.NAME + "</h3><p>" + siteMarkers[siteid].clientMarkers[clientID].data.COMMENT + "</p>"
                });

                google.maps.event.addListener(siteMarkers[siteid].clientMarkers[clientID].marker, 'click', function() {
                    infowindow.setContent(this.comment);
                    infowindow.open(map, this);
                });

                // Handle Site to Client Links
                if (client.STRENGTH) {
                    siteMarkers[siteid].clientMarkers[clientID].link = new google.maps.Polyline({
                        path: [siteMarkers[siteid].position, siteMarkers[siteid].clientMarkers[clientID].position],
                        strokeColor: linkColor(client),
                        strokeOpacity: linkOpacity(),
                    });


                    siteMarkers[siteid].clientMarkers[clientID].link.setMap(map);
                }
            }
        }

        // Handle Links
        for (var siteid in data.SITES) {
            var site = data.SITES[siteid];

            for (var linkID in data.SITES[siteid].LINKS ) {
                var link = data.SITES[siteid].LINKS[linkID];

                if (!linkMarkers[linkID]) {
                    linkMarkers[linkID] = [];
                }

                if (siteid == link.SITE1_ID) {


                linkMarkers[linkID].data = client;
                if ( ! siteMarkers[link.SITE1_ID] ) {
                    continue;
                }
                if ( ! siteMarkers[link.SITE2_ID] ) {
                    continue;
                }
                if ( link.LINESTYLE == 'solid' ) {
                    linkMarkers[linkID].link = new google.maps.Polyline({
                        path: [siteMarkers[link.SITE1_ID].position, siteMarkers[link.SITE2_ID].position],
                        strokeColor: linkColor(link),
                        strokeOpacity: linkOpacity(),
                        strokeWeight: linkWidth(link)
                    });
                }

                if ( link.LINESTYLE == 'dotted' ) {
                    var lineSymbol = {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillOpacity: linkOpacity(),
                        scale: linkWidth(link)/2,
                        // fillColor: linkColor(link),
                        // fillOpacity: linkOpacity(),
                        // strokeWeight: linkWidth(link),
                    };

                    linkMarkers[linkID].link = new google.maps.Polyline({
                        path: [siteMarkers[link.SITE1_ID].position, siteMarkers[link.SITE2_ID].position],
                        strokeColor: linkColor(link),
                        strokeOpacity: linkOpacity(),
                        strokeWeight: 0,
                        icons: [{
                            icon: lineSymbol,
                            offset: '0',
                            repeat: linkWidth(link)*3 + "px"
                        }]
                    });

                }

                linkMarkers[linkID].link.setMap(map);
                }
            }


        }


    })
        .fail(function() {
            //alert("Could not load network status data! Only the coverage map will be displayed. Internet Explorer 8 is not supported.");
        })
        .always(function() {
            // // yellow dot
            // var clienticon = {
            //     url: '//maps.gstatic.com/mapfiles/mv/imgs2.png',
            //     anchor: new google.maps.Point(6, 6),
            //     origin: new google.maps.Point(25, 104),
            //     size: new google.maps.Size(12, 12)
            // }
            //
            // // orangered dot
            // var surveyicon = {
            //     url: '//maps.gstatic.com/mapfiles/mv/imgs2.png',
            //     anchor: new google.maps.Point(4, 4),
            //     origin: new google.maps.Point(3, 124),
            //     size: new google.maps.Size(9, 9)
            // }
            //
            // // Add sites to map
            // for (var siteid in sites) {
            //     var site = sites[siteid]
            //     var sitemarker = new google.maps.Marker({
            //         position: site.position,
            //         icon: {
            //             url: site.ICON,
            //             anchor: new google.maps.Point(24, 25),
            //             scaledSize: new google.maps.Size(50, 50)
            //         },
            //         map: map,
            //         title: site.NAME,
            //         comment: "<h3>" + site.NAME + "</h3><p>" + site.COMMENT + "</p>"
            //     });
            //
            //     // Add clients to map
            //     for (var clientid in site.CLIENTS) {
            //         var client = site.CLIENTS[clientid];
            //         client.position = new google.maps.LatLng(client.LATITUDE, client.LONGITUDE);
            //         var clientmarker = new google.maps.Marker({
            //             position: client.position,
            //             icon: surveyicon,
            //             map: map,
            //             title: client.NAME,
            //             comment: "<h3>" + client.NAME + "</h3>" + (client.TYPE == "SURVEY" ? "<p>Signal survey</p>" : "") + "<ul><li>distance: " + distHaversine(client.position, site.position) + " miles </li><li>signal strength: " + client.STRENGTH + " dBm</li>" + (client.SPEED ? "<li>speed: " + client.SPEED/1000/1000 + " Mbps</li>" : '') + "</ul><p>" + client.COMMENT + "</p>"
            //         });
            //         google.maps.event.addListener(clientmarker, 'click', function() {
            //             infowindow.setContent(this.comment);
            //             infowindow.open(map, this);
            //         });
            //
            //
            //         // Add client links to map
            //         if (client.STRENGTH) {
            //             var linkpolyline = new google.maps.Polyline({
            //                 path: [site.position, client.position],
            //                 strokeColor: linkColor(client),
            //                 strokeOpacity: linkOpacity(),
            //             });
            //
            //
            //             linkpolyline.setMap(map);
            //         }
            //     }
            //
            //     // Add PtP links to map
            //     for (var linkid in site.LINKS) {
            //         var link = site.LINKS[linkid];
            //
            //
            //         if ( link.LINESTYLE == 'dotted') {
            //             var lineSymbol = {
            //                 path: google.maps.SymbolPath.CIRCLE,
            //                 fillOpacity: 1,
            //                 scale: 2,
            //                 fillColor: linkColor(link),
            //                 fillOpacity: linkOpacity(),
            //                 strokeWeight: linkWidth(link),
            //             };
            //             // only plot each link once (ignore reciprocal links)
            //             if (siteid == link.SITE1_ID) {
            //                 var linkpolyline = new google.maps.Polyline({
            //                     path: [site.position, sites[link.SITE2_ID].position],
            //                     strokeColor: linkColor(link),
            //                     strokeOpacity: 0,
            //                     strokeWeight: 0,
            //                     icons: [{
            //                         icon: lineSymbol,
            //                         offset: '0',
            //                         repeat: '10px'
            //                     }],
            //                 });
            //                 linkpolyline.setMap(map);
            //             }
            //         } else {
            //             if (siteid == link.SITE1_ID) {
            //                 var linkpolyline = new google.maps.Polyline({
            //                     path: [site.position, sites[link.SITE2_ID].position],
            //                     strokeColor: linkColor(link),
            //                     strokeOpacity: linkOpacity(),
            //                     strokeWeight: linkWidth(link),
            //                 });
            //                 linkpolyline.setMap(map);
            //             }
            //         }
            //         // Add link data to comment of both associated sites
            //         sitemarker.comment += "<h3>" + link.NAME + " link</h3><ul><li>distance: " + distHaversine(sites[link.SITE1_ID].position, sites[link.SITE2_ID].position) + " miles </li><li>signal strength: " + link.STRENGTH + " dBm</li>" + "<li>speed: " + link.SPEED/1000/1000 + " Mbps</li></ul><p>" + link.COMMENT + "</p>"
            //     }
            //
            //     // create site infowindow (now that link data is incorporated)
            //     google.maps.event.addListener(sitemarker, 'click', function() {
            //         infowindow.setContent(this.comment);
            //         infowindow.open(map, this);
            //     });
            //
            // }
        });


}

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
var coverageHash = "";
var gainHash = '015';
var qualityHash = 'geo';
function updateOverlays() {
    var coverage = [];
    coverageHash = "";
    gainHash = $('#clientGain').val();
    qualityHash = $('#quality').val();
    $.each( $("input[name='showSites[]']:checked"), function() {
        var v = $(this).val();
        var site = v.split('|')[0];
        var sector = v.split('|')[1].toString();
        sector = sector.padStart(3,'0');

        c = jQuery.extend({}, globalCoverages[site]);
        c['id'] = site + "|" + sector + "|" + $('#clientGain').val() + "|" + $('#quality').val();
        c['site'] = site;
        c['sector'] = sector;
        c['quality'] = $('#quality').val();
        c['geo'] = 'http://portal.hamwan.ca/coverages/' + site + '-' + sector + '-' + $('#clientGain').val() + '.json';
        c['topo'] = 'http://portal.hamwan.ca/coverages/' + site + '-' + sector + '-' + $('#clientGain').val() + '.topo.json';
        c['src'] = 'http://portal.hamwan.ca/coverages/' + site + '-' + sector + '-' + $('#clientGain').val() + '.png?speed=' + $('#quality').val();

        var ename;
            ename = "RADIO" + parseInt(sector) + "_" + site;

        c['VA7STV'] = 'http://scratchy.compucomp.net/maps/output/' + site + '/' + ename + '/HD/' + parseInt($('#clientGain').val()) + '/' + ename + '.json';
        coverage.push(
            c
        );

        if ( sector != 'ALL' ) {
            coverageHash = coverageHash + site + "|" + Number(sector) + ","

        } else {
            coverageHash = coverageHash + site + "|ALL,"

        }
    });

    document.location.hash = coverageHash.slice(0, -1) + ";" + gainHash + ";" + qualityHash;


    // Hide
    for (var id in coverageOverlays) {
        coverageOverlays[ id ].setMap(null);
        //delete coverageOverlays[ id ];
    }


    // Add coverage maps
    for (var i in coverage) {
        var overlay = coverage[i];
        var overlaybounds = new google.maps.LatLngBounds(
            new google.maps.LatLng( overlay.s, overlay.w),
            new google.maps.LatLng( overlay.n, overlay.e));
        if ( !             coverageOverlays[ overlay['id']] ) {

            if (  overlay['quality'] == 'geo' ||overlay['quality'] == 'topo' || overlay['quality'] == 'VA7STV') {
                coverageOverlays[ overlay['id']] = new google.maps.Data();


                coverageOverlays[ overlay['id']].setStyle(function(feature) {

                    var level = feature.getProperty('n');
                    var flevel = feature.getProperty('n-future');




                    var colorMap = {};

                    colorMap[40] = '000, 255, 000';
                    colorMap[39] = '000, 255, 000';
                    colorMap[38] = '001, 255, 000';
                    colorMap[37] = '002, 255, 000';
                    colorMap[36] = '004, 255, 000';
                    colorMap[35] = '008, 255, 000';
                    colorMap[34] = '014, 255, 000';
                    colorMap[33] = '022, 255, 000';
                    colorMap[32] = '032, 255, 000';
                    colorMap[31] = '046, 255, 000';
                    colorMap[30] = '063, 255, 000';
                    colorMap[29] = '084, 255, 000';
                    colorMap[28] = '108, 255, 000';
                    colorMap[27] = '133, 255, 000';
                    colorMap[26] = '159, 255, 000';
                    colorMap[25] = '183, 255, 000';
                    colorMap[24] = '206, 255, 000';
                    colorMap[23] = '226, 255, 000';
                    colorMap[22] = '242, 255, 000';
                    colorMap[21] = '252, 255, 000';
                    colorMap[20] = '255, 255, 000';
                    colorMap[19] = '255, 252, 000';
                    colorMap[18] = '255, 242, 000';
                    colorMap[17] = '255, 226, 000';
                    colorMap[16] = '255, 206, 000';
                    colorMap[15] = '255, 183, 000';
                    colorMap[14] = '255, 159, 000';
                    colorMap[13] = '255, 133, 000';
                    colorMap[12] = '255, 108, 000';
                    colorMap[11] = '255, 084, 000';
                    colorMap[10] = '255, 063, 000';
                    colorMap[9] = '255, 046, 000';
                    colorMap[8] = '255, 032, 000';
                    colorMap[7] = '255, 014, 000';
                    colorMap[6] = '255, 008, 000';
                    colorMap[5] = '255, 004, 000';
                    colorMap[4] = '255, 003, 000';
                    colorMap[3] = '255, 002, 000';
                    colorMap[2] = '255, 001, 000';
                    colorMap[1] = '255, 000, 000';
                    colorMap[0] = '100, 100, 255';

                    colorMap['90'] = '255, 000, 000';

                    var r =  {
                        //fillOpacity: (level <= 12) ? 0 : .5,
                        fillColor: 'rgb(' + colorMap[level] + ")",
                        strokeWeight: 0,
                         strokeColor: 'rgb(' + colorMap[level] + ")"
                    };

                    if ( flevel ) {
                        r['strokeWeight'] = 2;
                        r['fillColor'] =  'rgb(' + colorMap[flevel] + ")";

                            r['strokeColor'] = 'rgb(' + colorMap[0] + ")";
                    }
                    return r;

                });
                coverageOverlays[ overlay['id']].loadGeoJson( overlay[overlay['quality']] );
                coverageOverlays[ overlay['id']].addListener('mouseover', function (e) {
                    if ( e.feature.f['n-future'] ) {
                        $('#hoverInfo .text').html("<strong>Signal Strength:</strong>-" + (90 - e.feature.f['n-future']) + " dB  (Future Coverage) ")
                    } else {
                        //$('#hoverInfo').html("<strong>Signal Strength:</strong> -" + (90-e.feature.f.n) + " dB &nbsp;&nbsp;&nbsp;&nbsp;<strong>Best Target:</strong> " + e.feature.f.src )
                        if ( e.feature.f.n <= 10 ) {

                            $('#hoverInfo .text').html("<strong>Signal Strength:</strong>-" + (90 - e.feature.f.n) + " dB  (Marginal/Very Poor) ")
                        } else {
                            $('#hoverInfo .text').html("<strong>Signal Strength:</strong> -" + (90 - e.feature.f.n) + " dB ")

                        }
                    }

                });
                coverageOverlays[ overlay['id']].addListener('mouseout', function (e) {
                    $('#hoverInfo .text').html("")
                });

            } else {
                coverageOverlays[ overlay['id']] = new google.maps.GroundOverlay(overlay.src,
                    overlaybounds, {opacity:0.5});
            }

        }
        coverageOverlays[ overlay['id']].setMap(map);

    }
}

var map;
function GainControl(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 1px 3px rgba(0,0,0,.3)';
    controlUI.style.margin = '10px';
    controlUI.style.lineHeight = '30px';
    controlUI.style.textAlign = 'center';
    controlUI.innerHTML = '<strong style="padding-left: 10px">Client Gain: </strong>';

    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('select');
    controlText.style.maxWidth = '60px';

    var vals = "";
    for( i=1; i<32; i++) {
        if ( i == 15 ) {
            vals += '<option selected="selected" value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB</option>';
        } else if ( i == 16 ) {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB (SXT 5)</option>';

        } else if ( i == 21 ) {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB (DISC Lite 5)</option>';

        } else if ( i == 24 ) {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB (LHG 5)</option>';

        }else if ( i == 27 ) {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB (LHG 5 XL)</option>';

        }else if ( i == 31 ) {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB Test Not Real Data</option>';

        }else {
            vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB</option>';

        }
    }
    controlText.onChange = function() {updateOverlays();  }
    controlText.id = "clientGain" ;
    controlText.innerHTML = vals ;
    controlText.style.border = '0px';

    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '11px';
    controlText.style.height = '30px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.style.cursor = 'pointer';

    controlUI.appendChild(controlText);

    // Setup the click event listeners: simply set the map to Chicago.
    controlText.addEventListener('change', function() {
        updateOverlays();
    });

}
function OverlayControl(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 1px 3px rgba(0,0,0,.3)';
    controlUI.style.margin = '10px';
    controlUI.style.textAlign = 'center';

    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    // var vals = "";
    // for( i=1; i<30; i++) {
    //     if ( i == 15 ) {
    //         vals += '<option selected="selected" value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB</option>';
    //     } else {
    //         vals += '<option  value="' + (i.toString().padStart(3,'0')) + '">' + i + 'dB</option>';
    //
    //     }
    // }
    controlText.onChange = function() {updateOverlays();  }
    controlText.id = "clientGain" ;
    //controlText.style.color = 'rgb(25,25,25)';
    controlText.style.border = '0px';

    //controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '11px';
    controlText.style.lineHeight = '30px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';;
    controlText.style.fontWeight = 'bold';
    controlText.style.cursor = 'pointer';
    controlText.innerHTML = 'Choose Coverages <span class="caret" style="color: rgb(25,25,25)"></span>';

    controlUI.appendChild(controlText);

    var listText = document.createElement('div');
    listText.id =         "coverageList";
    listText.style.display = 'none';
    listText.style.textAlign = 'left';

    style=' opacity: 1;';

    sk = 'ALL';

    k = "ALL";
    if ( $(listText).find('.' +k).length == 0 ) {
        $(listText).append('<div style="" class="' + k + '"><strong style="display: inline-block; text-align: right; width: 28px">'  + k + ': </strong></div>');
    }
    $(listText) .find('.' + k).append("<label style='padding-left: 2px; padding-right: 2px; border-radius: 5px; width: 42px;    white-space: nowrap; display:inline-block; " + style + "'><input name='showSites[]' checked='1' onChange='updateOverlays();' value='" + k + "|" + sk + "' type='checkbox' " + checked+ ">&nbsp;" + sk + "</label>");
    for( k in globalCoverages ) {

        //initialize();
        for (sk in globalCoverages[k].SECTORS) {
            sk.padStart(3,'0');

            if ( $(listText).find('.' +k).length == 0 ) {
                $(listText).append('<div style="" class="' + k + '"><strong style="display: inline-block; text-align: right; width: 28px">'  + k + ': </strong></div>');
                style=' opacity: 1;';

                $(listText) .find('.' + k).append("<label style='padding-left: 2px; padding-right: 2px; border-radius: 5px; width: 42px;    white-space: nowrap; display:inline-block; " + style + "'><input name='showSites[]' onChange='updateOverlays();' value='" + k + "|ALL' type='checkbox' " + checked+ ">&nbsp;ALL</label>");

            }

            style=' opacity: 0.5; ';
            var checked='';
            if ( globalCoverages[k].SECTORS[sk].status == 'Installed' ) {
                style=' opacity: 1; background-color: #aaffaa;';
            }
            if ( globalCoverages[k].SECTORS[sk].status == 'Problems' ) {
                style=' opacity: 1; background-color: #ffd355;';

            }
            if ( globalCoverages[k].SECTORS[sk].status == 'Planning' ) {
                style=' opacity: 1; background-color: #fff6a6;';

            }
            if ( globalCoverages[k].SECTORS[sk].status == 'Potential' ) {
                style=' opacity: 1; background-color: #e1e1e1;';
            }

            sk.padStart(3,'0');

            $(listText) .find('.' + k).append("<label style='padding-left: 2px; padding-right: 2px; border-radius: 5px; width: 42px;    white-space: nowrap; display:inline-block; " + style + "'><input name='showSites[]' onChange='updateOverlays();' value='" + k + "|" + sk + "' type='checkbox' " + checked+ ">&nbsp;" + sk + "&deg;</label>");
        }

    }
    $(listText).append('ALL is experimental and only available<br>for some coverages with the quality<br>set to GeoJSON');

    controlUI.appendChild(listText);


    // Setup the click event listeners: simply set the map to Chicago.
    controlText.addEventListener('click', function() {
        $('#coverageList').toggle()
    });



}
function parseHash() {
    // Parse Hash

    var hash = document.location.hash;
    if ( hash ) {
        hash =hash.slice(1);
        var parts = hash.split(';');
        var covs = parts[0].split(',');
        var gain = parts[1];
        var quality = parts[2];


        $("#quality").val( quality );
        $("#clientGain").val( gain );
        $('input[name="showSites[]"]').removeAttr('checked');

        for ( var i = 0; i < covs.length; i++) {
            $('input[value="' + covs[i] + '"]').attr('checked','true');
        }
    }

    setTimeout(function() {

        updateOverlays();

    },1000);
}
function QualityControl(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 1px 3px rgba(0,0,0,.3)';
    controlUI.style.margin = '10px';
    controlUI.style.lineHeight = '30px';
    controlUI.style.textAlign = 'center';
    controlUI.innerHTML = '<strong style="padding-left: 10px">Quality: </strong>';

    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('select');
    var vals = "";
        vals += '<option value="none">None</option>';
        vals += '<option value="geo">GeoJSON</option>';
        vals += '<option value="topo">TopoJSON</option>';
        vals += '<option value="VA7STV">GeoJSON (VA7STV)</option>';
        vals += '<option value="10">Fast</option>';
        vals += '<option value="6">Medium</option>';
        vals += '<option value="3">High</option>';
        vals += '<option value="1">Extreme</option>';

    // controlText.attr('onChange', 'updateOverlays()');
    controlText.id = "quality" ;
    controlText.innerHTML = vals ;
    controlText.style.border = '0px';
    controlText.style.cursor = 'pointer';

    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '11px';
    controlText.style.height = '30px';
    controlText.style.lineHeight = '38px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlUI.appendChild(controlText);



    // Setup the click event listeners: simply set the map to Chicago.
    controlText.addEventListener('change', function() {
        updateOverlays();
    });
}

function LegendControl(controlDiv, map) {

    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 1px 3px rgba(0,0,0,.3)';
    controlUI.style.margin = '10px';
    controlUI.style.lineHeight = '30px';
    controlUI.style.textAlign = 'center';

    var controlText = document.createElement('span')
    controlText.id= "hoverInfo";
    controlText.innerHTML = "<span class='legend'>-50<span class='legendGrad'></span>-90</span> <span class='text'></span>" ;

    controlUI.appendChild(controlText);

    controlDiv.appendChild(controlUI);

}
function initialize() {
    //var coverage = [];

    var sites = {};

    console.log(mapOptions);

    var lat = 49.8924;
    if ( typeof targetLat !== 'undefined' ) {
        lat = targetLat;
    }
    var lon = -119.4153;
    if ( typeof targetLon !== 'undefined' ) {
        lon = targetLon;
    }
        var mapOptions = {
            center: new google.maps.LatLng( lat, lon),
            zoom: 11,

            scrollwheel: true,
            mapTypeId: google.maps.MapTypeId.TERRAIN,
            mapTypeControl: true,
            scaleControl: true,

            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            fullscreenControl: true
        };


    if (typeof zoom !== 'undefined') {
        mapOptions['zoom'] = zoom;
    }
    if ( typeof centerLat !== 'undefined' || typeof centerLon !== 'undefined' ) {
        mapOptions['center'] = new google.maps.LatLng( centerLat,centerLon); // Capitol Parkish;
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);


    google.maps.event.addListenerOnce(map, 'tilesloaded', parseHash);

    var gainControlDiv = document.createElement('div');
    var gainControl = new GainControl(gainControlDiv, map);

    gainControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(gainControlDiv);

    var qualityControlDiv = document.createElement('div');
    var qualityControl = new QualityControl(qualityControlDiv, map);

    qualityControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(qualityControlDiv);


    var legendControlDiv = document.createElement('div');
    var legendControl = new LegendControl(legendControlDiv, map);

    legendControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(legendControlDiv);


    var overlayControlDiv = document.createElement('div');
    var overlayControl = new OverlayControl(overlayControlDiv, map);

    overlayControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(overlayControlDiv);
    
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


    drawMarkers();
    // Add mesh nodes kml map
    //var ActiveNodesLayer = new google.maps.KmlLayer('http://nodes.map.nwmesh.us/');
    //ActiveNodesLayer.setMap(map);


    // Add the controls to the map
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(document.getElementById('search'));
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('fullscreen'));



}

