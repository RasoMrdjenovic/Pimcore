<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName TRANSPORT
 * @var TRANSPORT
 */
class TRANSPORT
	{

	
	/**
	 * @xmlType element
	 * @xmlName INCOTERM
	 * @var INCOTERM
	 */
	public $INCOTERM;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName LOCATION
	 * @var LOCATION
	 */
	public $LOCATION;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TRANSPORT_REMARK
	 * @var TRANSPORT_REMARK[]
	 */
	public $TRANSPORT_REMARK;


} // end class TRANSPORT
