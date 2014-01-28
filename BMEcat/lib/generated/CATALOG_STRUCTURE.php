<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CATALOG_STRUCTURE
 * @var CATALOG_STRUCTURE
 */
class CATALOG_STRUCTURE
	{

	
	/**
	 * @xmlType element
	 * @xmlName GROUP_ID
	 * @var GROUP_ID
	 */
	public $GROUP_ID;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName GROUP_NAME
	 * @var GROUP_NAME[]
	 */
	public $GROUP_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName GROUP_DESCRIPTION
	 * @var GROUP_DESCRIPTION[]
	 */
	public $GROUP_DESCRIPTION;
	/**
	 * @xmlType element
	 * @xmlName PARENT_ID
	 * @var PARENT_ID
	 */
	public $PARENT_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName GROUP_ORDER
	 * @var GROUP_ORDER
	 */
	public $GROUP_ORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlName USER_DEFINED_EXTENSIONS
	 * @var org\bmecat\www\bmecat\_2005fd\udxCATALOGGROUP
	 */
	public $USER_DEFINED_EXTENSIONS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName KEYWORD
	 * @var KEYWORD[]
	 */
	public $KEYWORD;
	/**
	 * @xmlType attribute
	 * @xmlName type
	 */
	public $type;


} // end class CATALOG_STRUCTURE
