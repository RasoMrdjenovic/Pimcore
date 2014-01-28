<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName PRODUCT_FEATURES
 * @var PRODUCT_FEATURES
 */
class PRODUCT_FEATURES
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName REFERENCE_FEATURE_SYSTEM_NAME
	 * @var REFERENCE_FEATURE_SYSTEM_NAME
	 */
	public $REFERENCE_FEATURE_SYSTEM_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName REFERENCE_FEATURE_GROUP_ID
	 * @var REFERENCE_FEATURE_GROUP_ID[]
	 */
	public $REFERENCE_FEATURE_GROUP_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName REFERENCE_FEATURE_GROUP_NAME
	 * @var REFERENCE_FEATURE_GROUP_NAME[]
	 */
	public $REFERENCE_FEATURE_GROUP_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName REFERENCE_FEATURE_GROUP_ID2
	 * @var REFERENCE_FEATURE_GROUP_ID2[]
	 */
	public $REFERENCE_FEATURE_GROUP_ID2;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName GROUP_PRODUCT_ORDER
	 * @var GROUP_PRODUCT_ORDER
	 */
	public $GROUP_PRODUCT_ORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FEATURE
	 * @var FEATURE[]
	 */
	public $FEATURE;


} // end class PRODUCT_FEATURES
