<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PARAMETER_DEFINITION
 * @var PARAMETER_DEFINITION
 */
class PARAMETER_DEFINITION
	{

	
	/**
	 * @xmlType element
	 * @xmlName PARAMETER_SYMBOL
	 * @var PARAMETER_SYMBOL
	 */
	public $PARAMETER_SYMBOL;
	/**
	 * @xmlType element
	 * @xmlName PARAMETER_BASICS
	 * @var PARAMETER_BASICS
	 */
	public $PARAMETER_BASICS;
	/**
	 * @xmlType element
	 * @xmlName FREF
	 * @var FREF
	 */
	public $FREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PARAMETER_ORIGIN
	 * @var PARAMETER_ORIGIN
	 */
	public $PARAMETER_ORIGIN;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PARAMETER_DEFAULT_VALUE
	 * @var PARAMETER_DEFAULT_VALUE
	 */
	public $PARAMETER_DEFAULT_VALUE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PARAMETER_MEANING
	 * @var PARAMETER_MEANING
	 */
	public $PARAMETER_MEANING;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PARAMETER_ORDER
	 * @var PARAMETER_ORDER
	 */
	public $PARAMETER_ORDER;


} // end class PARAMETER_DEFINITION
