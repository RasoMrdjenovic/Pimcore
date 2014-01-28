<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PREDEFINED_CONFIG
 * @var PREDEFINED_CONFIG
 */
class PREDEFINED_CONFIG
	{

	
	/**
	 * @xmlType element
	 * @xmlName PREDEFINED_CONFIG_CODE
	 * @var PREDEFINED_CONFIG_CODE
	 */
	public $PREDEFINED_CONFIG_CODE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PREDEFINED_CONFIG_NAME
	 * @var PREDEFINED_CONFIG_NAME[]
	 */
	public $PREDEFINED_CONFIG_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PREDEFINED_CONFIG_DESCR
	 * @var PREDEFINED_CONFIG_DESCR[]
	 */
	public $PREDEFINED_CONFIG_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PREDEFINED_CONFIG_ORDER
	 * @var PREDEFINED_CONFIG_ORDER
	 */
	public $PREDEFINED_CONFIG_ORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_PRICE_DETAILS
	 * @var PRODUCT_PRICE_DETAILS
	 */
	public $PRODUCT_PRICE_DETAILS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName SUPPLIER_PID
	 * @var SUPPLIER_PID
	 */
	public $SUPPLIER_PID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName INTERNATIONAL_PID
	 * @var INTERNATIONAL_PID[]
	 */
	public $INTERNATIONAL_PID;


} // end class PREDEFINED_CONFIG
