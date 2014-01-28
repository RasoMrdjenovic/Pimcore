<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName typeTIME_SPAN
 * @var typeTIME_SPAN
 */
class typeTIME_SPAN
	{

	
	/**
	 * @xmlType element
	 * @xmlName TIME_BASE
	 * @var TIME_BASE
	 */
	public $TIME_BASE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TIME_VALUE_DURATION
	 * @var TIME_VALUE_DURATION
	 */
	public $TIME_VALUE_DURATION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TIME_VALUE_INTERVAL
	 * @var TIME_VALUE_INTERVAL
	 */
	public $TIME_VALUE_INTERVAL;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TIME_VALUE_START
	 * @var TIME_VALUE_START
	 */
	public $TIME_VALUE_START;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TIME_VALUE_END
	 * @var TIME_VALUE_END
	 */
	public $TIME_VALUE_END;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName SUB_TIME_SPANS
	 * @var SUB_TIME_SPANS[]
	 */
	public $SUB_TIME_SPANS;


} // end class typeTIME_SPAN
