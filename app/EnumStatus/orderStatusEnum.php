<?php

namespace App\EnumStatus;

enum orderStatusEnum: string
{
    case waiting = 'waiting';
    case accept = 'accept';
    case cancel = 'cancel';
    case start = 'start';
    case end = 'end';
}
