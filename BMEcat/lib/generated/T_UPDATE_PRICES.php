<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName T_UPDATE_PRICES
 * @var T_UPDATE_PRICES
 */
class T_UPDATE_PRICES
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
	 * @xmlMaxOccurs unbounded
	 * @xmlName ARTICLE
	 */
	public $ARTICLE;
	/**
	 * @xmlType attribute
	 * @xmlName prev_version
	 * @var dtINTEGER[]
	 */
	public $prev_version;


} // end class T_UPDATE_PRICES
