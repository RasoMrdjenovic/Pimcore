<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName FT_GROUP
 * @var FT_GROUP
 */
class FT_GROUP
	{

	
	/**
	 * @xmlType element
	 * @xmlName FT_GROUP_ID
	 * @var FT_GROUP_ID
	 */
	public $FT_GROUP_ID;
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
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_GROUP_DESCR
	 * @var FT_GROUP_DESCR[]
	 */
	public $FT_GROUP_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FT_GROUP_PARENT_ID
	 * @var FT_GROUP_PARENT_ID[]
	 */
	public $FT_GROUP_PARENT_ID;


} // end class FT_GROUP
