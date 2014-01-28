<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CONFIG_STEP
 * @var CONFIG_STEP
 */
class CONFIG_STEP
	{

	
	/**
	 * @xmlType element
	 * @xmlName STEP_ID
	 * @var STEP_ID
	 */
	public $STEP_ID;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName STEP_HEADER
	 * @var STEP_HEADER[]
	 */
	public $STEP_HEADER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName STEP_DESCR_SHORT
	 * @var STEP_DESCR_SHORT[]
	 */
	public $STEP_DESCR_SHORT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName STEP_DESCR_LONG
	 * @var STEP_DESCR_LONG[]
	 */
	public $STEP_DESCR_LONG;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName STEP_ORDER
	 * @var STEP_ORDER
	 */
	public $STEP_ORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName STEP_INTERACTION_TYPE
	 * @var STEP_INTERACTION_TYPE
	 */
	public $STEP_INTERACTION_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CONFIG_CODE
	 * @var CONFIG_CODE
	 */
	public $CONFIG_CODE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_PRICE_DETAILS
	 * @var PRODUCT_PRICE_DETAILS
	 */
	public $PRODUCT_PRICE_DETAILS;
	/**
	 * @xmlType element
	 * @xmlName CONFIG_FEATURE
	 * @var CONFIG_FEATURE
	 */
	public $CONFIG_FEATURE;
	/**
	 * @xmlType element
	 * @xmlName CONFIG_PARTS
	 * @var CONFIG_PARTS
	 */
	public $CONFIG_PARTS;
	/**
	 * @xmlType element
	 * @xmlName MIN_OCCURANCE
	 * @var MIN_OCCURANCE
	 */
	public $MIN_OCCURANCE;
	/**
	 * @xmlType element
	 * @xmlName MAX_OCCURANCE
	 * @var MAX_OCCURANCE
	 */
	public $MAX_OCCURANCE;


} // end class CONFIG_STEP
