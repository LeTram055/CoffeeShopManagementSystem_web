<?php
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('chat', function () {
    return true;
});

Broadcast::channel('orders', function () {
    return true;
});