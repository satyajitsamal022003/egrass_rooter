<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Role;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BulkSmsController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function getroletype()
    {
        $getroletype = Role::select('id', 'role')->where('is_active', 1)->get();

        $getroletypedata = $getroletype->map(function ($roledata) {
            return [
                'role_id' => $roledata->id,
                'role_name' => $roledata->role
            ];
        });
        return response()->json([
            'role_type' => $getroletypedata
        ], 200);
    }

    public function getcontactmembers($role)
    {
        $getcontactmembers = AddMember::where('role_type', $role)->where('is_active', 1)->get();

        $getmemberdata = [];
        foreach ($getcontactmembers as $edata) {
            $getmemberdata[] = [
                'id' => $edata->id,
                'phone_number' => $edata->phone_number,
            ];
        }

        return response()->json([
            'members' => $getmemberdata
        ], 200);
    }

    public function sendBulkSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipients_phonenumbers' => 'required|array',
            'recipients_phonenumbers.*' => 'required|string', // Each recipient should be a valid phone number
            'message' => 'required|string'
        ], [
            'recipients_phonenumbers.required' => 'Recipients phone numbers are required.',
            'recipients_phonenumbers.array' => 'Recipients phone numbers must be an array.',
            'recipients_phonenumbers.*.required' => 'Each recipient phone number is required.',
            'recipients_phonenumbers.*.string' => 'Each recipient phone number must be a valid string.',
            'message.required' => 'The message field is required.',
            'message.string' => 'The message must be a valid string.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $recipients = $request->input('recipients_phonenumbers');
        $message = $request->input('message');

        try {
            $this->twilioService->sendBulkSMS($recipients, $message);
            return response()->json(['message' => 'SMS Sent Successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
