<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 7/5/19
 * Time: 4:17 PM
 */

namespace App\Http\Services;


use App\Http\Repository\AdminSettingsRepository;
use App\Http\Repository\MobileDeviceRepository;
use App\Http\Repository\PasswordResetRepository;
use App\Http\Repository\UserRepository;

class CommonService
{
    protected $userRepository;
    protected $passwordResetRepository;
    protected $adminSettingsRepository;
    protected $mobileDevicesRepository;

    /**
     * CommonService constructor.
     * @param $repository
     */
    function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->passwordResetRepository = new PasswordResetRepository();
        $this->adminSettingsRepository = new AdminSettingsRepository();
        $this->mobileDevicesRepository= new MobileDeviceRepository();
    }
}
