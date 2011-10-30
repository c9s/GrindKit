<?php

namespace GrindKit;
use DirectoryIterator;
use Exception;
use SplFileInfo;

class GrindKit 
{

    function __construct()
    {
        if( ! extension_loaded('xdebug') ) {
            throw new Exception('xdebug extension is required.');
        }

    }


    /*
     * scan xdebug profiler output dir
     *
     * @param $directory   cachegrind output dir.
     * @param $prefix      cachegrind file prefix.
     *
     * @return cachegrind files
     */
    function scanDir( $directory = null , $prefix = 'cachegrind.out.' ) 
    {
        // xdebug.profiler_output_dir = '/tmp/var'
        // xdebug.profiler_output_name	
        // xdebug.trace_output_dir
        if( ! $directory )
            $directory = ini_get('xdebug.profiler_output_dir');

#          $profiler_filename = null;
#  		if (function_exists('xdebug_get_profiler_filename'))
#  		    $profiler_filename = realpath(xdebug_get_profiler_filename());

        $files = array();
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile()) {
                if( strpos( $fileinfo->getFilename() , $prefix ) === 0 ) {
                    $files[] = new SplFileInfo( $fileinfo );
                    // $files[] = array( 'filepath' => $fileinfo->getFilePath() );
                }
            }
        }
        return $files;
    }
}


