<?php

namespace App\Http\Controllers;

use App\Models\Answered;
use App\Models\Earning;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    // main mobile app dashboard
    // return the details of the dashboard for a given user.


    public function dashboard()
    {
        // logged is user
        $user = 2;
        // get two random questions
        $random_questions = Question::inRandomOrder()->limit(2)->get();
        // total answered
        $total_answered = Answered::where('user_id', $user)->count();
        // total questions
        $total_questions = Question::all()->count();
        // new questions will be dedcuted from the total answered questions by the user
        $new_questions = $total_questions - $total_answered;
        // get amount earned
        $earned = Earning::where('user_id', $user);

        if ($earned->exists()) {
            $earned = $earned->limit(1)->pluck('amount');
        } else {
            $earned = 0;
        }

        return json_encode(array(
            "random_question" => $random_questions,
            "total_answered" => $total_answered,
            "new_questions" => $new_questions,
            "earned" => $earned
        ), 200);
    }

    // Return questions for user to be answered
    // they have to be questions he has not yet answered

    public function questions()
    {
        $questions = DB::table('questions AS question')
            ->select('question.id', 'question.question', 'question.amount', 'question.duration')
            ->leftJoin('answereds AS answered', 'answered.question_id', '=', 'question.id')
            ->whereNull('answered.question_id')
            ->where('answer_id', '!=', null)->get();
        return $questions;
    }

    // single question to answer
    public function singleQuestion($id)
    {
        $question = Question::find($id);
        if ($question) {
            return $question;
        } else {
            return json_encode(array("message" => "Not found"));
        }
    }

    // answer question
    public function answerQuestion(Request $request)
    {

        $user = 1; //suposed to be the authenticated user

        $question = $request->question;
        $answer = $request->answer;

        // check if there is no null value
        if ($user == "" || $question == "" || $answer == "") {
            // return back with error
            return json_encode(array("message" => "An error occured. Please try again later"));
        } else {

            // if no errors insert into the answered and the earnings table
            //check if answer is correct

            $query = Question::find($question);
            if ($answer == $query->answer_id) {

                // first check if question already answered to avoid double answering
                $if_answered = Answered::where('user_id', $user)->where('question_id', $question)->exists();
                if ($if_answered) {

                    return json_encode(array("message" => "Question already answered"));
                } else {
                    $answered = Answered::create([
                        'question_id' => $question,
                        'user_id' => $user
                    ]);
                }

                if ($answered) {
                    // check if user already has a row in the table
                    $user_exists = Earning::where('user_id', $user);
                    if ($user_exists->exists()) {
                        Earning::find($user_exists->first()->id)
                            ->update([
                                'user_id' => $user,
                                'amount' => $query->amount + $user_exists->first()->amount
                            ]);
                    } else {
                        //create a new row for user
                        Earning::create([
                            'user_id' => $user,
                            'amount' => $question->amount
                        ]);
                    }
                }
                return json_encode(array("message" => "Question answered successfully"));
            } else {
                return json_encode(array("message" => "Wrong answer provided"));
            }
        }
    }
}
