<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ARTICLE_PRICE_DETAILS
 * @var ARTICLE_PRICE_DETAILS
 */
class ARTICLE_PRICE_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName VALID_START_DATE
	 * @var VALID_START_DATE
	 */
	public $VALID_START_DATE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName VALID_END_DATE
	 * @var VALID_END_DATE
	 */
	public $VALID_END_DATE;
	/**
	 * @xmlType element
	 * @xmlNamespace http://www.bmecat.org/bmecat/2005fd
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs 2
	 * @xmlName DATETIME
	 */
	public $DATETIME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName DAILY_PRICE
	 * @var DAILY_PRICE[2]
	 */
	public $DAILY_PRICE;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName ARTICLE_PRICE
	 * @var ARTICLE_PRICE[]
	 */
	public $ARTICLE_PRICE;


} // end class ARTICLE_PRICE_DETAILS
