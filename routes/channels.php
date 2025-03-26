<?php
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('chat', function () {
    return true;
});

Broadcast::channel('orderevent', function () {
    return true;
});

Broadcast::channel('ordercompleted', function () {
    return true;
});

Broadcast::channel('lowstock', function () {
    return true;
});