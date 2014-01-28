<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_TERRITORIES
 * @var IPP_TERRITORIES
 */
class IPP_TERRITORIES
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
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_TERRITORIES
