<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 7/5/19
 * Time: 7:31 PM
 */

namespace App\Http\Repository;



use App\Models\PasswordReset;

class PasswordResetRepository extends CommonRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    function __construct()
    {
        $this->model = new PasswordReset();
        parent::__construct($this->model);
    }
}
