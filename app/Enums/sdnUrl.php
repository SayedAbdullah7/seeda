<?php

namespace App\Enums;

class sdnUrl
{
    const login = "https://ap.sdnvision.services/api/user.getsessiontoken";
    const addVehicle = "https://ap.sdnvision.services/api/vehicle.addvehicle";
    const deleteVehicle = "https://ap.sdnvision.services/api/vehicle.deletevehicle";
    const getVehicleList = "https://ap.sdnvision.services/api/vehicle.getvehiclelist";
    const getVehicleById = "https://ap.sdnvision.services/api/vehicle.getvehiclebyid";
    const getVehicle = "https://ap.sdnvision.services/api/vehicle.getvehiclelist";
    const getVCommand = "https://ap.sdnvision.services/api/cam.getvcommand";
    const updateCommand = "https://ap.sdnvision.services/api/cam.updatecommand";
    const cutOffOnVehicle = "https://ap.sdnvision.services/api/cam.sendcommand";
    const getLocation = "https://ap.sdnvision.services/api/location.getlocation";

//    const login = "https://ap.sdnvision.services/api/user.getsessiontoken";
//    const addVehicle = "https://ap.sdnvision.services/api/vehicle.addvehicle";
//    const deleteVehicle = "https://ap.sdnvision.services/api/vehicle.deletevehicle";
//    const getVehicleList = "https://ap.sdnvision.services/api/vehicle.getvehiclelist";
//    const getVehicleById = "https://ap.sdnvision.services/api/vehicle.getvehiclebyid";
//    const getVCommand = "https://ap.sdnvision.services/api/cam.getvcommand";
//    const updateCommand = "https://ap.sdnvision.services/api/cam.updatecommand";
//    const cutOffOnVehicle = "https://ap.sdnvision.services/api/cam.sendcommand";
//    const getLocation = "https://ap.sdnvision.services/api/location.getlocation";
    const apiVersion = 49;
}
