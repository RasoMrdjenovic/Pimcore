<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName SUPPLIER
 * @var SUPPLIER
 */
class SUPPLIER
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName SUPPLIER_ID
	 * @var SUPPLIER_ID[]
	 */
	public $SUPPLIER_ID;
	/**
	 * @xmlType element
	 * @xmlName SUPPLIER_NAME
	 * @var SUPPLIER_NAME
	 */
	public $SUPPLIER_NAME;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlName ADDRESS
	 */
	public $ADDRESS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;


} // end class SUPPLIER
