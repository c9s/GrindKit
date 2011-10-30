<?php

namespace GrindKit;
use SplFileInfo;

class GrindFile extends SplFileInfo
{
    private $meta;

    /*
     * Parse meta info from cachegrind file.
     *
     * file meta sample:
        version: 1
        creator: xdebug 2.1.2
        cmd: /Users/c9s/git/Work/phifty/webroot/index.php
        part: 1
        positions: line
     */
    public function getMeta()
    {
        if( $this->meta )
            return $this->meta;

        $meta = array();
        $fh = fopen($this->getRealPath(),'r');
        for( $i = 0 ; $i < 6 ; $i++ ) {
            $line = fgets($fh);
            if( preg_match( '/^(\w+):\s*(.*)$/' , $line , $reg ) ) {
                $meta[ $reg[1] ] = $reg[2];
            }
        }
        fclose($fh);
        return $this->meta = (object) $meta;
    }

}


