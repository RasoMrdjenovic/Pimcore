<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName TERM
 * @var TERM
 */
class TERM
	{

	
	/**
	 * @xmlType element
	 * @xmlName TERM_ID
	 * @var TERM_ID
	 */
	public $TERM_ID;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName TERM_CONDITION
	 * @var TERM_CONDITION
	 */
	public $TERM_CONDITION;
	/**
	 * @xmlType element
	 * @xmlName TERM_EXPRESSION
	 * @var TERM_EXPRESSION
	 */
	public $TERM_EXPRESSION;
	/**
	 * @xmlType attribute
	 * @xmlName type
	 */
	public $type;


} // end class TERM
