<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CONFIG_PARTS
 * @var CONFIG_PARTS
 */
class CONFIG_PARTS
	{

	
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName PART_ALTERNATIVE
	 * @var PART_ALTERNATIVE[]
	 */
	public $PART_ALTERNATIVE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PART_SELECTION_TYPE
	 * @var PART_SELECTION_TYPE
	 */
	public $PART_SELECTION_TYPE;


} // end class CONFIG_PARTS
