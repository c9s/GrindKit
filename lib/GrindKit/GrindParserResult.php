<?php
/*
 * This file is part of the GrindKit package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace GrindKit;
use Exception;

class GrindParserResult 
{
    /* function invocation list, parsed from cachegrind file. */
    public $functions = array();
	public $functionMapping = array();

    /* function invocation summary data, which is calculated */
    public $summary = array();

    /* cachegrind file headers */
    public $headers = array();

	function addCall( & $func )
	{
		$this->functions[] = $func;
		$this->functionMapping[ $func['function'] ] = $func;
		$this->calculateInvocationSummary( $func );
	}

    /* Calcualte Invocation summary for functions 
     * */
    function calculateInvocationSummary($funcdata)
    {
        $funcname = $funcdata['function'];

        // if the function exists 
        if( isset( $this->summary[ $funcname ] ) ) {
            // calculate here...
            $this->summary[ $funcname ]['cost'] += (int) $funcdata['self_cost'];

            if( isset($funcdata['invocation_count'] ) )
                $this->summary[ $funcname ]['invocation_count'] += (int) $funcdata['invocation_count'];
        } else {
            $this->summary[ $funcname ] = array();
            $this->summary[ $funcname ]['cost'] = (int) $funcdata['self_cost'];

            if( isset($funcdata['invocation_count'] ) )
                $this->summary[ $funcname ]['invocation_count'] = (int) $funcdata['invocation_count'];
        }
    }

	function getCall($name)
	{
		if( isset($this->functionMapping[ $name ] ) ) {
			return $this->functionMapping[ $name ];
		}
	}

	function listCalls()
	{
		foreach( $this->functions as $func ) {
			if( isset($func['called_from'] ) )
				echo "\t";
			printf("%s [Line %d]: %s\n", $func['filename'],  $func['line'] , $func['function'] );
		}
	}

	/* return tree structure result */
	function getTree()
	{
		$entrypoint = '{main}';
		$main = $this->getCall($entrypoint);
		if( ! $main )
			throw new Exception( 'Entrypoint {{main}} not found.' );

		// get entrypoint function
		// if( isset( $this->functions )
	}

}

