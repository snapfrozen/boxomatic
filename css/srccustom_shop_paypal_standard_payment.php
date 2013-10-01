<?php

class SRCCustom_Actions extends Shop_Actions
{
	const whosaleGroupID = 8;	//W
	const adminUserID = 2;		//Admin user id for sending wholesale approval reminders to
	
	public function login()
	{
		if (post('login'))
			$this->on_login();
		elseif (post('signup'))
			$this->on_signup();
	}
	
	public function on_signup()
	{		
		$customer = new Shop_Customer();
		$customer->disable_column_cache('front_end', false);

		$customer->init_columns_info('front_end');
		$customer->validation->focusPrefix = null;
		$customer->validation->getRule('email')->focusId('signup_email');

		if (!array_key_exists('password', $_POST))
			$customer->generate_password();

		$shipping_params = Shop_ShippingParams::get();

		if (!post('shipping_country_id'))
		{
			$customer->shipping_country_id = $shipping_params->default_shipping_country_id;
			$customer->shipping_state_id = $shipping_params->default_shipping_state_id;
		}

		if (!post('shipping_zip'))
			$customer->shipping_zip = $shipping_params->default_shipping_zip;

		if (!post('shipping_city'))
			$customer->shipping_city = $shipping_params->default_shipping_city;

		$customer->save($_POST);
		
		//var_dump($_POST);
		if(isset($_POST['customer_group_id']) && $_POST['customer_group_id']==self::whosaleGroupID) 
		{
			$template = System_EmailTemplate::create()->find_by_code('shop:wholesale_regsuccess');
			if ($template)
			{
				$message = $customer->set_customer_email_vars($template->content);
				$template->send_to_customer($customer, $message);
			}
			
			$templateAdmin = System_EmailTemplate::create()->find_by_code('shop:wholesale_approval');
			$user = Users_User::create()->find(self::adminUserID);
			
			if ($templateAdmin && $user)
			{
				$message = str_replace('[customer name]', $customer->first_name . ' ' . $customer->last_name, $templateAdmin->content);
				$templateAdmin->send($user->email, $message, $user->first_name);
			}
		}
		else
		{
			echo 'not customer group';
			exit;
			$customer->send_registration_confirmation();
		}
		
		if (post('flash'))
			Phpr::$session->flash['success'] = post('flash');

		if (post('customer_auto_login'))
			Phpr::$frontend_security->customerLogin($customer->id);

		$redirect = post('redirect');
		if ($redirect)
			Phpr::$response->redirect($redirect);
	}
	
	public function print_invoice()
	{
//		include('lib/mpdf/mpdf.php');
//		$mpdf=new mPDF();
//		$mpdf->WriteHTML('<p>Hallo World</p>');
//		$mpdf->Output();
//		exit;
	
		$this->data['order'] = null;
		$this->data['payment_processed'] = false;

		$order_hash = trim($this->request_param(0));
		if (!strlen($order_hash))
			return;

		$order = Shop_Order::create()->find_by_order_hash($order_hash);
		if (!$order)
			return;
		
		
		$this->data['order'] = $order;
		$company_info = $this->data['company_info'] = Shop_CompanyInformation::get();
		
		$invoice_info = $this->data['invoice_template_info'] = $company_info->get_invoice_template();
		$this->data['template_id'] = isset($invoice_info['template_id']) ? $invoice_info['template_id'] : null;
		$this->data['invoice_template_css'] = isset($invoice_info['css']) ? $invoice_info['css'] : array();
		$this->data['display_due_date'] = strlen($company_info->invoice_due_date_interval);
		
		//print_r($this->data);exit;
		//echo $this->page->content;
		
//		$block = Cms_ContentBlock::get_by_page_and_code($this->page->id, $this->page->content);
//		var_dump($block);
//		exit;
	}
	
	
	public function on_addToCart($ajax_mode = true)
	{
		if ($ajax_mode)
			$this->action();

		$quantity = trim(post('product_cart_quantity', 1));

		if (!strlen($quantity) || !preg_match('/^[0-9]+$/', $quantity))
			throw new Cms_Exception('Invalid quantity value.');

		if (!isset($this->data['product']))
		{
			$product_id = post('product_id');
			if (!$product_id)
				throw new Cms_Exception('Product not found.');

			$product = Shop_Product::create()->find_by_id($product_id);
			if (!$product)
				throw new Cms_Exception('Product not found.');

			$this->data['product'] = $product;
		}

		Shop_Cart::add_cart_item($this->data['product'], array(
			'quantity'=>$quantity,
			'cart_name'=>post('cart_name', 'main'),
			'extra_options'=>post('product_extra_options', array()),
			'options'=>post('product_options', array()),
			'custom_data'=>post('item_data', array()),
			'bundle_data'=>post('bundle_data', array())
		));

		if (!post('no_flash'))
		{
			$message = post('message', '%s item(s) added to your cart.');
			Phpr::$session->flash['success'] = sprintf($message, $quantity);
		}

		$redirect = post('redirect');
		if ($redirect)
			Phpr::$response->redirect($redirect);
	}
}
?>