var turf = require('@turf/turf');

// var turf = require('turf');
var helpers = require('@turf/helpers');
var truncate = require('@turf/truncate');
var clean = require('@turf/clean-coords');
var overlap = require('@turf/boolean-overlap');

var fs = require("fs");

if ( process.cwd() == "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/geoparse") {
    var sourceDir = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/";

} else{
    var sourceDir = "/hamwan/bcih-portal/public/projections/";

}

var inputGain = process.argv[2];

var sites = {'LMK' : ['000','120','240'],'BGM' : ['120'],'KUI' : ['000','240'],'BKM' : ['000','240']};
var futureSites = {'TUR': ['000','120','240'], 'BKM' : ['120']};
var sectors = ['000','120','240'];

var siteCode = process.argv[3];

var sites = { };
var futureSites = {};


if ( siteCode == 'ALL') {
    sites['LMK'] = ['ALL'];
    sites['BGM'] = ['ALL'];
    sites['BKM'] = ['ALL'];
    sites['KUI'] = ['ALL'];
    sites['TUR'] = ['ALL'];
} else {
    if ( siteCode == "BKM") {
        sites[ siteCode ] = ['000','240'];
        futureSites[ siteCode ] = ['120'];
    }  else if ( siteCode == "TUR") {
        sites[siteCode] = [];
        futureSites[siteCode] = ['000', '120', '240'];

    } else {
        sites[siteCode] = ['000', '120', '240'];
    }
}

var sectors = ['000','120','240'];


// Find all of the files we are going to work

var Polys = [];

var OutPolys = [];
var FileA;
var FileB;
// Try to load polys

for (var key in sites) {
    for (var b = 0, len = sites[key].length; b < len; b++) {
        console.log("Loading " + sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json" + "..." );
        if (fs.existsSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json")) {

            console.log("Loading polygons for " + key + " - " + sites[key][b] + "...");
            FileA_content = fs.readFileSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json");
            FileA = JSON.parse(FileA_content);
            FileA.features.forEach(function (featureA) {

                Polys.push(featureA)
            });
        }
    }
}
// Load future site polys

for (var key in futureSites) {
    for (var b = 0, len = futureSites[key].length; b < len; b++) {

        if (fs.existsSync(sourceDir + key + "/" + key + "-" + futureSites[key][b] + "-" + inputGain + ".json")) {

            console.log("Loading polygons for " + key + " - " + futureSites[key][b] + "...");
            FileB_content = fs.readFileSync(sourceDir + key + "/" + key + "-" + futureSites[key][b] + "-" + inputGain + ".json");
            FileB = JSON.parse(FileB_content);
            FileB.features.forEach(function (featureA) {
                //if (featureA.properties['n'] >= 15) {
                featureA.properties['n'] = 0;

                    Polys.push(featureA)
                //}
            });
        }
    }
}
console.log("Loaded " + Polys.length + " polygons...");

// Seperate polygons into strength layers
var LayerPoly = {};
var OutLayerPoly = {};

for (var a = 0, len = Polys.length; a < len; a++) {
    if( Polys[a] != undefined ) {

        if ( LayerPoly[ Polys[a].properties['n'] ] == undefined ) {
            LayerPoly[ Polys[a].properties['n'] ] = [];
            OutLayerPoly[ Polys[a].properties['n'] ] = [];
        }

        if ( Polys[a].geometry.type == 'Polygon' || Polys[a].geometry.type == 'MultiPolygon') {
            // console.log( "Poly has " + Polys[a].geometry.coordinates[0].length + " points")
            // Polys[a].geometry = turf.simplify( Polys[a].geometry, {tolerance: 5, highQuality: false, mutate: true}) ;
            // Polys[a].geometry = clean( Polys[a].geometry) ;
            // console.log( "    Simplified Poly has " + Polys[a].geometry.coordinates[0].length + " points")

            LayerPoly[ Polys[a].properties['n'] ].push( Polys[a] );

        }

        //OutPolys.push(Polys[a]);
    }
}


var combinedPolys = {};
// Combine layers of polygons
var baseFeature = {};

Object.keys( LayerPoly ).forEach(function(key) {
    console.log( "Strength Layer " + key + " : " + LayerPoly[key].length + " original polys" );
    baseFeature = LayerPoly[key][0];
    baseFeature.srcPolyCount = 0;


        for (var a = 1, len = LayerPoly[key].length; a < len; a++) {
            process.stdout.write("   Merging Polygon " + a + " of " + LayerPoly[key].length + "...\r");

            baseFeature = turf.union(baseFeature, LayerPoly[key][a]);

            baseFeature.srcPolyCount++;
        }


    //baseFeature.geometry = turf.simplify( baseFeature.geometry, {tolerance: 0.01, highQuality: false, mutate: true}) ;
    baseFeature.geometry = clean( baseFeature.geometry) ;
    console.log( "    New feature contains " + baseFeature.geometry.coordinates.length + " polys" );
    console.log(" ");
    combinedPolys[key] = baseFeature;
});





// // Simplify Layers
// for (var a = 0, len = 40; a < len; a++) {
//     if (combinedPolys[a]) {
//         console.log("Simplifying Layer " + a);
//
//         combinedPolys[a] = turf.simplify( combinedPolys[a], {tolerance: 0.01, highQuality: false, mutate: true})
//     }
// }


// Remove overlaps
for (var a = 0, len = 40; a < len; a++) {
    for (var b = a+1, len = 40; b < len; b++) {

        if (combinedPolys[a]) {
            if (combinedPolys[b]) {
                process.stdout.write("Subtracting Strength " + (b) + " from Strength " + a + "\r");
                combinedPolys[a] = turf.difference(combinedPolys[a], combinedPolys[b]);
            }
        }
    }
}
process.stdout.write("\n\n");
// generate output

Object.keys( combinedPolys ).forEach(function(key) {

    OutPolys.push(combinedPolys[key]);

});




var output = FileA;
if ( ! output ) {
    output = FileB;
}
output.features = OutPolys;

var outputStr = JSON.stringify(output);

fs.writeFile(sourceDir + siteCode + "/"+ siteCode +"-ALL-" + inputGain + ".json", outputStr, 'utf8', function (err) {
    if (err) {
        return console.log(err);
    }

    console.log("The file was saved!    "+ sourceDir + siteCode + "/"+ siteCode +"-ALL-" + inputGain + ".json");
});