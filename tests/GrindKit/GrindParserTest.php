<?php
/*
 * This file is part of the {{ }} package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
use GrindKit\GrindFile;
use GrindKit\GrindParser;

class GrindParserTest extends PHPUnit_Framework_TestCase
{

    function testParser()
    {
        $file = new GrindFile( 'tests/data/cachegrind.out.4039.1319688314' );
        $this->assertNotEmpty( $file );

        $parser = new GrindParser( $file );
        $this->assertNotEmpty( $parser );

        $result = $parser->parse();

        $this->assertTrue( is_a($result,'GrindKit\GrindParserResult') ); 
        $this->assertNotEmpty( $result ); 
        $this->assertNotEmpty( $result->summary ); 
        $this->assertNotEmpty( $result->functions ); 

        # $result->dumpCalls();
        # ob_flush();

        $tree = $result->getExecutionTree();
        $this->assertNotEmpty( $tree ); 
        # $result->dumpExecutionTree();
        # ob_flush();
    }

}
