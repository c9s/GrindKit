<?php
require_once('src/GrindKit/GrindKit.php');
require_once('src/GrindKit/GrindFile.php');

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

    public function testFile()
    {
        $gfile = new GrindKit\GrindFile( 'tests/data/cachegrind.out.4039.1319688314' );
        $this->assertNotEmpty( $gfile );

    }
}

