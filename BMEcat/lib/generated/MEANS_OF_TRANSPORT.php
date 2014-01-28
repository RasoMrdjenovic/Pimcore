<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName MEANS_OF_TRANSPORT
 * @var MEANS_OF_TRANSPORT
 */
class MEANS_OF_TRANSPORT
	{

	
	/**
	 * @xmlType element
	 * @xmlName MEANS_OF_TRANSPORT_ID
	 * @var MEANS_OF_TRANSPORT_ID
	 */
	public $MEANS_OF_TRANSPORT_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MEANS_OF_TRANSPORT_NAME
	 * @var MEANS_OF_TRANSPORT_NAME[]
	 */
	public $MEANS_OF_TRANSPORT_NAME;
	/**
	 * @xmlType attribute
	 * @xmlName type
	 */
	public $type;


} // end class MEANS_OF_TRANSPORT
