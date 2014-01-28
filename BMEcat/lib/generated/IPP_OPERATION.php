<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_OPERATION
 * @var IPP_OPERATION
 */
class IPP_OPERATION
	{

	
	/**
	 * @xmlType element
	 * @xmlName IPP_OPERATION_ID
	 * @var IPP_OPERATION_ID
	 */
	public $IPP_OPERATION_ID;
	/**
	 * @xmlType element
	 * @xmlName IPP_OPERATION_TYPE
	 * @var IPP_OPERATION_TYPE
	 */
	public $IPP_OPERATION_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_OPERATION_DESCR
	 * @var IPP_OPERATION_DESCR[]
	 */
	public $IPP_OPERATION_DESCR;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_OUTBOUND
	 * @var IPP_OUTBOUND[]
	 */
	public $IPP_OUTBOUND;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_INBOUND
	 * @var IPP_INBOUND[]
	 */
	public $IPP_INBOUND;


} // end class IPP_OPERATION
