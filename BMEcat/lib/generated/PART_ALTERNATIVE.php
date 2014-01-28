<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PART_ALTERNATIVE
 * @var PART_ALTERNATIVE
 */
class PART_ALTERNATIVE
	{

	
	/**
	 * @xmlType element
	 * @xmlName SUPPLIER_PIDREF
	 * @var SUPPLIER_PIDREF
	 */
	public $SUPPLIER_PIDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName SUPPLIER_IDREF
	 * @var SUPPLIER_IDREF
	 */
	public $SUPPLIER_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_ORDER
	 * @var PRODUCT_ORDER
	 */
	public $PRODUCT_ORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName DEFAULT_FLAG
	 * @var DEFAULT_FLAG
	 */
	public $DEFAULT_FLAG;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CONFIG_CODE
	 * @var CONFIG_CODE
	 */
	public $CONFIG_CODE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_PRICE_DETAILS
	 * @var PRODUCT_PRICE_DETAILS
	 */
	public $PRODUCT_PRICE_DETAILS;


} // end class PART_ALTERNATIVE
