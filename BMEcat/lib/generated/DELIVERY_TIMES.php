<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName DELIVERY_TIMES
 * @var DELIVERY_TIMES
 */
class DELIVERY_TIMES
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TERRITORY
	 * @var TERRITORY[]
	 */
	public $TERRITORY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AREA_REFS
	 * @var AREA_REFS
	 */
	public $AREA_REFS;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName TIME_SPAN
	 * @var TIME_SPAN[]
	 */
	public $TIME_SPAN;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName LEADTIME
	 * @var LEADTIME
	 */
	public $LEADTIME;


} // end class DELIVERY_TIMES
