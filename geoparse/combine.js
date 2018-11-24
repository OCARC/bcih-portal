//var GeoJSON = require('./geojson');
var turf = require('turf');
console.log(process.cwd());

if ( process.cwd() == "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/geoparse") {

    var FileA_path = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-000-" + process.argv[3] + ".json";
    var FileB_path = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-120-" + process.argv[3] + ".json";
    var FileC_path = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-240-" + process.argv[3] + ".json";
    var OutFile_path = "/mnt/c/Users/steph/PhpstormProjects/hamwan/bcih-portal/public/projections/" + "LAB" + "/" + "LAB" + "-000-" + process.argv[3] + ".json";
} else {

    var FileA_path = "/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-000-" + process.argv[3] + ".json";
    var FileB_path = "/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-120-" + process.argv[3] + ".json";
    var FileC_path = "/hamwan/bcih-portal/public/projections/" + process.argv[2] + "/" + process.argv[2] + "-240-" + process.argv[3] + ".json";
    var OutFile_path = "/hamwan/bcih-portal/public/projections/" + "LAB" + "/" + "LAB" + "-000-" + process.argv[3] + ".json";
}
var fs = require("fs");
console.log("\n *START* \n");
var Polys = [];
var OutPolys = [];



if (fs.existsSync(FileA_path)) {

    var FileA_content = fs.readFileSync(FileA_path);
    var FileA = JSON.parse(FileA_content);
    FileA.features.forEach(function(featureA) {
        //featureA.properties['src'] = "LMK-000-030";
        Polys.push( featureA )
    });
}
if (fs.existsSync(FileB_path)) {

    var FileB_content = fs.readFileSync(FileB_path);
    var FileB = JSON.parse(FileB_content);
    FileB.features.forEach(function(featureB) {
        //featureB.properties['src'] = "LMK-120-030";

        Polys.push( featureB )
    });
}
if (fs.existsSync(FileC_path)) {

    var FileC_content = fs.readFileSync(FileC_path);
    var FileC = JSON.parse(FileC_content);

    FileC.features.forEach(function(featureC) {
        //featureB.properties['src'] = "LMK-240-030";

        Polys.push( featureC )
    });
}


updated_at

var PolyCount = Polys.length;

for (var a = 0, len = Polys.length; a < len; a++) {
    if ( Polys[a] == undefined ) {
        continue;
    }
    if ( Polys[a].properties.n < 1 ) {
        console.log( '[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Poly Under Threshold, Skipping, Removing Poly A');
        PolyCount = PolyCount -1;
        delete Polys[a];
        continue;
    }
    for (var b = 0, len = Polys.length; b < len; b++) {
        if ( Polys[a] == undefined ) {
            console.log( '[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Poly A Consumed Skipping Remaining Comparisons');
            break;
        }
        if ( Polys[b] == undefined ) {
            continue;
        }
        if ( Polys[b].properties.n < 1 ) {
            console.log( '[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Poly Under Threshold, Skipping, Removing Poly B');
            PolyCount = PolyCount -1;
            delete Polys[b];

            continue;
        }


        // Handle when we cross over the same polygon
         if (Polys[a] == Polys[b]) {
            continue;
        }
        //console.log(Polys[a]);
        //console.log(Polys[b]);


        // Get the overlapping poly
        var intersection = turf.intersect(Polys[a], Polys[b]);
        if ( intersection ) {

            // Inherit the properties from the better polygon
            if ( Polys[a].properties.n >= Polys[b].properties.n ) {
                intersection.properties = Polys[a].properties;
            } else {
                intersection.properties = Polys[b].properties;
            }

            if ( intersection.geometry.type == 'Polygon' ) {


                OutPolys.push(intersection);
                //console.log(intersection);
                console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Creating Combined Polygon');

                // Find remaining Polygon A
                var diffA = turf.difference(Polys[a], intersection);

                if ( diffA ) {
                    // Make sure we inherit the original properties
                    diffA.properties = Polys[a].properties;
                    if ( diffA.geometry.type == 'Polygon' ) {
                        Polys[a] = diffA;
                        //OutPolys.push(diffA);
                        //console.log(diffA);
                        console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polys Found @ A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length);

                    } else {
                        console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polygon A Was Consumed');
                        PolyCount--;
                        delete Polys[a];
                    }


                } else {
                    console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polygon A Was Consumed');
                    PolyCount--;
                    delete Polys[a];
                }

                // Find remaining Polygon B
                var diffB = turf.difference(Polys[b], intersection);
                if ( diffB ) {
                    // Make sure we inherit the original properties
                    diffB.properties = Polys[b].properties;

                    if ( diffB.geometry.type == 'Polygon' ) {
                        Polys[b] = diffB;
                        //OutPolys.push(diffB);
                        //console.log(diffB);
                        console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polys Found @ A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length);
                    } else {
                        PolyCount--;
                        delete Polys[b];
                        console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polygon B Was Consumed');

                    }

                } else {
                    PolyCount--;
                    delete Polys[b];
                    console.log('[Count: ' + PolyCount + ' A:' + a + '/' + Polys.length + ' B:' + b + '/' + Polys.length+']   Polygon B Was Consumed');

                }
            }
        }


}

}


for (var a = 0, len = Polys.length; a < len; a++) {
    if ( Polys[a] != undefined ) {
        OutPolys.push(Polys[a]);
    }
}
OutPolysUnique = OutPolys;
// OutPolysUnique = OutPolys.filter(function(elem, pos) {
//     return OutPolys.indexOf(elem) == pos;
// })

var output = FileA;
output.features = OutPolysUnique;
var outputStr = JSON.stringify(output);

fs.writeFile(OutFile_path, outputStr, 'utf8', function (err) {
    if (err) {
        return console.log(err);
    }

    console.log("The file was saved!");
});


console.log( FileA );
console.log("\n *EXIT* \n");
console.log('asd');
