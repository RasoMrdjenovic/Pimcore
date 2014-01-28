<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName HEADER
 * @var HEADER
 */
class HEADER
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName GENERATOR_INFO
	 * @var GENERATOR_INFO
	 */
	public $GENERATOR_INFO;
	/**
	 * @xmlType element
	 * @xmlName CATALOG
	 * @var CATALOG
	 */
	public $CATALOG;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName BUYER_IDREF
	 * @var BUYER_IDREF
	 */
	public $BUYER_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName BUYER
	 * @var BUYER
	 */
	public $BUYER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName AGREEMENT
	 * @var AGREEMENT[]
	 */
	public $AGREEMENT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName LEGAL_INFO
	 * @var LEGAL_INFO
	 */
	public $LEGAL_INFO;
	/**
	 * @xmlType element
	 * @xmlName SUPPLIER_IDREF
	 * @var SUPPLIER_IDREF
	 */
	public $SUPPLIER_IDREF;
	/**
	 * @xmlType element
	 * @xmlName SUPPLIER
	 * @var SUPPLIER
	 */
	public $SUPPLIER;
	/**
	 * @xmlType element
	 * @xmlName DOCUMENT_CREATOR_IDREF
	 * @var DOCUMENT_CREATOR_IDREF
	 */
	public $DOCUMENT_CREATOR_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PARTIES
	 * @var PARTIES
	 */
	public $PARTIES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AREAS
	 * @var AREAS
	 */
	public $AREAS;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlName USER_DEFINED_EXTENSIONS
	 * @var org\bmecat\www\bmecat\_2005fd\udxHEADER
	 */
	public $USER_DEFINED_EXTENSIONS;


} // end class HEADER
