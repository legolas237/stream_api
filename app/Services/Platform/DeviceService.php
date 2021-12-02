<?php

namespace App\Services\Platform;

use App\Models\Device;
use Illuminate\Database\Eloquent\Model;

trait DeviceService
{

    /***
     * Add new record
     *
     * @param array $data
     * @return Model
     */
    public static function store(array $data): Model
    {
        return Device::query()->create($data);
    }

    /**
     * Update device
     *
     * @param array $data
     * @return mixed
     */
    public function updateService(array $data)
    {
        return tap($this)->update($data);
    }

    /**
     * Managing devices during connection
     *
     * @param array $data
     * @param Device|null $device
     * @return Device|Model|mixed|null
     */
    static public function handleDeviceForLogin(array $data, Device $device = null)
    {
        $deviceData = $data;

        if($device === null){
            $device = Device::store($deviceData);
        } elseif ($device->{'device_id'} !== $data['device_id']) {
            $device = $device->updateService($deviceData);
        }

        return $device;
    }

}
