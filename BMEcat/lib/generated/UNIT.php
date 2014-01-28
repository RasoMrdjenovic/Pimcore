<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName UNIT
 * @var UNIT
 */
class UNIT
	{

	
	/**
	 * @xmlType element
	 * @xmlName UNIT_ID
	 * @var UNIT_ID
	 */
	public $UNIT_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName UNIT_NAME
	 * @var UNIT_NAME[]
	 */
	public $UNIT_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName UNIT_SHORTNAME
	 * @var UNIT_SHORTNAME[]
	 */
	public $UNIT_SHORTNAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName UNIT_DESCR
	 * @var UNIT_DESCR[]
	 */
	public $UNIT_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName UNIT_CODE
	 * @var UNIT_CODE
	 */
	public $UNIT_CODE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName UNIT_URI
	 * @var UNIT_URI
	 */
	public $UNIT_URI;
	/**
	 * @xmlType attribute
	 * @xmlName system
	 */
	public $system;


} // end class UNIT
