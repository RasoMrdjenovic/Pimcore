<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PRODUCT_ORDER_DETAILS
 * @var PRODUCT_ORDER_DETAILS
 */
class PRODUCT_ORDER_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlName ORDER_UNIT
	 * @var ORDER_UNIT
	 */
	public $ORDER_UNIT;
	/**
	 * @xmlType element
	 * @xmlName CONTENT_UNIT
	 * @var CONTENT_UNIT
	 */
	public $CONTENT_UNIT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName NO_CU_PER_OU
	 * @var NO_CU_PER_OU
	 */
	public $NO_CU_PER_OU;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
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
	 * @xmlName PRICE_QUANTITY
	 * @var PRICE_QUANTITY
	 */
	public $PRICE_QUANTITY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName QUANTITY_MIN
	 * @var QUANTITY_MIN
	 */
	public $QUANTITY_MIN;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName QUANTITY_INTERVAL
	 * @var QUANTITY_INTERVAL
	 */
	public $QUANTITY_INTERVAL;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName QUANTITY_MAX
	 * @var QUANTITY_MAX
	 */
	public $QUANTITY_MAX;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PACKING_UNITS
	 * @var PACKING_UNITS
	 */
	public $PACKING_UNITS;


} // end class PRODUCT_ORDER_DETAILS
