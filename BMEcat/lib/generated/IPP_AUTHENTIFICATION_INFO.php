<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_AUTHENTIFICATION_INFO
 * @var IPP_AUTHENTIFICATION_INFO
 */
class IPP_AUTHENTIFICATION_INFO
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName AUTHENTIFICATION
	 * @var AUTHENTIFICATION[]
	 */
	public $AUTHENTIFICATION;
	/**
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_AUTHENTIFICATION_INFO
