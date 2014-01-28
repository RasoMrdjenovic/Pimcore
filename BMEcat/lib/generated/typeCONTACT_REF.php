<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName typeCONTACT_REF
 * @var typeCONTACT_REF
 */
class typeCONTACT_REF
	{

	
	/**
	 * @xmlType element
	 * @xmlName PARTY_IDREF
	 * @var PARTY_IDREF
	 */
	public $PARTY_IDREF;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName CONTACT_IDREF
	 * @var CONTACT_IDREF[]
	 */
	public $CONTACT_IDREF;


} // end class typeCONTACT_REF
