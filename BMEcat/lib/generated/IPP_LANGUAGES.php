<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName IPP_LANGUAGES
 * @var IPP_LANGUAGES
 */
class IPP_LANGUAGES
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName LANGUAGE
	 * @var LANGUAGE[]
	 */
	public $LANGUAGE;
	/**
	 * @xmlType attribute
	 * @xmlName occurence
	 * @var typeIPP_occurence
	 */
	public $occurence;


} // end class IPP_LANGUAGES
