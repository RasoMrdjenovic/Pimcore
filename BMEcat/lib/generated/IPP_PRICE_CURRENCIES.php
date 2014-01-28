<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_PRICE_CURRENCIES
 * @var IPP_PRICE_CURRENCIES
 */
class IPP_PRICE_CURRENCIES
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRICE_CURRENCY
	 * @var PRICE_CURRENCY[]
	 */
	public $PRICE_CURRENCY;
	/**
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_PRICE_CURRENCIES
