<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 7/5/19
 * Time: 7:31 PM
 */

namespace App\Http\Repository;


use App\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends CommonRepository
{
    public $model;

    /**
     * UserRepository constructor.
     */
    function __construct()
    {
        $this->model = new User();
        parent::__construct($this->model);
    }

    /**
     * @param $where
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($where, $data)
    {
        return User::updateOrCreate($where, $data);
    }

    /**
     * @param $request
     */
    public function deleteToken($request)
    {
        $token = $request->user()->token();
        if (!empty($token)) {
            DB::table('oauth_access_tokens')->where('id', $token->id)->delete();
        }
    }
}
