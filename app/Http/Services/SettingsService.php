<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\DB;

class SettingsService extends CommonService
{
    /**
     * SettingsService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $request
     * @return array
     */
    public function SaveSuperAdminSettings($request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->except('_token') as $key => $value) {
                if ($key == 'main_logo') {
                    $logo = uploadFile($request->main_logo, logoPath());
                    $this->adminSettingsRepository->updateOrCreate(['slug' => $key], ['value' => $logo]);
                } elseif ($key == 'main_image') {
                    $image = uploadFile($request->main_image, imagePath());
                    $this->adminSettingsRepository->updateOrCreate(['slug' => $key], ['value' => $image]);
                } else {
                    $this->adminSettingsRepository->updateOrCreate(['slug' => $key], ['value' => $value]);
                }
            }
            DB::commit();

            return [
                'success' =>  true,
                'message' => __('Settings has been updated successfully'),
                'data' => null
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' =>  false,
                'message' => __('Something went wrong. Please try again.') . $exception->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function SaveAdminSettings($request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->except('_token') as $key => $value) {
                if ($request->hasFile($key)) {
                    $value = uploadFile($value, appImagePath());
                }
                $this->adminSettingsRepository->updateOrCreate(['slug' => $key], ['value' => $value]);
            }
            DB::commit();
            return [
                'success' =>  true,
                'message' => __('Settings has been updated successfully'),
                'data' => null
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' =>  false,
                'message' => __('Something went wrong. Please try again.') . $exception->getMessage(),
                'data' => null
            ];
        }
    }
}
