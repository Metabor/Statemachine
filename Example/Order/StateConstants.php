<?php
namespace Example\Order;

interface StateConstants
{
    const STATE_NEW = 'new';
    const STATE_PAYMENT_PENDING = 'payment pending';
    const STATE_PAYMENT_FAILED = 'payment failed';
    const STATE_SHIPPABLE = 'shippable';
    const STATE_SHIPPED = 'shipped';
    const STATE_RETURNED = 'returned';
    const STATE_RETURNED_AND_CLOSED = 'returned and closed';
    const STATE_CLOSED = 'closed';

    const STATE_PAID = 'paid';
    const STATE_DUNNING = 'dunning';

}
