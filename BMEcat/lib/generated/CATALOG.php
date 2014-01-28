<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CATALOG
 * @var CATALOG
 */
class CATALOG
	{

	
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName LANGUAGE
	 * @var LANGUAGE[]
	 */
	public $LANGUAGE;
	/**
	 * @xmlType element
	 * @xmlName CATALOG_ID
	 * @var CATALOG_ID
	 */
	public $CATALOG_ID;
	/**
	 * @xmlType element
	 * @xmlName CATALOG_VERSION
	 * @var CATALOG_VERSION
	 */
	public $CATALOG_VERSION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CATALOG_NAME
	 * @var CATALOG_NAME[]
	 */
	public $CATALOG_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName GENERATION_DATE
	 * @var GENERATION_DATE
	 */
	public $GENERATION_DATE;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlName DATETIME
	 */
	public $DATETIME;
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
	 * @xmlName CURRENCY
	 * @var CURRENCY
	 */
	public $CURRENCY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MIME_ROOT
	 * @var MIME_ROOT[]
	 */
	public $MIME_ROOT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRICE_FLAG
	 * @var PRICE_FLAG[]
	 */
	public $PRICE_FLAG;
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
	 * @xmlName VALID_START_DATE
	 * @var VALID_START_DATE
	 */
	public $VALID_START_DATE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName VALID_END_DATE
	 * @var VALID_END_DATE
	 */
	public $VALID_END_DATE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_TYPE
	 * @var PRODUCT_TYPE
	 */
	public $PRODUCT_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName COUNTRY_OF_ORIGIN
	 * @var COUNTRY_OF_ORIGIN
	 */
	public $COUNTRY_OF_ORIGIN;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName DELIVERY_TIMES
	 * @var DELIVERY_TIMES[]
	 */
	public $DELIVERY_TIMES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TRANSPORT
	 * @var TRANSPORT
	 */
	public $TRANSPORT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName SUPPLIER_IDREF
	 * @var SUPPLIER_IDREF
	 */
	public $SUPPLIER_IDREF;
        

} // end class CATALOG
