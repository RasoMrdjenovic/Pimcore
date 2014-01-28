<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName T_UPDATE_PRODUCTS
 * @var T_UPDATE_PRODUCTS
 */
class T_UPDATE_PRODUCTS
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName FORMULAS
	 * @var FORMULAS
	 */
	public $FORMULAS;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
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


} // end class T_UPDATE_PRODUCTS
