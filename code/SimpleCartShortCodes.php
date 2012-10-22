<?php

/**
 * Register this parser with the following code in your _config.php file:
 *
 *   SimpleCartShortCodes::register();
 *
 * You can then insert a YouTube video into your content using:
 *
 *   [CartItem price=12.34 name=My Cool T-Shirt]
 *
 * or
 *
 *   [CartItem price=12.34 name=My Cool T-Shirt]<img src="my-image.png" />[/SimpleCart]
 */

class SimpleCartShortCodes extends ViewableData {

	/**
	 * @var ArrayData
	 */
	protected $data;
 
	public static function register() {
		$instance = ShortcodeParser::get();
		foreach( array('CartItem', 'CartButton', 'CartSummary') as $method ) {
			$instance->register($method, array('SimpleCartShortCodes', $method));
		}
	}

	function CartItem( $arguments, $enclosedContent = null, $parser = null ) {
		$defaults = array(
			'price' => 0
		);
		$vars = array_merge($defaults, $arguments);
		$vars['Price'] = '$'.number_format($arguments['price'], 2);
		$vars['Body'] = $enclosedContent ? ShortcodeParser::get()->parse($enclosedContent) : '';
		$template = new SSViewer('SimpleCartItem');
		return $template->process(new ArrayData($vars));
	}

	function CartSummary( $arguments, $enclosedContent = null, $parser = null ) {
		$template = new SSViewer('SimpleCartSummary');
		return $template->process(new ArrayData(array()));
	}

	function CartButton( $arguments, $enclosedContent = null, $parser = null ) {
		return '<div class="simpleCart_items button"></div>';
	}

}

?>