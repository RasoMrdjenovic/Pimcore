<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName FORMULA
 * @var FORMULA
 */
class FORMULA
	{

	
	/**
	 * @xmlType element
	 * @xmlName FORMULA_ID
	 * @var FORMULA_ID
	 */
	public $FORMULA_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORMULA_VERSION
	 * @var FORMULA_VERSION
	 */
	public $FORMULA_VERSION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FORMULA_NAME
	 * @var FORMULA_NAME[]
	 */
	public $FORMULA_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FORMULA_DESCR
	 * @var FORMULA_DESCR[]
	 */
	public $FORMULA_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORMULA_SOURCE
	 * @var FORMULA_SOURCE
	 */
	public $FORMULA_SOURCE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORMULA_FUNCTION
	 * @var FORMULA_FUNCTION
	 */
	public $FORMULA_FUNCTION;
	/**
	 * @xmlType element
	 * @xmlName PARAMETER_DEFINITIONS
	 * @var PARAMETER_DEFINITIONS
	 */
	public $PARAMETER_DEFINITIONS;


} // end class FORMULA
