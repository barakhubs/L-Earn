<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMainController extends Controller
{
    public function questions()
    {
        $questions = Question::latest()->paginate(10);
        return view('admin.questions', compact('questions'));
    }

    public function storeQuestion(Request $request)
    {
        $this->validate($request, [
            'question' => 'required',
            'amount' => 'required',
            'duration' => 'required'
        ]);

        Question::create([
            'question' => $request->question,
            'amount' => $request->amount,
            'duration' => $request->duration
        ]);

        return redirect()->back()->with('message', 'Question added successfully');
    }


    public function updateQuestion(Request $request, $id)
    {
        $this->validate($request, [
            'question' => 'required',
            'amount' => 'required',
            'duration' => 'required'
        ]);

        Question::find($id)->update([
            'question' => $request->question,
            'amount' => $request->amount,
            'duration' => $request->duration
        ]);

        return redirect()->back()->with('message', 'Question added successfully');
    }

    public function addQuestionAnswer(Request $request)
    {
        Question::find($request->question)->update([
            'answer_id' => $request->answer
        ]);

        return redirect()->back()->with('message', 'Anser assigned successfully');
    }


    public function answers()
    {
        $questions = Question::latest()->paginate(10);

        return view('admin.answers', compact('questions'));
    }

    public function storeAnswer(Request $request)
    {
        $this->validate($request, [
            'answer' => 'required'
        ]);

        Answer::create([
            'question_id' => $request->question,
            'answer' => $request->answer
        ]);

        return redirect()->back()->with('message', 'Anser added successfully');
    }

    public function editAnswer()
    {
        //
    }

    public function earnings()
    {
        //
    }

    public function payments()
    {
        //
    }


    // delete function which deletes anything as long as the parameters are pssed

    public function destroy($table, $id)
    {
        $query = DB::table($table)->where('id', $id);
        $query->delete();
        return redirect()->back();
    }
}
