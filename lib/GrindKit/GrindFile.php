<?php

namespace GrindKit;
use SplFileInfo;

class GrindFile extends SplFileInfo
{
    private $meta;

    /*
     * Parse meta info from cachegrind file.
     *
        Header Lines:

        version: 1
        creator: xdebug 2.1.2
        cmd: /Users/c9s/git/Work/phifty/webroot/index.php
        part: 1
        positions: line
        pid:


        Header Spec

        version: number [Callgrind]
            This is used to distinguish future profile data file formats. A 
            version of 1 is supposed to be upwards compatible with the 
            Cachegrind format. Optional. If not given, defaults to version 1. 
            Otherwise it has to be the first header line.

        creator: string [Callgrind]
            Information about the creator of this file, i.e. the profile tool 
            or conversion script. Optional.

        pid: process id [Callgrind]
            This specifies the process ID of the supervised application for 
            which this profile was generated. Optional.

        cmd: program name + args [Cachegrind]
            This specifies the full command line of the supervised application 
            for which this profile was generated. Optional.

        part: number [Callgrind]
            This specifies a sequentially running number for profile dumps 
            belonging to the same profile run of an application, starting at 1. 
            Optional.

        desc: type: value [Cachegrind]
            This specifies various information for this dump. For some types, 
            the semantic is defined, but any description type is allowed. 
            Unknown types are ignored.

     */
    public function getMeta()
    {
        if( $this->meta )
            return $this->meta;

        $meta = array();
        $fh = fopen($this->getRealPath(),'r');
        while( $line = fgets($fh) ) {
            $line = trim( $line );
            if( ! trim($line) )
                break;

            if( preg_match( '/^(\w+):\s*(.*)$/' , $line , $reg ) ) {
                $meta[ $reg[1] ] = $reg[2];
            }
        }
        fclose($fh);
        return $this->meta = (object) $meta;
    }

    public function getPid()
    {
        return $this->getMeta()->pid;
    }

    public function getDesc()
    {
        return $this->getMeta()->desc;
    }

    public function getPart()
    {
        return $this->getMeta()->part;
    }

    public function getVersion()
    {
        return $this->getMeta()->version;
    }

    public function getCmd()
    {
        return $this->getMeta()->cmd;
    }


}


