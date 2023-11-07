<?php

namespace App\Enums;

enum mediaTypeEnum: string
{
    case orderEnd = 'orderEnd';
    case orderVoice = 'orderVoice';
    case nationalId = 'nationalId';
    case driverLicense = 'driverLicense';
    case DriverCriminalRecorder = 'DriverCriminalRecorder';
    case vehicleLicense = 'vehicleLicense';
    case vehiclePlatNumber = 'vehiclePlatNumber';
    case vehicleImage = 'vehicleImage';
    case front = 'front';
    case back = 'back';
    case idImage = 'userId';
}
