<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ARTICLE_FEATURES
 * @var ARTICLE_FEATURES
 */
class ARTICLE_FEATURES
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
	 * @xmlName CLASSIFICATION_GROUP_ARTICLEORDER
	 * @var CLASSIFICATION_GROUP_ARTICLEORDER
	 */
	public $CLASSIFICATION_GROUP_ARTICLEORDER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FEATURE
	 * @var FEATURE[]
	 */
	public $FEATURE;


} // end class ARTICLE_FEATURES
