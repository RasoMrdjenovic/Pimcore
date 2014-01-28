<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CUSTOMS_TARIFF_NUMBER
 * @var CUSTOMS_TARIFF_NUMBER
 */
class CUSTOMS_TARIFF_NUMBER
	{

	
	/**
	 * @xmlType element
	 * @xmlName CUSTOMS_NUMBER
	 * @var CUSTOMS_NUMBER
	 */
	public $CUSTOMS_NUMBER;
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


} // end class CUSTOMS_TARIFF_NUMBER
