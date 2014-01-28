<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName AREA_LEGAL_INFO
 * @var AREA_LEGAL_INFO
 */
class AREA_LEGAL_INFO
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TERRITORY
	 * @var TERRITORY[]
	 */
	public $TERRITORY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName AREA_REFS
	 * @var AREA_REFS
	 */
	public $AREA_REFS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName LEGAL_TEXT
	 * @var LEGAL_TEXT[]
	 */
	public $LEGAL_TEXT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName MIME_INFO
	 * @var MIME_INFO
	 */
	public $MIME_INFO;


} // end class AREA_LEGAL_INFO
