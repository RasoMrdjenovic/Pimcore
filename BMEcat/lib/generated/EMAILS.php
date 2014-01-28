<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName EMAILS
 * @var EMAILS
 */
class EMAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlName EMAIL
	 * @var EMAIL
	 */
	public $EMAIL;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PUBLIC_KEY
	 * @var PUBLIC_KEY[]
	 */
	public $PUBLIC_KEY;


} // end class EMAILS
