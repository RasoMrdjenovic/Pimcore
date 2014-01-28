<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PRODUCT_REFERENCE
 * @var PRODUCT_REFERENCE
 */
class PRODUCT_REFERENCE
	{

	
	/**
	 * @xmlType element
	 * @xmlName PROD_ID_TO
	 * @var PROD_ID_TO
	 */
	public $PROD_ID_TO;
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
	 * @xmlName CATALOG_ID
	 * @var CATALOG_ID
	 */
	public $CATALOG_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CATALOG_VERSION
	 * @var CATALOG_VERSION
	 */
	public $CATALOG_VERSION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName REFERENCE_DESCR
	 * @var REFERENCE_DESCR[]
	 */
	public $REFERENCE_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;
	/**
	 * @xmlType attribute
	 * @xmlName type
	 */
	public $type;
	/**
	 * @xmlType attribute
	 * @xmlName quantity
	 * @var dtINTEGER
	 */
	public $quantity;


} // end class PRODUCT_REFERENCE
