<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_OUTBOUND
 * @var IPP_OUTBOUND
 */
class IPP_OUTBOUND
	{

	
	/**
	 * @xmlType element
	 * @xmlName IPP_OUTBOUND_FORMAT
	 * @var IPP_OUTBOUND_FORMAT
	 */
	public $IPP_OUTBOUND_FORMAT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_OUTBOUND_PARAMS
	 * @var IPP_OUTBOUND_PARAMS
	 */
	public $IPP_OUTBOUND_PARAMS;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_URI
	 * @var IPP_URI[]
	 */
	public $IPP_URI;


} // end class IPP_OUTBOUND
