<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName T_NEW_CATALOG
 * @var T_NEW_CATALOG
 */
class T_NEW_CATALOG
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 0
	 * @xmlName FEATURE_SYSTEM
	 * @var FEATURE_SYSTEM
	 */
	public $FEATURE_SYSTEM;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CLASSIFICATION_SYSTEM
	 * @var CLASSIFICATION_SYSTEM[]
	 */
	public $CLASSIFICATION_SYSTEM;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName CATALOG_GROUP_SYSTEM
	 * @var CATALOG_GROUP_SYSTEM
	 */
	public $CATALOG_GROUP_SYSTEM;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORMULAS
	 * @var FORMULAS
	 */
	public $FORMULAS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName IPP_DEFINITIONS
	 * @var IPP_DEFINITIONS
	 */
	public $IPP_DEFINITIONS;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRODUCT
	 */
	public $PRODUCT;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PRODUCT_TO_CATALOGGROUP_MAP
	 */
	public $PRODUCT_TO_CATALOGGROUP_MAP;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ARTICLE
	 */
	public $ARTICLE;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ARTICLE_TO_CATALOGGROUP_MAP
	 */
	public $ARTICLE_TO_CATALOGGROUP_MAP;
	/**
	 * @xmlType attribute
	 * @xmlName prev_version
	 * @var dtINTEGER[]
	 */
	public $prev_version;


} // end class T_NEW_CATALOG
