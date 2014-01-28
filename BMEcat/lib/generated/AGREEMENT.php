<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AGREEMENT
 * @var AGREEMENT
 */
class AGREEMENT
	{

	
	/**
	 * @xmlType element
	 * @xmlName AGREEMENT_ID
	 * @var AGREEMENT_ID
	 */
	public $AGREEMENT_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AGREEMENT_LINE_ID
	 * @var AGREEMENT_LINE_ID
	 */
	public $AGREEMENT_LINE_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AGREEMENT_START_DATE
	 * @var AGREEMENT_START_DATE
	 */
	public $AGREEMENT_START_DATE;
	/**
	 * @xmlType element
	 * @xmlName AGREEMENT_END_DATE
	 * @var AGREEMENT_END_DATE
	 */
	public $AGREEMENT_END_DATE;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMaxOccurs 2
	 * @xmlName DATETIME
	 */
	public $DATETIME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName SUPPLIER_IDREF
	 * @var SUPPLIER_IDREF[2]
	 */
	public $SUPPLIER_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AGREEMENT_DESCR
	 * @var AGREEMENT_DESCR
	 */
	public $AGREEMENT_DESCR;
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
	 * @xmlName default
	 * @var dtBOOLEAN
	 */
	public $default;


} // end class AGREEMENT
