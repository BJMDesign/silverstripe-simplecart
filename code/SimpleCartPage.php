<?php

class SimpleCartPage extends Page {

	static $defaults = array(
		'Content' => '<h1>Your Cart</h1><p>[CartSummary]</p>'
	);

}

class SimpleCartPage_Controller extends Page_Controller {

	public static function addRequirements() {
		foreach( self::getJavascriptFiles() as $file ) {
			Requirements::javascript($file);
		}
		foreach( self::getCSSFiles() as $file ) {
			Requirements::css($file);
		}
	}

	public static function getJavascriptFiles() {
		return array(
			'simplecart/vendor/simplecart-js/simpleCart'
				.(Director::isLive() ? '.min' : '').'.js',
			'simplecart/vendor/jquery.path/jquery.path.js',
			'simplecart/vendor/simplecart-utils/simpleCart-utils.js'
		);
	}

	public static function getCSSFiles() {
		return array(
			'simplecart/css/simpleCart.css'
		);
	}

	public function init() {
		parent::init();
		self::addRequirements();
	}

	public static function createOrder( $request, $member ) {
		$order = new Order();
		$order->Member = $member;
		for( $i = 1; $i <= $request['itemCount']; $i++ ) {
			$item = new OrderItem();
			$item->Name = $request["item_name_$i"];
			$item->Quantity = $request["item_quantity_$i"];
			$item->Amount = $request["item_price_$i"];
			$item->options = self::parseOptions($request["item_options_$i"]);
			$order->OrderItems()->add($item);
		}
		return $order;
	}

	public static function parseOptions( $options ) {
		$rv = array();
		foreach( explode(', ', $options) as $pair ) {
			list($name, $value) = explode(': ', $pair);
			$rv[$name] = $value;
		}
		return $rv;
	}

	function ErrorMessage() {
		return PaymentPage_Controller::getErrorMessage();
	}

}