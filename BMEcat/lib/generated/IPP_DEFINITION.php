<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_DEFINITION
 * @var IPP_DEFINITION
 */
class IPP_DEFINITION
	{

	
	/**
	 * @xmlType element
	 * @xmlName IPP_ID
	 * @var IPP_ID
	 */
	public $IPP_ID;
	/**
	 * @xmlType element
	 * @xmlName IPP_TYPE
	 * @var IPP_TYPE
	 */
	public $IPP_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_OPERATOR_IDREF
	 * @var IPP_OPERATOR_IDREF
	 */
	public $IPP_OPERATOR_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_DESCR
	 * @var IPP_DESCR[]
	 */
	public $IPP_DESCR;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_OPERATION
	 * @var IPP_OPERATION[]
	 */
	public $IPP_OPERATION;


} // end class IPP_DEFINITION
