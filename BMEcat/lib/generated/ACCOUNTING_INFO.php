<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName ACCOUNTING_INFO
 * @var ACCOUNTING_INFO
 */
class ACCOUNTING_INFO
	{

	
	/**
	 * @xmlType element
	 * @xmlName COST_CATEGORY_ID
	 * @var COST_CATEGORY_ID
	 */
	public $COST_CATEGORY_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName COST_TYPE
	 * @var COST_TYPE
	 */
	public $COST_TYPE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName COST_ACCOUNT
	 * @var COST_ACCOUNT
	 */
	public $COST_ACCOUNT;


} // end class ACCOUNTING_INFO
