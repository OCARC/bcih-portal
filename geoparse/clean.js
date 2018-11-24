var turf = require('turf');
var fs = require("fs");

if ( process.cwd() == "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/geoparse") {
    var sourceDir = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/";

} else{
    var sourceDir = "/hamwan/bcih-portal/public/projections/";

}

var inputGain = process.argv[2];

var sites = {'LAB' : ['000']}
var sectors = ['000','120','240'];

// Find all of the files we are going to work

var Polys = [];
var OutPolys = [];
var FileA;
// Try to load polys

for (var key in sites) {
    for (var b = 0, len = sites[key].length; b < len; b++) {

        if (fs.existsSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json")) {

            console.log("Loading polygons for " + key + " - " + sites[key][b] + "...");
            FileA_content = fs.readFileSync(sourceDir + key + "/" + key + "-" + sites[key][b] + "-" + inputGain + ".json");
            FileA = JSON.parse(FileA_content);
            FileA.features.forEach(function (featureA) {
                //featureA.properties['src'] = "LMK-000-030";
                Polys.push(featureA)
            });
        }
    }
}

console.log("Loaded " + Polys.length + " polygons...");


// Loop over and compare polygons
for (var a = 0, len = Polys.length; a < len; a++) {
    if ( Polys[a] == undefined) { continue; }
    for (var b = 0, len = Polys.length; b < len; b++) {
        var pre = "[" + a + ":" + b + "]   ";
        if (Polys[a] == undefined) {
            break;
        }
        if (Polys[b] == undefined) {
            continue;
        }

        // Handle when we cross over the same polygon
        if (Polys[a] == Polys[b]) {
            continue;
        }


        // Does PolyA and PloyB intersect
        var intersection = turf.intersect(Polys[a], Polys[b]);
        if (intersection) {
            if (intersection.geometry.type == 'MultiLineString' ) {
                continue;

            }
            if (intersection.geometry.type == 'LineString' ) {
                continue;

            }
            if (intersection.geometry.type == 'MultiPoint' ) {
                continue;

            }
            if (intersection.geometry.type == 'Point' ) {
                continue;

            }

                if ( intersection.geometry.type == 'Polygon' || intersection.geometry.type == "GeometryCollection" ) {
                if ( intersection.geometry.type == "GeometryCollection" ) {
                    // Remove all but polys
                    intersection = featureClean(intersection, 'Polygon');
                    if (intersection.geometry.geometries.length == 0 ) {
                        continue;

                    }
                }

                console.log(pre + "Created Poly, PolyA and PolyB Intersect");
                OutPolys.push(intersection);
                // Determine what color it should be
                if ( Polys[a].properties.n >= Polys[b].properties.n ) {
                    intersection.properties = Polys[a].properties;
                } else {
                    intersection.properties = Polys[b].properties;
                }
                intersection.properties.overlap = true;
                // Whats Left Over

                    var diffA = turf.difference(Polys[a], intersection);
                if (diffA) {
                    if ( diffA.geometry.type == 'Polygon' ) {
                        diffA.properties = Polys[b].properties;
                        diffA.properties.cut = true;

                        console.log(pre + "New polygon subtracted from PolyA");
                        Polys[a] == diffA;
                    } else {
                        delete Polys[a];
                        console.log(pre + "New polygon subtracted from PolyA but it did not form a polygon PolyA was consumed");
                    }

                } else {
                    delete Polys[a];
                    console.log(pre + "PolyA was consumed");
                }
                    console.log( JSON.stringify(Polys[b]) );

                var diffB = turf.difference(Polys[b], intersection);
                if (diffB) {
                    if ( diffB.geometry.type == 'Polygon' ) {
                        diffB.properties = Polys[b].properties;
                        diffB.properties.cut = true;

                        console.log(pre + "New polygon subtracted from PolyB");
                        Polys[b] == diffB;
                    } else {
                        delete Polys[b]
                        console.log(pre + "New polygon subtracted from PolyB but it did not form a polygon PolyB was consumed");
                    }
                } else {
                    delete Polys[b]
                    console.log(pre + "PolyB was consumed");

                }
            } else {
                console.log( "Intersection Produced an Unknown Geometry Type : " + intersection.geometry.type );
                console.log( JSON.stringify(intersection) );

            }

    }




    }
    
}


for (var a = 0, len = Polys.length; a < len; a++) {
    if( Polys[a] != undefined ) {
        OutPolys.push(Polys[a]);
    }
}

var output = FileA;
output.features = OutPolys;
var outputStr = JSON.stringify(output);

fs.writeFile(sourceDir + "LAB" + "/LAB-000-" + inputGain + ".json", outputStr, 'utf8', function (err) {
    if (err) {
        return console.log(err);
    }

    console.log("The file was saved!");
});


function featureClean( data, type ) {


    var out = JSON.parse(JSON.stringify(data));
    out.geometry.geometries = [];

    data.geometry.geometries.forEach( function( g ) {
        if ( g.type == type ) {
            out.geometry.geometries.push( g );
        }
    });

    return out;
}