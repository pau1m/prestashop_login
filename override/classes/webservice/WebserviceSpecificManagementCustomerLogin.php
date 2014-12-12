<?php 


		
		// if (!Tools::getIsset('step') && $this->context->customer->isLogged() && !$this->ajax)
		// 	Tools::redirect('index.php?controller='.(($this->authRedirection !== false) ? urlencode($this->authRedirection) : 'my-account'));

		// if (Tools::getValue('create_account'))
		// 	$this->create_account = true;



class WebserviceSpecificManagementCustomerLogin implements WebserviceSpecificManagementInterface
{

  protected $objOutput;
  protected $output;
  protected $wsObject;

  protected $cookie;
  protected $email;

	protected $webserviceParameters = array(
	//	'objectsNodeName' => 'foo',
	// 'fields' => array(
	// 	'id_default_group' => array('xlink_resource' => 'groups'),
	// 	'id_lang' => array('xlink_resource' => 'languages'),
	// ),
	);

  public function setObjectOutput(WebserviceOutputBuilderCore $obj) {
		$this->objOutput = $obj;
		return $this;
	}


	public function getObjectOutput() {
		return $this->objOutput;
	}

	public function setWsObject(WebserviceRequestCore $obj) {
		$this->wsObject = $obj;
		return $this;
	}

	public function getWsObject() {
		return $this->wsObject;
	}

	public function setUrlSegment($segments) {
		$this->urlSegment = $segments;
		return $this;
	}

	public function getUrlSegment() {
		return $this->urlSegment;
	}

	public function manage() {

		if (!isset($this->wsObject->urlFragments['email'])) {
			throw new WebserviceException('You have to use the \'email\' parameter to get a result');
		} 

		$this->email = $this->wsObject->urlFragments['email'];

		if (!Validate::isEmail($this->email)) {
			throw new WebserviceException('Supplied value is not a valid email');
		}

	 	$cookie_name = $this->generateCookie($this->email);

	 	//@todo check db if user exists and handle case

 		$matches = NULL;
 		foreach(headers_list() as $index => $header) {
 			preg_match('/Set-Cookie: (.*)\b/',$header, $matches);
 		}

		$cookie_parts = explode(';', $matches[0]);
		$cookie_key_value = explode('=', $cookie_parts[0]);
		$cookie_value = $cookie_key_value[1];
		header_remove('Set-Cookie');
			
		$this->output .= $this->objOutput->getObjectRender()->renderNodeHeader('cookie');

		$this->output .= $this->objOutput->getObjectRender()->renderNodeHeader('name', array());
		$this->output .= '<![CDATA['.$cookie_name.']]>';
		$this->output .= $this->objOutput->getObjectRender()->renderNodeFooter('name', array());	

		$this->output .= $this->objOutput->getObjectRender()->renderNodeHeader('value', array());
		//$this->output .= '<![CDATA['.$_COOKIE[$cookie_name].']]>';      //$_COOKIE[$cookie_name];
		$this->output .= '<![CDATA['.$cookie_value.']]>';
		$this->output .= $this->objOutput->getObjectRender()->renderNodeFooter('value', array());

		$this->output .= $this->objOutput->getObjectRender()->renderNodeFooter('cookie');

		return $this->wsObject->getOutputEnabled();
		
	}

	public function getContent() {

	  return $this->objOutput->getObjectRender()->overrideContent($this->output);
	}

	protected function generateCookie($email /*=  "paul+2@freedomthroughweb.com"*/) {

		$customer = new \Customer();
		$authentication = $customer->getByEmail($email);

		if (!$authentication) { //user doesn't exist
			ppp('could not find user');
			return false;
		}

		$ctx = \Context::getContext();
	
		$ctx->cookie->id_customer = (int)($customer->id);
		$ctx->cookie->customer_lastname = $customer->lastname;
		$ctx->cookie->customer_firstname = $customer->firstname;
		$ctx->cookie->logged = 1;
		$customer->logged = 1;
		$ctx->cookie->is_guest = $customer->isGuest();
		$ctx->cookie->passwd = $customer->passwd;
		$ctx->cookie->email = $customer->email;
		// Add customer to the context
		$ctx->customer = $customer;
	
		$ctx->cookie->write();

		return $ctx->cookie->getName();
	}
}


