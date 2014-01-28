<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PARTY
 * @var PARTY
 */
class PARTY
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PARTY_ID
	 * @var PARTY_ID[]
	 */
	public $PARTY_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PARTY_ROLE
	 * @var PARTY_ROLE[]
	 */
	public $PARTY_ROLE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName ADDRESS
	 * @var ADDRESS
	 */
	public $ADDRESS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;


} // end class PARTY
