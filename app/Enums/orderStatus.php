<?php
namespace App\Enums;

class orderStatus
{
    const waiting = 'waiting';
    const accept = 'accept';
    const arrived = 'arrived';
    const cancel = 'cancel';
    const cancelAfterTime = 'cancelAfterTime';
    const start = 'start';
    const end = 'end';
    const unPlaced = 'unPlaced';
}
