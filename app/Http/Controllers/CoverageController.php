<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Imagick;
use ImagickDraw;
use ImagickPixel;
class CoverageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        //
        $path = getcwd() . "/projections/";
        $files =  scandir($path);

        $result = array();
        foreach ( $files as $file ) {
            if ( $file == "." ) { continue; }
            if ( $file == ".." ) { continue; }
            if ( $file == "CachedProjections" ) { continue; }
//
            if ( is_dir( $path . $file ) ) {
////print $path . $file . "/" . $file . "-000-000.extents";
////print file_get_contents( $path . $file . "/" . $file . "-000-000.extents");
                list($x,$n,$e,$s,$w) = explode("|",file_get_contents( $path . $file . "/" . $file . "-000-000.extents"));
////
                $result[$file] = array(
                    'NAME' => $file,
                    'n' => $n, 'e' => $e, 's' => $s, 'w' => $w
                );
            }
//
       }

       return $result;
    }


    public function getJSON( $site = 'BGM', $direction = '120', $clientGain = '010')
    {
        $file = "$site-$direction-$clientGain.json";
        $data = file_get_contents(realpath("projections/$site/$site-$direction-$clientGain.json"));

        header('Cache-Control: max-age=3600');
        header('Content-Type: image/png');
        header('Content-Encoding: gzip');

        return gzencode( $data,9 );
    }
    public function getImage( $site = 'BGM', $direction = '120', $clientGain = '010') {


        $speed = request('speed');
        if (! $speed ) { $speed = 1;
        }
        $cacheName = "$site-$direction-$clientGain.png";
 //TODO: Add caching
        //print "projections/$site/$site-$direction-$clientGain.png";
        if ( strtoupper($direction) == 'ALL' || strtoupper($direction) == 'OMNI' ) {
            $directions = array('000', '120', '240');
        } elseif ( strpos($direction,".") >= 1 ) {
            $directions = explode(".",$direction);
        } else {
            $directions = array($direction);
        }


        // Generate CacheID
        $cacheID =  "$site-$clientGain-";
        foreach ($directions as $dir) {
            if ( file_exists(realpath("projections/$site/$site-$dir-$clientGain.png") ) ) {
                $cacheID .=  realpath("projections/$site/$site-$dir-$clientGain.png") . "-";
                //$cacheID .=  filemtime(realpath("projections/$site/$site-$dir-$clientGain.png")) . "-";
            }
        }

        if ( file_exists(realpath("projections/CachedProjections/$speed$site-" . md5($cacheID) . "-nq8.png")) && !request('refresh') ) {
            header('Cache-Control: max-age=3600');
            header('Content-Type: image/png');

            print file_get_contents(realpath("projections/CachedProjections/$speed$site-" . md5($cacheID) . "-nq8.png"));
            return ;
        }

            $images = array();

        $masks = array();

        $output = new Imagick();
        $output->newimage( 5035, 3238, "none");
        $output->setImageMatte(1);

        foreach ($directions as $dir) {
            $image = new \Imagick();
            if ( file_exists(realpath("projections/$site/$site-$dir-$clientGain.png") )) {
                $image->readImage(realpath("projections/$site/$site-$dir-$clientGain.png"));
                $image->setImageMatte(1);
            } else {
                continue;
            }

            $mask = new \Imagick();
            $mask->readImage(realpath("projections/$dir" . "DegreeMask.png"));
            $image->setImageMatte(1);
            $image->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);

            $output->compositeimage($image->getimage(), Imagick::COMPOSITE_DEFAULT, 0, 0);

        }


        $overlay = new \Imagick();
        $overlay->readImage(realpath("projections/SectorOverlay.png"));
        $output->compositeimage($overlay->getimage(), Imagick::COMPOSITE_DEFAULT, 0, 0);

        foreach ($directions as $dir) {
            //$imageOut->compositeImage($images[ $dir ]->getImage(), Imagick::COMPOSITE_COPY, 0, 0);

        }

        $output->setImageFormat("PNG");
        //$output->setImageDepth(8);
        $output->stripImage();

        $output->scaleImage(5035/$speed, 3238/$speed);

        //$output->setCompressionQuality("90");
        //$output->scaleImage(500,500,true);
        $output->writeImage( ("projections/CachedProjections/$speed$site-" . md5($cacheID) . ".png") );
        //print $output->getImageBlob();

        shell_exec("/usr/bin/pngnq -n 24 -f  " . realpath("projections/CachedProjections/$speed$site-" . md5($cacheID) . ".png") );
        unlink(realpath("projections/CachedProjections/$speed$site-" . md5($cacheID) . ".png"));
        header('Cache-Control: max-age=3600');
        header('Content-Type: image/png');
        print file_get_contents(realpath("projections/CachedProjections/$speed$site-" . md5($cacheID) . "-nq8.png"));
        //return false;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
