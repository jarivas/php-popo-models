<?php

declare(strict_types=1);

namespace Tests\Models;

use CastModels\Model;

class Order extends Model
{
    public string $type;
    public string $order_id;
    public string $currency;
    public int $amount;
    public string $description;
    public PaymentOptions $payment_options;
    public Customer $customer;
    public GatewayInfo $gateway_info;
    public Delivery $delivery;
    public CheckoutOptions $checkout_options;
    public ShoppingCart $shopping_cart;
    /** @var \Tests\Models\CustomField[] */
    public array $custom_fields;
    public Affiliate $affiliate;
    public SecondChance $second_chance;
    public int $days_active;
    public int $seconds_active;
    public string $var1;
    public string $var2;
    public string $var3;
    public Plugin $plugin;

    public int $transaction_id;
    public string $created;
    public string $items;
    public int $amount_refunded;
    public string $status;
    public string $financial_status;
    public string $fastcheckout;
    public string $modified;
    /**  @var \Tests\Feature\Model\Cost[] */
    public array $costs;
    /** @var \Tests\Feature\Model\RelatedTransaction[] */
    public array $related_transactions;
    /** @var \Tests\Feature\Model\PaymentMethod[] */
    public array $payment_methods;
}
