<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ARTICLE_REFERENCE
 * @var ARTICLE_REFERENCE
 */
class ARTICLE_REFERENCE
	{

	
	/**
	 * @xmlType element
	 * @xmlName ART_ID_TO
	 * @var ART_ID_TO
	 */
	public $ART_ID_TO;
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


} // end class ARTICLE_REFERENCE
