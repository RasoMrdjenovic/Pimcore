<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PRODUCT_PRICE
 * @var PRODUCT_PRICE
 */
class PRODUCT_PRICE
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRICE_AMOUNT
	 * @var PRICE_AMOUNT
	 */
	public $PRICE_AMOUNT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRICE_FORMULA
	 * @var PRICE_FORMULA
	 */
	public $PRICE_FORMULA;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRICE_CURRENCY
	 * @var PRICE_CURRENCY
	 */
	public $PRICE_CURRENCY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TAX_DETAILS
	 * @var TAX_DETAILS[]
	 */
	public $TAX_DETAILS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TAX
	 * @var TAX
	 */
	public $TAX;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRICE_FACTOR
	 * @var PRICE_FACTOR
	 */
	public $PRICE_FACTOR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName LOWER_BOUND
	 * @var LOWER_BOUND
	 */
	public $LOWER_BOUND;
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
	 * @xmlMinOccurs 0
	 * @xmlName PRICE_BASE
	 * @var PRICE_BASE
	 */
	public $PRICE_BASE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRICE_FLAG
	 * @var PRICE_FLAG[]
	 */
	public $PRICE_FLAG;
	/**
	 * @xmlType attribute
	 * @xmlName price_type
	 * @var typePRICE_TYPE
	 */
	public $price_type;


} // end class PRODUCT_PRICE
