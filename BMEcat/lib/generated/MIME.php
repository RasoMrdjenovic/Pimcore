<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName MIME
 * @var MIME
 */
class MIME
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_TYPE
	 * @var MIME_TYPE
	 */
	public $MIME_TYPE;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName MIME_SOURCE
	 * @var MIME_SOURCE[]
	 */
	public $MIME_SOURCE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MIME_DESCR
	 * @var MIME_DESCR[]
	 */
	public $MIME_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MIME_ALT
	 * @var MIME_ALT[]
	 */
	public $MIME_ALT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_PURPOSE
	 * @var MIME_PURPOSE
	 */
	public $MIME_PURPOSE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_ORDER
	 * @var MIME_ORDER
	 */
	public $MIME_ORDER;


} // end class MIME
