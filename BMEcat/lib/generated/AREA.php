<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AREA
 * @var AREA
 */
class AREA
	{

	
	/**
	 * @xmlType element
	 * @xmlName AREA_ID
	 * @var AREA_ID
	 */
	public $AREA_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName AREA_NAME
	 * @var AREA_NAME[]
	 */
	public $AREA_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName AREA_DESCR
	 * @var AREA_DESCR[]
	 */
	public $AREA_DESCR;
	/**
	 * @xmlType element
	 * @xmlName TERRITORIES
	 * @var TERRITORIES
	 */
	public $TERRITORIES;


} // end class AREA
