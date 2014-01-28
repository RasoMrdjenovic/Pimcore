<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName FEATURE
 * @var FEATURE
 */
class FEATURE
	{

	
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName FNAME
	 * @var FNAME[]
	 */
	public $FNAME;
	/**
	 * @xmlType element
	 * @xmlName FT_IDREF
	 * @var FT_IDREF
	 */
	public $FT_IDREF;
	/**
	 * @xmlType element
	 * @xmlName FTEMPLATE
	 * @var FTEMPLATE
	 */
	public $FTEMPLATE;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName FVALUE
	 * @var FVALUE[]
	 */
	public $FVALUE;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName VALUE_IDREF
	 * @var VALUE_IDREF[]
	 */
	public $VALUE_IDREF;
	/**
	 * @xmlType element
	 * @xmlName VARIANTS
	 * @var VARIANTS
	 */
	public $VARIANTS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FUNIT
	 * @var FUNIT
	 */
	public $FUNIT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORDER
	 * @var FORDER
	 */
	public $FORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FDESCR
	 * @var FDESCR[]
	 */
	public $FDESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FVALUE_DETAILS
	 * @var FVALUE_DETAILS[]
	 */
	public $FVALUE_DETAILS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FVALUE_TYPE
	 * @var FVALUE_TYPE
	 */
	public $FVALUE_TYPE;


} // end class FEATURE
