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
use GrindKit\Node;

class GrindParserResult 
{
    /* function invocation list, parsed from cachegrind file. */
    public $functions = array();
	public $functionMapping = array();

    /* function invocation summary data, which is calculated */
    public $summary = array();

    /* cachegrind file headers */
    public $headers = array();

	private $id = 0;

	function addCall( $func )
	{
		$func['identity'] = $this->id ++;
		$node = new Node($func);
		$this->functions[] = $node; // append to function list
		$this->functionMapping[ $func['function'] ] = $node; // quick mapping
		$this->calculateInvocationSummary( $node );
		return $node;
	}

    /* Calcualte Invocation summary for functions 
     * */
    function calculateInvocationSummary($node)
    {
		$funcdata = & $node->data;
        $funcname = $node->data['function'];

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

	public function getCalledFrom($name)
	{
		$list = array();
		foreach( $this->functions as $func ) {
			if( isset($func['called_from']) && $func['called_from'] === $name ) {
				$list[] = $func;
			}
		}
		return $list;
	}

	public function getCall($name)
	{
		if( isset($this->functionMapping[ $name ] ) ) {
			return $this->functionMapping[ $name ];
		}
	}

	function dumpCalls()
	{
		foreach( $this->functions as $func ) {
			if( isset($func['called_from'] ) )
				echo "\t";
			printf("%s [Line %d]: %s\n", $func['filename'],  $func['line'] , $func['function'] );
		}
	}

	private function dump($node,$level = 0)
	{
		echo str_repeat('  ', $level);
		printf( "-> %s \n" , $node->getName() );
		foreach( $node->childs as $child ) {
			$this->dump( $child, $level + 1);
		}
	}

	public function dumpExecutionTree()
	{
		$tree = $this->getExecutionTree();
		$this->dump( $tree , 0 );
	}

	/* return tree structure result */
	public function getExecutionTree()
	{
		$root = new Node;
		foreach( $this->functions as $node ) 
		{
			# echo $call['identity'] . "\n";
			# ob_flush();
			# $this->getSubcalls($node);
			$root->addChild( $node );
		}
		return $root;
	}

	public function getSubcalls($node)
	{
		// get all called from
		$subcalls = $this->getCalledFrom( $node->getName() );
		foreach( $subcalls as $call ) { // $call = array
			$child = new Node($call);
			$node->addChild( $child );
		}
	}
}

