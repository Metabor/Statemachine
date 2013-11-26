<?php
namespace Example\Order;

interface EventConstants
{
    const EVENT_AUTHORIZE = 'authorize';
    const EVENT_PAID = 'paid';
    const EVENT_SHIPPING = 'shipping';
    const EVENT_RETURNED = 'returned';
    const EVENT_REFUND = 'refund';
}
