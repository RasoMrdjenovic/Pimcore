<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP
 * @var IPP
 */
class IPP
	{

	
	/**
	 * @xmlType element
	 * @xmlName IPP_IDREF
	 * @var IPP_IDREF
	 */
	public $IPP_IDREF;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_OPERATION_IDREF
	 * @var IPP_OPERATION_IDREF[]
	 */
	public $IPP_OPERATION_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_RESPONSE_TIME
	 * @var IPP_RESPONSE_TIME
	 */
	public $IPP_RESPONSE_TIME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_URI
	 * @var IPP_URI[]
	 */
	public $IPP_URI;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_PARAM
	 * @var IPP_PARAM[]
	 */
	public $IPP_PARAM;


} // end class IPP
