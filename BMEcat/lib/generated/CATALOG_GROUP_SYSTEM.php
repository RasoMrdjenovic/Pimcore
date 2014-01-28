<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CATALOG_GROUP_SYSTEM
 * @var CATALOG_GROUP_SYSTEM
 */
class CATALOG_GROUP_SYSTEM
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName GROUP_SYSTEM_ID
	 * @var GROUP_SYSTEM_ID
	 */
	public $GROUP_SYSTEM_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName GROUP_SYSTEM_NAME
	 * @var GROUP_SYSTEM_NAME[]
	 */
	public $GROUP_SYSTEM_NAME;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName CATALOG_STRUCTURE
	 * @var CATALOG_STRUCTURE[]
	 */
	public $CATALOG_STRUCTURE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName GROUP_SYSTEM_DESCRIPTION
	 * @var GROUP_SYSTEM_DESCRIPTION[]
	 */
	public $GROUP_SYSTEM_DESCRIPTION;


} // end class CATALOG_GROUP_SYSTEM
