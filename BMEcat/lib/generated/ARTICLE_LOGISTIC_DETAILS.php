<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ARTICLE_LOGISTIC_DETAILS
 * @var ARTICLE_LOGISTIC_DETAILS
 */
class ARTICLE_LOGISTIC_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CUSTOMS_TARIFF_NUMBER
	 * @var CUSTOMS_TARIFF_NUMBER[]
	 */
	public $CUSTOMS_TARIFF_NUMBER;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName STATISTICS_FACTOR
	 * @var STATISTICS_FACTOR
	 */
	public $STATISTICS_FACTOR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName COUNTRY_OF_ORIGIN
	 * @var COUNTRY_OF_ORIGIN[]
	 */
	public $COUNTRY_OF_ORIGIN;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName PRODUCT_DIMENSIONS
	 * @var PRODUCT_DIMENSIONS
	 */
	public $PRODUCT_DIMENSIONS;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName DELIVERY_TIMES
	 * @var DELIVERY_TIMES[]
	 */
	public $DELIVERY_TIMES;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TRANSPORT
	 * @var TRANSPORT[]
	 */
	public $TRANSPORT;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName MEANS_OF_TRANSPORT
	 * @var MEANS_OF_TRANSPORT[]
	 */
	public $MEANS_OF_TRANSPORT;


} // end class ARTICLE_LOGISTIC_DETAILS
