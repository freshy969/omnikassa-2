<?php
/**
 * Product categories.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\OmniKassa2
 */

namespace Pronamic\WordPress\Pay\Gateways\OmniKassa2;

use Pronamic\WordPress\Pay\Payments\PaymentLineType;

/**
 * Product categories.
 *
 * @author  Reüel van der Steege
 * @version 2.0.4
 * @since   2.0.4
 */
class ProductCategories {
	/**
	 * Product category 'PHYSICAL'.
	 *
	 * @var string
	 */
	const PHYSICAL = 'PHYSICAL';

	/**
	 * Product category 'DIGITAL'.
	 *
	 * @var string
	 */
	const DIGITAL = 'DIGITAL';

	/**
	 * Transform Pronamic payment line type to OmniKassa 2.0 category.
	 *
	 * @param string $type Pronamic payment line type.
	 * @return string
	 */
	public static function transform( $type ) {
		switch ( $type ) {
			case PaymentLineType::DIGITAL:
				return self::DIGITAL;
			case PaymentLineType::DISCOUNT:
				return self::DIGITAL;
			case PaymentLineType::PHYSICAL:
				return self::PHYSICAL;
			case PaymentLineType::SHIPPING:
				return self::DIGITAL;
			default:
				return self::DIGITAL;
		}
	}
}
