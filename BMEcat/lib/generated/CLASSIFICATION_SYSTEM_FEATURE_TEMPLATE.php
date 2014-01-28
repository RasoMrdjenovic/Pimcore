<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CLASSIFICATION_SYSTEM_FEATURE_TEMPLATE
 * @var CLASSIFICATION_SYSTEM_FEATURE_TEMPLATE
 */
class CLASSIFICATION_SYSTEM_FEATURE_TEMPLATE
	{

	
	/**
	 * @xmlType element
	 * @xmlName FT_ID
	 * @var FT_ID
	 */
	public $FT_ID;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_NAME
	 * @var FT_NAME[]
	 */
	public $FT_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_SHORTNAME
	 * @var FT_SHORTNAME[]
	 */
	public $FT_SHORTNAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_DESCR
	 * @var FT_DESCR[]
	 */
	public $FT_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FT_VERSION
	 * @var FT_VERSION
	 */
	public $FT_VERSION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FT_GROUP_IDREF
	 * @var FT_GROUP_IDREF
	 */
	public $FT_GROUP_IDREF;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_GROUP_NAME
	 * @var FT_GROUP_NAME[]
	 */
	public $FT_GROUP_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FT_DEPENDENCIES
	 * @var FT_DEPENDENCIES
	 */
	public $FT_DEPENDENCIES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FEATURE_CONTENT
	 * @var FEATURE_CONTENT
	 */
	public $FEATURE_CONTENT;


} // end class CLASSIFICATION_SYSTEM_FEATURE_TEMPLATE
