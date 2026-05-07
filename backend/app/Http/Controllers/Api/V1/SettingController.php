<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::pluck('value', 'key');
        return $this->successResponse($settings);
    }

    public function update(UpdateSettingRequest $request): JsonResponse
    {
        foreach ($request->validated()['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        return $this->successResponse(message: 'Settings updated.');
    }
}
