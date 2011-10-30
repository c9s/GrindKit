<?php
require_once('lib/GrindKit/GrindKit.php');

class GrindKitTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $grind = new GrindKit\GrindKit;
        $this->assertNotEmpty( $grind );

        $files = $grind->scanDir();
        $this->assertNotEmpty( $files );

        foreach( $files as $file ) {
            // var_dump( $file->getFilename() ); 
        }
        # var_dump( $files ); 
    }
}


