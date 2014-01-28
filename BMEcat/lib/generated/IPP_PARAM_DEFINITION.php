<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_PARAM_DEFINITION
 * @var IPP_PARAM_DEFINITION
 */
class IPP_PARAM_DEFINITION
	{

	
	/**
	 * @xmlType element
	 * @xmlName IPP_PARAM_NAME
	 * @var IPP_PARAM_NAME
	 */
	public $IPP_PARAM_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName IPP_PARAM_DESCR
	 * @var IPP_PARAM_DESCR[]
	 */
	public $IPP_PARAM_DESCR;
	/**
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_PARAM_DEFINITION
