<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_OUTBOUND_PARAMS
 * @var IPP_OUTBOUND_PARAMS
 */
class IPP_OUTBOUND_PARAMS
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_LANGUAGES
	 * @var IPP_LANGUAGES
	 */
	public $IPP_LANGUAGES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_TERRITORIES
	 * @var IPP_TERRITORIES
	 */
	public $IPP_TERRITORIES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_PRICE_CURRENCIES
	 * @var IPP_PRICE_CURRENCIES
	 */
	public $IPP_PRICE_CURRENCIES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_PRICE_TYPES
	 * @var IPP_PRICE_TYPES
	 */
	public $IPP_PRICE_TYPES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_SUPPLIER_PID
	 * @var IPP_SUPPLIER_PID
	 */
	public $IPP_SUPPLIER_PID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_PRODUCTCONFIG_IDREF
	 * @var IPP_PRODUCTCONFIG_IDREF
	 */
	public $IPP_PRODUCTCONFIG_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_PRODUCTLIST_IDREF
	 * @var IPP_PRODUCTLIST_IDREF
	 */
	public $IPP_PRODUCTLIST_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_USER_INFO
	 * @var IPP_USER_INFO
	 */
	public $IPP_USER_INFO;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_AUTHENTIFICATION_INFO
	 * @var IPP_AUTHENTIFICATION_INFO
	 */
	public $IPP_AUTHENTIFICATION_INFO;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_PARAM_DEFINITION
	 * @var IPP_PARAM_DEFINITION[]
	 */
	public $IPP_PARAM_DEFINITION;


} // end class IPP_OUTBOUND_PARAMS
