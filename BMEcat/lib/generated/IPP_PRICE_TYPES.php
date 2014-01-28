<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_PRICE_TYPES
 * @var IPP_PRICE_TYPES
 */
class IPP_PRICE_TYPES
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRICE_TYPE
	 * @var PRICE_TYPE[]
	 */
	public $PRICE_TYPE;
	/**
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_PRICE_TYPES
