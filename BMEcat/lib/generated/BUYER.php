<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName BUYER
 * @var BUYER
 */
class BUYER
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName BUYER_ID
	 * @var BUYER_ID
	 */
	public $BUYER_ID;
	/**
	 * @xmlType element
	 * @xmlName BUYER_NAME
	 * @var BUYER_NAME
	 */
	public $BUYER_NAME;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlName ADDRESS
	 */
	public $ADDRESS;


} // end class BUYER
