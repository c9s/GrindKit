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

class Node 
{
    public $childs = array();
    public $parents = array();
    public $data;

    function __construct( $data = null )
    {
        $this->data = $data;
    }

    function getName()
    {
        return $this->data['function'];
    }

    function addChild($child)
    {
        if( is_array($child) ) 
            $child = new Node( $child );
        if( ! $this->isConnected( $child ) )
            $this->childs[] = $child;
    }

    function isConnected($newChild)
    {
        foreach( $this->childs as $child ) {
            return $child->getName() == $newChild->getName();
        }
    }

    function addParent( $parent )
    {
        $this->parents[] = $parent;
    }


}
