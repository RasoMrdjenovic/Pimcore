<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName TAX_DETAILS
 * @var TAX_DETAILS
 */
class TAX_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CALCULATION_SEQUENCE
	 * @var CALCULATION_SEQUENCE
	 */
	public $CALCULATION_SEQUENCE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TAX_CATEGORY
	 * @var TAX_CATEGORY
	 */
	public $TAX_CATEGORY;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TAX_TYPE
	 * @var TAX_TYPE
	 */
	public $TAX_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TAX
	 * @var TAX
	 */
	public $TAX;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName EXEMPTION_REASON
	 * @var EXEMPTION_REASON[]
	 */
	public $EXEMPTION_REASON;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName JURISDICTION
	 * @var JURISDICTION[]
	 */
	public $JURISDICTION;


} // end class TAX_DETAILS
