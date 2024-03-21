<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Models\PaymentOptions;
use Tests\Models\Customer;
use Tests\Models\Delivery;
use Tests\Models\CheckoutOptions;
use Tests\Models\CustomField;
use Tests\Models\Order;
use Tests\Models\ShoppingCart;
use Tests\Models\ShoppingCartItem;

class OrderTest extends TestCase
{


    public function testPaymentOptions()
    {
        $data = $this->getData();
        $data = $data['payment_options'];

        $paymentOptions = new PaymentOptions($data);

        $this->assertInstanceOf(PaymentOptions::class, $paymentOptions);

        $this->assertEqualsCanonicalizing($data, $paymentOptions->toArray());

    }//end testPaymentOptions()


    public function testCustomFields()
    {
        $data = $this->getData();
        $data = $data['custom_fields'];

        $customFields = CustomField::collection($data);

        $this->assertInstanceOf(CustomField::class, $customFields[0]);

        $this->assertEqualsCanonicalizing($data[0], $customFields[0]->toArray());

    }//end testCustomFields()


    public function testCustomer()
    {
        $data = $this->getData();
        $data = $data['customer'];

        $customer = new Customer($data);

        $this->assertInstanceOf(Customer::class, $customer);

        $this->assertEqualsCanonicalizing($data, $customer->toArray());

    }//end testCustomer()


    public function testDelivery()
    {
        $data = $this->getData();
        $data = $data['delivery'];

        $delivery = new Delivery($data);

        $this->assertInstanceOf(Delivery::class, $delivery);

        $this->assertEqualsCanonicalizing($data, $delivery->toArray());

    }//end testDelivery()


    public function testShoppinCart()
    {
        $data = $this->getData();
        $data = $data['shopping_cart'];

        $shoppingCart = new ShoppingCart($data);

        $this->assertInstanceOf(ShoppingCart::class, $shoppingCart);

        foreach ($shoppingCart->items as $item) {
            $this->assertInstanceOf(ShoppingCartItem::class, $item);
        }

        $this->assertEqualsCanonicalizing($data, $shoppingCart->toArray());

    }//end testShoppinCart()


    public function testCheckoutOptions()
    {
        $data = $this->getData();
        $data = $data['checkout_options'];

        $checkoutOptions = new CheckoutOptions($data);

        $this->assertInstanceOf(CheckoutOptions::class, $checkoutOptions);

        $this->assertEqualsCanonicalizing($data, $checkoutOptions->toArray());

    }//end testCheckoutOptions()


    public function testOrder()
    {
        $data = $this->getData();

        $order = new Order($data);

        $this->assertInstanceOf(Order::class, $order);

        $this->assertEqualsCanonicalizing($data, $order->toArray());

    }//end testOrder()


    private function getData(): array
    {
        $filePath = __DIR__.'/order.json';
        $this->assertFileExists($filePath);
        $this->assertFileIsReadable($filePath);

        $jsonData = file_get_contents($filePath);

        $this->assertIsString($jsonData);

        $data = json_decode($jsonData, true);

        $this->assertNotEmpty($data);
        $this->assertIsArray($data);

        return $data;

    }//end getData()


}//end class
