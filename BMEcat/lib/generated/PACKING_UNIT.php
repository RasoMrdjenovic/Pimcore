<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PACKING_UNIT
 * @var PACKING_UNIT
 */
class PACKING_UNIT
	{

	
	/**
	 * @xmlType element
	 * @xmlName QUANTITY_MIN
	 * @var QUANTITY_MIN
	 */
	public $QUANTITY_MIN;
	/**
	 * @xmlType element
	 * @xmlName QUANTITY_MAX
	 * @var QUANTITY_MAX
	 */
	public $QUANTITY_MAX;
	/**
	 * @xmlType element
	 * @xmlName PACKING_UNIT_CODE
	 * @var PACKING_UNIT_CODE
	 */
	public $PACKING_UNIT_CODE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PACKING_UNIT_DESCR
	 * @var PACKING_UNIT_DESCR[]
	 */
	public $PACKING_UNIT_DESCR;
	/**
	 * @xmlType element
	 * @xmlName SUPPLIER_PID
	 * @var SUPPLIER_PID
	 */
	public $SUPPLIER_PID;
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


} // end class PACKING_UNIT
