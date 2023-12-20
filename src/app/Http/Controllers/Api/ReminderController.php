<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Http\Resources\ReminderCollection;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    use ApiResponseTrait;

    public function show($id)
    {
        $reminder = Reminder::find($id);
        if ($reminder) {
            return $this->sendResponse(true, new ReminderResource($reminder), ['code' => 200]);
        }
        return $this->sendResponse(false, [], ['code' => 401, 'err' => 'ERR_NOT_FOUND', 'msg' => 'data not found']);
    }

    public function store(StoreReminderRequest $request)
    {
        $user = auth('sanctum')->user();
        $request->merge(['user_id' => $user->id]);

        $model = Reminder::create($request->validated());

        return $this->sendResponse(true, new ReminderResource($model), ['code' => 200]);
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        $reminder->update($request->validated());

        return $this->sendResponse(true, new ReminderResource($reminder), ['code' => 200]);
    }

    public function delete(Reminder $reminder)
    {
        $reminder->delete();

        return response()->json([
            'ok' => true
        ]);
    }

    public function index($limit = 10)
    {
        $user = auth('sanctum')->user();
        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('remind_at', 'asc')
            ->limit($limit)
            ->get()
            ->map(function($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'description' => $data->description,
                    'remind_at' => $data->remind_at,
                    'event_at' => $data->event_at
                ];
            });
        return $this->sendResponse(true, ['reminders' => $reminders, 'limit' => $limit], ['code' => 200]);
    }


}
