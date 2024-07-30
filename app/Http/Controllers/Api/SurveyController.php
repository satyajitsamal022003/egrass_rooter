<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Campaign_user;
use App\Models\GrassrooterFeedback;
use App\Models\ImportVotersdata;
use App\Models\Notification;
use App\Models\Role;
use App\Models\State;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index(Request $request, $userid)
    {
        // Enable query logging
        DB::enableQueryLog();
        $surveyDet = Survey::where('user_id', $userid)->orderBy('id', 'desc')->get();

        $survey_data = [];
        if (!$surveyDet->isEmpty()) {
            foreach ($surveyDet as $key => $member) {
                $createdDate = date('d-m-Y', strtotime($member->created));
                $survey_data[$key] = [
                    'id' => $member->id,
                    'slug' => $member->slug ?? '',
                    'title' => $member->title ?? '',
                    'description' => $member->description ?? '',
                    'created' => $createdDate ?? null,
                ];
            }
        }

        return response()->json(['data' => $survey_data], 200);
    }


    public function store(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = $user->id;

        // Validate the request input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        // Create a new survey
        $survey = new Survey();
        $survey->user_id = $userId;
        $survey->slug = trim($request->input('slug'));
        $survey->title = $request->input('title');
        $survey->description = $request->input('description');
        $survey->is_active = $request->input('is_active', 0);
        $survey->created = now();
        $survey->modified = now();

        if ($survey->save()) {
            // Prepare data for the notification
            $notificationData = [
                'user_id' => $userId,
                'notification_type' => 'Survey',
                'status' => 0,
                'admin_status' => 2,
                'name' => $request->title,
                'member_id' => 0,
                'desc' => $request->description,
                'date' => $survey->created,
                'created' => now(),
                'modified' => now()
            ];

            // Create a new notification
            Notification::create($notificationData);

            return response()->json([
                'message' => 'Survey created successfully',
                'survey' => $survey,
            ], 201);
        } else {
            return response()->json(['message' => 'Failed to create survey! Please try again'], 500);
        }
    }



    public function edit($id)
    {
        // Find the member by its ID
        $survey = Survey::find($id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found'
            ], 404);
        }
        $survey_data = [
            'id' => $survey->id,
            'title' => $survey->title,
            'description' => $survey->description,
        ];

        return response()->json([
            'success' => true,
            'survey_data' => $survey_data
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:surveys,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $survey = Survey::find($request->id);

        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }


        $survey->slug = trim($request->input('slug'));
        $survey->title = $request->input('title');
        $survey->description = $request->input('description');
        $survey->is_active = $request->input('is_active', 0);
        $survey->modified = now();

        $survey->save();

        return response()->json(['message' => 'Survey updated successfully', 'data' => $survey], 200);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $survey = Survey::find($request->id);

        if (!$survey) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found'
            ], 404);
        }

        $survey->delete();

        return response()->json([
            'success' => true,
            'message' => 'Survey deleted successfully'
        ]);
    }

    public function addSurveyQuestion(Request $request, $id)
    {
        // dd($request->all());
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = $user->id;

        // Find the survey by ID
        $survey = Survey::find($id);
        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }

        // Validate the request input
        $request->validate([
            'questions' => 'required|string|max:255',
            'options' => 'required|array',
            'answer' => 'required|array'
        ]);

        // Prepare data for the survey question
        $data = $request->all();
        $data['created'] = now();
        $data['questions'] = $request->questions;
        $data['options'] = implode(",", $data['options']);
        $data['answer'] = implode(",", $data['answer']);
        $data['survey_id'] = $survey->id;
        $data['user_id'] = $userId;
        $data['is_active'] = $request->has('is_active') ? $request->is_active : '0';

        // Create a new survey question
        $surveyQuestion = new SurveyQuestion($data);

        if ($surveyQuestion->save()) {
            return response()->json([
                'message' => 'Survey question created successfully',
                'question' => $surveyQuestion
            ], 201);
        } else {
            return response()->json(['message' => 'Failed to create survey question! Please try again'], 500);
        }
    }

    public function surveyQuestionsList(Request $request, $id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        $questionsDet = SurveyQuestion::where('user_id', $userId)
            ->where('survey_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        $questionsDet = $questionsDet->map(function ($question) {
            $question->options = explode(",", $question->options);
            $question->answer = explode(",", $question->answer);
            return $question;
        });

        return response()->json([
            'questionsDet' => $questionsDet
        ]);
    }

    public function editSurveyQuestion(Request $request, $surveyid, $id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        $surveyQuestion = SurveyQuestion::where('id', $id)
            ->where('user_id', $userId)
            ->where('survey_id', $surveyid)
            ->first();

        if (!$surveyQuestion) {
            return response()->json(['message' => 'Survey question not found'], 404);
        }

        return response()->json([
            'question' => $surveyQuestion
        ]);
    }

    public function updateSurveyQuestion(Request $request, $surveyid, $id)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        $surveyQuestion = SurveyQuestion::where('id', $id)
            ->where('user_id', $userId)
            ->where('survey_id', $surveyid)
            ->first();

        if (!$surveyQuestion) {
            return response()->json(['message' => 'Survey question not found'], 404);
        }

        $request->validate([
            'questions' => 'required|string|max:255',
            'options' => 'required|array',
            'answer' => 'required|array'
        ]);

        $surveyQuestion->questions = $request->questions;
        $surveyQuestion->options = implode(",", $request['options']);
        $surveyQuestion->answer = implode(",", $request['answer']);
        $surveyQuestion->is_active = $request->has('is_active') ? $request->is_active : '0';
        $surveyQuestion->modified = now();

        if ($surveyQuestion->save()) {
            return response()->json([
                'message' => 'Survey question updated successfully',
                'question' => $surveyQuestion
            ]);
        } else {
            return response()->json(['message' => 'Failed to update survey question! Please try again'], 500);
        }
    }

    public function deleteSurveyQuestion(Request $request, $surveyid, $id)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = $user->id;

        // Find the survey question
        $surveyQuestion = SurveyQuestion::where('id', $id)
            ->where('user_id', $userId)
            ->where('survey_id', $surveyid)
            ->first();

        if (!$surveyQuestion) {
            return response()->json(['message' => 'Survey question not found'], 404);
        }

        // Delete the survey question
        if ($surveyQuestion->delete()) {
            return response()->json(['message' => 'Survey question deleted successfully']);
        } else {
            return response()->json(['message' => 'Failed to delete survey question! Please try again'], 500);
        }
    }


    public function feedbackQuestionsList(Request $request, $userid)
    {

        $user = Campaign_user::find($userid);
        // dd($user);

        if ($user && $user->user_type == '2') {
            $questions = SurveyQuestion::where('user_id', $userid)
                ->orderBy('id', 'desc')
                ->get();

            $questions_data = [];

            if (!$questions->isEmpty()) {
                foreach ($questions as $key => $question) {
                    $createdDate = date('d-m-Y', strtotime($question->created));
                    $questions_data[$key] = [
                        'id' => $question->id,
                        'questions' => $question->questions ?? '',
                        'created' => $createdDate ?? null,
                    ];
                }
            }

            return response()->json(['data' => $questions_data], 200);
        }

        return response()->json(['data' => []], 200);
    }

    public function surveyReply(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        $getVoter = ImportVotersdata::where('user_id', $userId)->first();
        // If no voter is found, set the voters_id to null
        $voterId = $getVoter ? $getVoter->id : null;

        $questionId = $request->input('question_id');
        $surveyId = $request->input('survey_id');

        $check = GrassrooterFeedback::where([
            ['voters_id', $voterId],
            ['question_id', $questionId],
            ['survey_id', $surveyId],
        ])->first();

        if ($check) {
            return response()->json(['message' => 'Your response is already available for this survey'], 409);
        }

        $data = [];
        $data['voters_id'] = $voterId;
        $data['options'] = $request->input('answer');
        $data['created'] = now();
        $data['question_id'] = $questionId;
        $data['grassrooters_id'] = $userId;
        $data['modified'] = now();
        $data['survey_id'] = $surveyId;
        $data['status'] = '1';

        $postres = $request->input('answer');

        if ($request->has('answer')) {
            $questionsDet = SurveyQuestion::where([
                ['id', $questionId],
                ['survey_id', $surveyId],
                ['user_id', $userId],
            ])->first();

            if ($questionsDet) {
                // Decode the answer and options as arrays
                $responseArray = explode(",", $questionsDet->answer);
                $optionsArray = explode(",", $questionsDet->options);

                if (is_array($optionsArray) && is_array($responseArray)) {
                    $index = array_search($postres, $optionsArray);
                    if ($index !== false && isset($responseArray[$index])) {
                        // Ensure response is an integer
                        $data['response'] = intval($responseArray[$index]);
                    } else {
                        $data['response'] = null;
                    }
                } else {
                    return response()->json(['message' => 'Invalid question options or answers'], 400);
                }
            }
        }

        $feedback = new GrassrooterFeedback($data);
        if ($feedback->save()) {
            return response()->json(['message' => 'Feedback updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to update Questions! please try again'], 500);
        }
    }

    // public function surveyReply(Request $request)
    // {
    //     $user = Auth::guard('api')->user();
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     $userId = $user->id;

    //     $getVoter = ImportVotersdata::where('user_id', $userId)->first();

    //     // If no voter is found, set the voters_id to null
    //     $voterId = $getVoter ? $getVoter->id : null;

    //     $questionId = $request->input('question_id');
    //     $surveyId = $request->input('survey_id');

    //     $check = GrassrooterFeedback::where([
    //         ['voters_id', $voterId],
    //         ['question_id', $questionId],
    //         ['survey_id', $surveyId],
    //     ])->first();

    //     if ($check) {
    //         return response()->json(['message' => 'Your response is already available for this survey'], 409);
    //     }

    //     $data = $request->all();
    //     $data['voters_id'] = $voterId;
    //     $data['options'] = $request->input('options');
    //     $data['created'] = now();
    //     $data['grassrooters_id'] = $userId;
    //     $data['survey_id'] = $surveyId;
    //     $data['modified'] = now();
    //     $data['status'] = '1';

    //     if ($request->has('options')) {
    //         $questionsDet = SurveyQuestion::where([
    //             ['id', $questionId],
    //             ['survey_id', $surveyId],
    //             ['user_id', $userId],
    //         ])->first();

    //         if ($questionsDet) {
    //             $responseArray = explode(",", $questionsDet->answer);
    //             $optionsArray = explode(",", $questionsDet->options);

    //             if (is_array($optionsArray) && is_array($responseArray)) {
    //                 $index = array_search($request->input('options'), $optionsArray);
    //                 if ($index !== false) {
    //                     $data['response'] = $responseArray[$index];
    //                 }
    //             } else {
    //                 // Handle the case where decoding fails or arrays are not valid
    //                 return response()->json(['message' => 'Invalid question options or answers'], 400);
    //             }
    //         }
    //     }

    //     $feedback = new GrassrooterFeedback($data);
    //     if ($feedback->save()) {
    //         return response()->json(['message' => 'Feedback updated successfully'], 200);
    //     } else {
    //         return response()->json(['message' => 'Failed to update Questions! please try again'], 500);
    //     }
    // }
}
