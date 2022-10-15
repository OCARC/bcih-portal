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

var siteCodeArg = process.argv[3];

var sites = { };
var futureSites = {};


if ( siteCodeArg == 'ALL') {
    sites['LMK'] = ['ALL'];
    sites['BGM'] = ['ALL'];
    sites['BKM'] = ['ALL'];
    sites['KUI'] = ['ALL'];
    sites['TUR'] = ['ALL'];
} else {
    if ( siteCodeArg == "BKM") {
        sites[ siteCodeArg ] = ['000','240'];
        futureSites[ siteCodeArg ] = ['120'];
    }  else if ( siteCodeArg == "TUR") {
        sites[siteCodeArg] = [];
        futureSites[siteCodeArg] = ['000', '120', '240'];

    } else {
        sites[siteCodeArg] = ['000', '120', '240'];
    }
}

var sectors = ['000','120','240'];


var Polygons = [];
var Levels = {};

/**
 * Load Polys from files
 */
var InputFile;

// Loop over sites
for (var siteCode in sites) {

    // Loop over Sectors
    for (var sectorID = 0, sectorCount = sites[siteCode].length; sectorID  < sectorCount; sectorID ++) {
        var sector = sites[siteCode][sectorID];
        process.stdout.write("*** Preparing to load polygons for " + siteCode + " sector " + sector + " ***\n" );

        process.stdout.write("\tTrying open " + sourceDir + siteCode + "/" + siteCode + "-" + sector + "-" + inputGain + ".json" + "...\n" );
        if (fs.existsSync(sourceDir + siteCode + "/" + siteCode + "-" + sector + "-" + inputGain + ".json")) {
            process.stdout.write("\tParsing polygons from " + sourceDir + siteCode + "/" + siteCode + "-" + sector + "-" + inputGain + ".json" + "...\n");
            var InputFile_content = fs.readFileSync(sourceDir + siteCode + "/" + siteCode + "-" + sector + "-" + inputGain + ".json");

            InputFile = JSON.parse(InputFile_content);
            if ( ! InputFile['features'] ) {
                process.stdout.write("\tFailed No Features!\n\n");
                continue;
            }
            // Process the features
            for (var featureID = 0, len = InputFile.features.length; featureID < len; featureID++) {
                var feature = InputFile.features[featureID];
                var level = feature.properties.n;

                //feature.properties.sites = [{'code' : siteCode, 'level' : level}];

                process.stdout.write("\tImported " + (featureID + 0) + ' polygons...\r');
                if ( ! Levels[ level ] ) {
                    Levels[ level ] = [];
                }

                Levels[ level ].push(InputFile.features[featureID]);
            }

            process.stdout.write("\n\n" );

        }
    }
}


// Loop over levels and merge touching polygons
for (var levelID = 1, levelCount = Object.keys(Levels).length; levelID  <= levelCount; levelID ++) {
    console.log('Merging in Level ' + levelID + " (" + Levels[levelID].length + " features )                        ");

    for (var featureID = 0, len = Levels[levelID].length; featureID < len; featureID++) {
        var feature = Levels[levelID][featureID];
        if( feature == null ) { continue; }
        for (var featureID2 = featureID, len = Levels[levelID].length; featureID2 < len; featureID2++) {
            var feature2 = Levels[levelID][featureID2];
            if( feature == null ) { continue; }
            if( feature2 == null ) { continue; }
            if( featureID == featureID2 ) { continue; }
           // if ( turf.intersect(feature, feature2) ) {
                merged = turf.union(feature, feature2);
                if (merged.geometry.type == 'Polygon') {
                    process.stdout.write('\tMerged polygons ' + featureID + " and " + featureID2 + " of " + Levels[levelID].length + "\r");
                    Levels[levelID].push(merged);

                        feature = null;
                    feature2 = null;
                    Levels[levelID][featureID] = feature;
                    Levels[levelID][featureID2] = feature2;


                }
           // }
        }
    }
}
// // Loop over Levels and deduplicate polygons
// for (var levelID = 1, levelCount = Object.keys(Levels).length; levelID  <= levelCount; levelID ++) {
//     console.log('Removing Overlaps in Level ' + levelID );
//
//     for (var featureID = 0, len = Levels[levelID].length; featureID < len; featureID++) {
//         var feature = Levels[levelID][featureID];
//         if( feature == null ) { continue; }
//         if ( feature.type != 'Feature') { console.log('Deleted None Feature'); CurLevel[featureID] == null; continue;}
//
//         for (var featureID2 = featureID, len = Levels[levelID].length; featureID2 < len; featureID2++) {
//if( featureID == featureID2 ) { continue; }
//             var feature2 = Levels[levelID][featureID2];
//             if( feature2 == null ) { continue; }
//
//
//             process.stdout.write("\tComparing " + featureID + ' with ' +  featureID2 + ' of ' + Levels[levelID].length + '...\r');
//
//             //
//             // console.log( JSON.stringify(feature));
//             // console.log( JSON.stringify(feature2));
//
//             if ( turf.booleanOverlap(feature, feature2) ) {
//
//                 newFeature = turf.difference(feature, feature2);
//                 newFeature2 = turf.difference(feature2, feature);
//
//
//                 if ( newFeature.geometry.type == 'MultiPolygon') {
//
//                     for (var splitID = 0, splitLen = newFeature.geometry.coordinates.length; splitID < splitLen; splitID++) {
//                         var appendFeature = {
//                             "type": "Feature",
//                             "properties": newFeature.properties,
//                             "geometry": {
//                                 "type": "Polygon",
//                                 "coordinates":
//                                     newFeature.geometry.coordinates[splitID]
//
//                             }
//                         }
//                         console.log( '\n\t\tCreated New Polygon From MultiPolygon');
//                         Levels[levelID].push( appendFeature );
//                     }
//
//                         newFeature = null;
//                 }
//
//                 if ( newFeature2.geometry.type == 'MultiPolygon') {
//                     for (var splitID = 0, splitLen = newFeature2.geometry.coordinates.length; splitID < splitLen; splitID++) {
//                         var appendFeature = {
//                             "type": "Feature",
//                             "properties": newFeature2.properties,
//                             "geometry": {
//                                 "type": "Polygon",
//                                 "coordinates":
//                                     newFeature2.geometry.coordinates[splitID]
//
//                             }
//                         }
//                         console.log( '\n\t\tCreated New Polygon From MultiPolygon');
//                         Levels[levelID].push( appendFeature );
//                     }
//
//                     newFeature2 = null;
//                 }
//                 Levels[levelID][featureID] = newFeature;
//                 Levels[levelID][featureID2] = newFeature2;
//             }
//         }
//     }
// }





// Create new file

var outputFile = InputFile;

outputFile.features = [];
var Features = [];
for (var levelID = 1, levelCount = Object.keys(Levels).length; levelID  <= levelCount; levelID ++) {
    console.log('Writing Level ' + levelID );
    var CurLevel = Levels[levelID];
    for (var featureID = 0, len = CurLevel.length; featureID < len; featureID++) {

        var feature = CurLevel[featureID];
        if( feature == null ) { continue; }

        Features.push(feature);
    }
}

// Remove overlaps
for (var featureID = 0, len = Features.length; featureID < len; featureID++) {

    for (var featureID2 = 0, len = Features.length; featureID2 < len; featureID2++) {
        if (featureID == featureID2) {
            continue;
        }

        var feature = Features[featureID];
        var feature2 = Features[featureID2];

        if (feature == null || feature2 == null) {
            continue;
        }


        //if ( turf.booleanOverlap(feature, feature2) ) {
        //if ( turf.intersect(feature, feature2) ) {

        if (feature.properties['n'] >= feature2.properties['n']) {

            Features[featureID2] = turf.difference(feature2, feature)
            if ( Features[featureID2] != feature2 ) {
                console.log('Feature ' + featureID + " overlaps " + featureID2 + " trimming...                        ");

            }
        } else {
            Features[featureID] = turf.difference(feature, feature2)
            if ( Features[featureID] != feature ) {
                console.log('Feature ' + featureID + " overlaps " + featureID2 + " trimming...                        ");

            }
        }
        //}
    //}

        Features[featureID] = feature;
        Features[featureID2] = feature2;
    }

}

// Remove Nulls
for (var featureID = 0, len = Features.length; featureID < len; featureID++) {
    if (Features[featureID] != null ) {
        outputFile.features.push( Features[featureID] )
    }
}

var outputStr = JSON.stringify(outputFile);

console.log("Writing " + sourceDir + siteCode + "/"+ siteCode +"-ALL-" + inputGain + ".json");
fs.writeFile(sourceDir + siteCode + "/"+ siteCode +"-ALL-" + inputGain + ".json", outputStr, 'utf8', function (err) {
    if (err) {
        return console.log(err);
    }

    console.log("The file was saved!    "+ sourceDir + siteCode + "/"+ siteCode +"-ALL-" + inputGain + ".json");
});

return


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
                if ( featureA.properties.n >= 1 ) {

                    if (featureA.properties.n <= 10) {
                        featureA.properties['n-actual'] = featureA.properties['n'];
                        featureA.properties['n'] = 1;
                    }
                }
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
                featureA.properties['n-future'] = featureA.properties['n'];
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