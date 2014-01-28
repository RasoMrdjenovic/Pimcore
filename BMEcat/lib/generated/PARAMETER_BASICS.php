<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PARAMETER_BASICS
 * @var PARAMETER_BASICS
 */
class PARAMETER_BASICS
	{

	
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName PARAMETER_NAME
	 * @var PARAMETER_NAME[]
	 */
	public $PARAMETER_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PARAMETER_DESCR
	 * @var PARAMETER_DESCR[]
	 */
	public $PARAMETER_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PARAMETER_UNIT
	 * @var PARAMETER_UNIT[]
	 */
	public $PARAMETER_UNIT;


} // end class PARAMETER_BASICS
