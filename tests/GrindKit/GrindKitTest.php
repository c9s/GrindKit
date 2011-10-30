<?php
require_once('lib/GrindKit/GrindKit.php');
require_once('lib/GrindKit/GrindFile.php');

class GrindKitTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $grind = new GrindKit\GrindKit;
        $this->assertNotEmpty( $grind );

        $files = $grind->scanDir();
        $this->assertNotEmpty( $files );

        $firstFile = $files[0];

        $this->assertNotEmpty( $firstFile );

        $meta = $firstFile->getMeta();

        # var_dump( $files ); 
    }
}


