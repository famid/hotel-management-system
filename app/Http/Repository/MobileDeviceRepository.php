<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 7/5/19
 * Time: 7:31 PM
 */

namespace App\Http\Repository;



use App\Models\MobileDevice;

class MobileDeviceRepository extends CommonRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    function __construct()
    {
        $this->model = new MobileDevice();
        parent::__construct($this->model);
    }

    /**
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where, $data)
    {
        return MobileDevice::updateOrCreate($where, $data);
    }
}
