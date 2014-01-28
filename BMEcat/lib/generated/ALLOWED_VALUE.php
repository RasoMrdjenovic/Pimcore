<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ALLOWED_VALUE
 * @var ALLOWED_VALUE
 */
class ALLOWED_VALUE
	{

	
	/**
	 * @xmlType element
	 * @xmlName ALLOWED_VALUE_ID
	 * @var ALLOWED_VALUE_ID
	 */
	public $ALLOWED_VALUE_ID;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName ALLOWED_VALUE_NAME
	 * @var ALLOWED_VALUE_NAME[]
	 */
	public $ALLOWED_VALUE_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName ALLOWED_VALUE_VERSION
	 * @var ALLOWED_VALUE_VERSION
	 */
	public $ALLOWED_VALUE_VERSION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ALLOWED_VALUE_SHORTNAME
	 * @var ALLOWED_VALUE_SHORTNAME[]
	 */
	public $ALLOWED_VALUE_SHORTNAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ALLOWED_VALUE_DESCR
	 * @var ALLOWED_VALUE_DESCR[]
	 */
	public $ALLOWED_VALUE_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName ALLOWED_VALUE_SYNONYMS
	 * @var ALLOWED_VALUE_SYNONYMS
	 */
	public $ALLOWED_VALUE_SYNONYMS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName ALLOWED_VALUE_SOURCE
	 * @var ALLOWED_VALUE_SOURCE
	 */
	public $ALLOWED_VALUE_SOURCE;


} // end class ALLOWED_VALUE
