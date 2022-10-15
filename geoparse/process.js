var turf = require('turf');
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

// Find all of the files we are going to work

var Polys = [];
var OutPolys = [];
var FileA;
var FileB;
// Try to load polys


for (var key in sites) {
    for (var b = 0, len = sites[key].length; b < len; b++) {

        if (fs.existsSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json")) {

            console.log("Loading polygons for " + key + " - " + sites[key][b] + "...");
            FileA_content = fs.readFileSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json");
            FileA = JSON.parse(FileA_content);
            FileA.features.forEach(function (featureA) {
                //featureA.properties['src'] = "LMK-000-030";

                //console.log( featureASimplified );
                Polys.push( featureA );
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
                    featureA.properties['n'] = 0;
                    Polys.push(featureA)
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

        if ( Polys[a].geometry.type == 'Polygon') {
            LayerPoly[ Polys[a].properties['n'] ].push( Polys[a] );
        }

        //OutPolys.push(Polys[a]);
    }
}


var combinedPolys = {};
// Combine layers of polygons
var baseFeature = {};


Object.keys( LayerPoly ).forEach(function(key) {
    console.log( "    Strength Layer " + key + " : " + LayerPoly[key].length + " original polys" );
    baseFeature = LayerPoly[key][0];
    baseFeature.srcPolyCount = 0;

    for (var a = 1, len = LayerPoly[key].length; a < len; a++) {
        baseFeature = turf.union( baseFeature, LayerPoly[key][a] );
        console.log(baseFeature);
        process.stdout.write("   Optimizing Feature " + key + " against " + a + "...\r");
        baseFeature.srcPolyCount++;
    }
    console.log( "       New feature contains " + baseFeature.geometry.coordinates.length + " polys" );
    console.log(" ");
    combinedPolys[ key ] = baseFeature;
//    OutPolys.push(baseFeature);
});


// Remove overlaps
for (var a = 0, len = 40; a < len; a++) {
    for (var b = a+1, len = 40; b < len; b++) {

        if (combinedPolys[a]) {
            if (combinedPolys[b]) {
                console.log("Subtracting Strength " + (b) + " from Strength " + a);
                combinedPolys[a] = turf.difference(combinedPolys[a], combinedPolys[b]);
            }
        }
    }
}

// generate output

Object.keys( combinedPolys ).forEach(function(key) {

    OutPolys.push(combinedPolys[key]);

});




var output = FileA;
output.features = OutPolys;
var outputStr = JSON.stringify(output);

fs.writeFile(sourceDir + "LAB" + "/LAB-000-" + inputGain + ".json", outputStr, 'utf8', function (err) {
    if (err) {
        return console.log(err);
    }

    console.log("The file was saved!");
});