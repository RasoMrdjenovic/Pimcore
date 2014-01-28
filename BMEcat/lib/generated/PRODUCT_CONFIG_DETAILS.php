<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PRODUCT_CONFIG_DETAILS
 * @var PRODUCT_CONFIG_DETAILS
 */
class PRODUCT_CONFIG_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName CONFIG_STEP
	 * @var CONFIG_STEP[]
	 */
	public $CONFIG_STEP;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PREDEFINED_CONFIGS
	 * @var PREDEFINED_CONFIGS
	 */
	public $PREDEFINED_CONFIGS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CONFIG_RULES
	 * @var CONFIG_RULES
	 */
	public $CONFIG_RULES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CONFIG_FORMULAS
	 * @var CONFIG_FORMULAS
	 */
	public $CONFIG_FORMULAS;


} // end class PRODUCT_CONFIG_DETAILS
