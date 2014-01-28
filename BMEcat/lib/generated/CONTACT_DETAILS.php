<?php

/**
 * @xmlNamespace 
 * @xmlType 
 * @xmlName CONTACT_DETAILS
 * @var CONTACT_DETAILS
 */
class CONTACT_DETAILS
	{

	
	/**
	 * @xmlType element
	 * @xmlName CONTACT_ID
	 * @var CONTACT_ID
	 */
	public $CONTACT_ID;
	/**
	 * @xmlType element
	 * @xmlMaxOccurs unbounded
	 * @xmlName CONTACT_NAME
	 * @var CONTACT_NAME[]
	 */
	public $CONTACT_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FIRST_NAME
	 * @var FIRST_NAME[]
	 */
	public $FIRST_NAME;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName TITLE
	 * @var TITLE[]
	 */
	public $TITLE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName ACADEMIC_TITLE
	 * @var ACADEMIC_TITLE[]
	 */
	public $ACADEMIC_TITLE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CONTACT_ROLE
	 * @var CONTACT_ROLE[]
	 */
	public $CONTACT_ROLE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName CONTACT_DESCR
	 * @var CONTACT_DESCR[]
	 */
	public $CONTACT_DESCR;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName PHONE
	 * @var PHONE[]
	 */
	public $PHONE;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlMaxOccurs unbounded
	 * @xmlName FAX
	 * @var FAX[]
	 */
	public $FAX;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName URL
	 * @var URL
	 */
	public $URL;
	/**
	 * @xmlType element
	 * @xmlMinOccurs 0
	 * @xmlName EMAILS
	 * @var EMAILS
	 */
	public $EMAILS;


} // end class CONTACT_DETAILS
