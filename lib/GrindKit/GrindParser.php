<?php

namespace GrindKit;

use GrindKit\GrindFile;
use GrindKit\GrindKit;

class GrindParser {

    public $file;

    function __construct( GrindFile $file ) 
    {
        $this->file = $file;
    }

    function parse()
    {
        # XXX: borrow from webgrind, Refactor this!!!!!

        $filePath = $this->file->getRealPath();
		$in = @fopen( $filePath , 'rb');
		if(!$in)
			throw new Exception('Could not open ' . $filePath . ' for reading.');
		
		$nextFuncNr = 0;
		$functions = array();
		$headers = array();
		$calls = array();
		
		// Read information into memory
		while(($line = fgets($in))){
			if(substr($line,0,3)==='fl='){
				// Found invocation of function. Read functionname
				list($function) = fscanf($in,"fn=%[^\n\r]s");
				if(!isset($functions[$function])){
					$functions[$function] = array(
                        'filename'              => substr(trim($line),3), 
                        'invocationCount'       => 0,
                        'nr'                    => $nextFuncNr++,
                        'count'                 => 0,
                        'summedSelfCost'        => 0,
                        'summedInclusiveCost'   => 0,
                        'calledFromInformation' => array(),
                        'subCallInformation'    => array()
					);
				} 
				$functions[$function]['invocationCount']++;
				// Special case for ENTRY_POINT - it contains summary header
				if(self::ENTRY_POINT == $function){
					fgets($in);				
					$headers[] = fgets($in);
					fgets($in);
				}
				// Cost line
				list($lnr, $cost) = fscanf($in,"%d %d");
				$functions[$function]['summedSelfCost'] += $cost;
				$functions[$function]['summedInclusiveCost'] += $cost;				
			} else if(substr($line,0,4)==='cfn=') {
				
				// Found call to function. ($function should contain function call originates from)
				$calledFunctionName = substr(trim($line),4);
				// Skip call line
				fgets($in);
				// Cost line
				list($lnr, $cost) = fscanf($in,"%d %d");
				
				$functions[$function]['summedInclusiveCost'] += $cost;
				
				if(!isset($functions[$calledFunctionName]['calledFromInformation'][$function.':'.$lnr]))
					$functions[$calledFunctionName]['calledFromInformation'][$function.':'.$lnr] = array('functionNr'=>$functions[$function]['nr'],'line'=>$lnr,'callCount'=>0,'summedCallCost'=>0);
				
				$functions[$calledFunctionName]['calledFromInformation'][$function.':'.$lnr]['callCount']++;
				$functions[$calledFunctionName]['calledFromInformation'][$function.':'.$lnr]['summedCallCost'] += $cost;

				if(!isset($functions[$function]['subCallInformation'][$calledFunctionName.':'.$lnr])){
					$functions[$function]['subCallInformation'][$calledFunctionName.':'.$lnr] = array('functionNr'=>$functions[$calledFunctionName]['nr'],'line'=>$lnr,'callCount'=>0,'summedCallCost'=>0);
				}
				
				$functions[$function]['subCallInformation'][$calledFunctionName.':'.$lnr]['callCount']++;
				$functions[$function]['subCallInformation'][$calledFunctionName.':'.$lnr]['summedCallCost'] += $cost;
				
				
			} else if(strpos($line,': ')!==false){
				// Found header
				$headers[] = $line;
			}
		}
    }

}


