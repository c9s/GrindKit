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

class GrindParserResult 
{
    /* function invocation data , parsed from cachegrind file. */
    public $functions = array();

    /* function invocation summary data, which is calculated */
    public $summary = array();

    /* cachegrind file headers */
    public $headers = array();


}

