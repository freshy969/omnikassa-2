<?php
/**
 * Order
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Gateways\OmniKassa2
 */

namespace Pronamic\WordPress\Pay\Gateways\OmniKassa2;

use DateTime;
use InvalidArgumentException;
use Pronamic\WordPress\Pay\Payments\PaymentLines;

/**
 * Order
 *
 * @author  Remco Tolsma
 * @version 2.0.2
 * @since   1.0.0
 */
class Order extends Message {
	/**
	 * ISO 8601 standard Date / time on which the order is announced at ROK.
	 * As a rule, this is the current date / time.
	 *
	 * This field is mandatory and provides protection against so-called
	 * replay (playback) attacks
	 *
	 * @var DateTime
	 */
	private $timestamp;

	/**
	 * Generated by Merchant. If your webshop wants to use AfterPay, this field must be unique.
	 *
	 * @var string
	 */
	private $merchant_order_id;

	/**
	 * Description of the order.
	 *
	 * @var string|null
	 */
	private $description;

	/**
	 * The order items.
	 *
	 * @var OrderItems|null
	 */
	private $order_items;

	/**
	 * Amount.
	 *
	 * @var Money
	 */
	private $amount;

	/**
	 * The shipping address.
	 *
	 * @var Address|null
	 */
	private $shipping_detail;

	/**
	 * The billing address.
	 *
	 * @var Address|null
	 */
	private $billing_detail;

	/**
	 * The customer information.
	 *
	 * @var CustomerInformation|null
	 */
	private $customer_information;

	/**
	 * Language.
	 *
	 * ISO 639-1 standard. Not Case sensitive.
	 *
	 * @var string|null
	 */
	private $language;

	/**
	 * Merchant return URL.
	 *
	 * The URL to which the consumer's browser will be sent after the payment.
	 *
	 * @var string
	 */
	private $merchant_return_url;

	/**
	 * Payment brand.
	 *
	 * This field is optional and is used to enforce a specific
	 * payment method with the consumer instead of the consumer
	 * selecting a payment method on the payment method selection
	 * page.
	 *
	 * Valid values are:
	 * • IDEAL
	 * • AFTERPAY
	 * • PAYPAL
	 * • MASTERCARD
	 * • VISA
	 * • BANCONTACT
	 * • MAESTRO
	 * • V_PAY
	 * • CARDS
	 *
	 * The CARDS value ensures that the consumer can choose
	 * between payment methods: MASTERCARD, VISA, BANCONTACT,
	 * MAESTRO and V_PAY
	 *
	 * @var string|null
	 */
	private $payment_brand;

	/**
	 * Payment brand force.
	 *
	 * This field should only be delivered if the paymentBrand field (see
	 * above) is also specified. This field can be used to send or, after
	 * a failed payment, the consumer can or can not select another payment
	 * method to still pay the payment.
	 *
	 * Valid values are:
	 * • FORCE_ONCE
	 * • FORCE_ALWAYS
	 *
	 * In the case of FORCE_ONCE, the indicated paymentBrand is only
	 * enforced on the first transaction. If this fails, the consumer
	 * can still choose another payment method. When FORCE_ALWAYS is
	 * chosen, the consumer can not choose another payment method.
	 *
	 * @var string|null
	 */
	private $payment_brand_force;

	/**
	 * Construct order.
	 *
	 * @param string $merchant_order_id    Merchant order ID.
	 * @param Money  $amount               Amount.
	 * @param string $merchant_return_url  Merchant return URL.
	 */
	public function __construct( $merchant_order_id, $amount, $merchant_return_url ) {
		$this->set_timestamp( new DateTime() );
		$this->set_merchant_order_id( $merchant_order_id );
		$this->set_amount( $amount );
		$this->set_merchant_return_url( $merchant_return_url );
	}

	/**
	 * Set timestamp.
	 *
	 * @param DateTime $timestamp Timestamp.
	 */
	public function set_timestamp( DateTime $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Set merchant order ID.
	 *
	 * @param string $merchant_order_id Merchant order ID.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..max 10`.
	 */
	public function set_merchant_order_id( $merchant_order_id ) {
		DataHelper::validate_an( $merchant_order_id, 10 );

		$this->merchant_order_id = $merchant_order_id;
	}

	/**
	 * Set amount.
	 *
	 * @param Money $amount Amount.
	 */
	public function set_amount( Money $amount ) {
		$this->amount = $amount;
	}

	/**
	 * Set merchant return URL.
	 *
	 * The URL to which the consumer's browser will be sent after the payment.
	 *
	 * @param string $url Merchant return URL.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..max 1024`.
	 */
	public function set_merchant_return_url( $url ) {
		DataHelper::validate_an( $url, 1024 );

		$this->merchant_return_url = $url;
	}

	/**
	 * Set description.
	 *
	 * @param string|null $description Description.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..max 35`.
	 */
	public function set_description( $description ) {
		DataHelper::validate_null_or_an( $description, 35 );

		$this->description = $description;
	}

	/**
	 * Set language.
	 *
	 * @param string|null $language Language.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..2`.
	 */
	public function set_language( $language ) {
		DataHelper::validate_null_or_an( $language, 2 );

		$this->language = $language;
	}

	/**
	 * Set payment brand.
	 *
	 * @param string|null $payment_brand Payment brand.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..50`.
	 */
	public function set_payment_brand( $payment_brand ) {
		DataHelper::validate_null_or_an( $payment_brand, 50 );

		$this->payment_brand = $payment_brand;
	}

	/**
	 * Set payment brand force.
	 *
	 * @param string|null $payment_brand_force Payment brand force.
	 * @throws InvalidArgumentException Throws invalid argument exception when value does not apply to format `AN..50`.
	 */
	public function set_payment_brand_force( $payment_brand_force ) {
		DataHelper::validate_null_or_an( $payment_brand_force, 50 );

		$this->payment_brand_force = $payment_brand_force;
	}

	/**
	 * Create and set new order items.
	 *
	 * @return OrderItems
	 */
	public function new_items() {
		$this->order_items = new OrderItems();

		return $this->order_items;
	}

	/**
	 * Set order items.
	 *
	 * @param OrderItems|null $order_items Order items.
	 */
	public function set_order_items( OrderItems $order_items = null ) {
		$this->order_items = $order_items;
	}

	/**
	 * Set shipping detail.
	 *
	 * @param Address|null $shipping_detail Shipping address details.
	 */
	public function set_shipping_detail( Address $shipping_detail = null ) {
		$this->shipping_detail = $shipping_detail;
	}

	/**
	 * Set billing detail.
	 *
	 * @param Address|null $billing_detail Billing address details.
	 */
	public function set_billing_detail( Address $billing_detail = null ) {
		$this->billing_detail = $billing_detail;
	}

	/**
	 * Set customer information.
	 *
	 * @param CustomerInformation $customer_information Customer information.
	 */
	public function set_customer_information( CustomerInformation $customer_information ) {
		$this->customer_information = $customer_information;
	}

	/**
	 * Get JSON object.
	 *
	 * @return object
	 */
	public function get_json() {
		$object = (object) array();

		$object->timestamp       = $this->timestamp->format( DATE_ATOM );
		$object->merchantOrderId = $this->merchant_order_id;

		if ( null !== $this->description ) {
			$object->description = $this->description;
		}

		if ( null !== $this->order_items ) {
			$object->orderItems = $this->order_items->get_json();
		}

		$object->amount = $this->amount->get_json();

		if ( null !== $this->shipping_detail ) {
			$object->shippingDetail = $this->shipping_detail->get_json();
		}

		if ( null !== $this->billing_detail ) {
			$object->billingDetail = $this->billing_detail->get_json();
		}

		if ( null !== $this->customer_information ) {
			$object->customerInformation = $this->customer_information->get_json();
		}

		if ( null !== $this->language ) {
			$object->language = $this->language;
		}

		$object->merchantReturnURL = $this->merchant_return_url;

		if ( null !== $this->payment_brand ) {
			$object->paymentBrand = $this->payment_brand;
		}

		if ( null !== $this->payment_brand_force ) {
			$object->paymentBrandForce = $this->payment_brand_force;
		}

		$object->signature = $this->get_signature();

		return $object;
	}

	/**
	 * Get signature fields.
	 *
	 * @param array $fields Fields.
	 * @return array
	 */
	public function get_signature_fields( $fields = array() ) {
		$fields[] = $this->timestamp->format( DATE_ATOM );
		$fields[] = $this->merchant_order_id;

		$fields = $this->amount->get_signature_fields( $fields );

		$fields[] = $this->language;
		$fields[] = $this->description;
		$fields[] = $this->merchant_return_url;

		if ( null !== $this->order_items ) {
			$fields = $this->order_items->get_signature_fields( $fields );
		}

		if ( null !== $this->shipping_detail ) {
			$fields = $this->shipping_detail->get_signature_fields( $fields );
		}

		if ( null !== $this->payment_brand ) {
			$fields[] = $this->payment_brand;
		}

		if ( null !== $this->payment_brand_force ) {
			$fields[] = $this->payment_brand_force;
		}

		if ( null !== $this->customer_information ) {
			$fields = $this->customer_information->get_signature_fields( $fields );
		}

		if ( null !== $this->billing_detail ) {
			$fields = $this->billing_detail->get_signature_fields( $fields );
		}

		return $fields;
	}
}
