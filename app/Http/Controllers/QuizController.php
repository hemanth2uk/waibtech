<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questions;
use App\Models\Answers;
use App\Models\Quiz;
use App\Models\QuizSection;
use App\Models\QuizDetails;
use PhpParser\Node\Expr\FuncCall;

class QuizController extends Controller
{
    public function index()
    {
        return view('startQuiz');
    }

    public function quizDropDown()
    {
       $loginUserID=auth()->id();
       $quizDropDown= Quiz::get();
       $disp='';
     
       if($quizDropDown!=[])
       {
       foreach($quizDropDown as $quizDrpDwn)
       {

          $disp.='<option value="'.$quizDrpDwn->id.'">'.$quizDrpDwn->quiz_name.'</option>';
        
    
       }
    }
    echo $disp;
}
public function startQuiz(Request $request)
{
    $userName = $request->name;
    $quizId = $request->quizId;
    $quiz = Quiz::where('id', $quizId)->first();
    session(['userName' => $userName, 'quizId' => $quizId]);
    $disp = '';
    $quest = Questions::where('quiz_id', $quizId)->get();
    $questcount = Questions::where('quiz_id', $quizId)->count();

    $loop = 1;
    foreach ($quest as $questList) {
        $class = ($loop == 1) ? 'question active' : 'question';
        $disp .= '<div class="'.$class.'" data-question="'.$loop.'">';
        $disp .= '<div class="form-group">';
        $disp .= '<label>'.$questList->question_name.'</label>';
        $disp .= '<input type="hidden" name="questionid[]" value="'.$questList->id.'">';
        // Set a common name attribute for radio buttons within each question
        $nameAttribute = 'question_'.$loop;
        
        $loop2 = 1;
        $answer = Answers::where('question_id', $questList->id)->get();
        foreach ($answer as $ans) {
            $disp .= '<div class="form-check">';
            $disp .= '<input class="form-check-input" type="radio" name="'.$nameAttribute.'" id="question'.$loop.'_option'.$loop2.'" value="'.$ans->id.'" >';
            $disp .= '<label class="form-check-label" for="question'.$loop.'_option'.$loop2.'">'.$ans->answer_name.'</label>';
            $disp .= '</div>';
            $loop2++;
        }
        $disp .= '<div class="form-check"><input class="form-check-input" type="hidden" name="timetaken" id="timetaken" value="0"/></div>';
        $disp .= '</div>';
        $disp .= '</div>';
        $loop++;
    }
    $output = [
        'message' => 'Quiz session started',
        'userName' => $userName,
        'quizData' => $quiz,
        'totalquestcount' => $questcount,
        'quizId' => $quizId,
        'quizHtml' => $disp
    ];
    echo json_encode($output);
}
public function submitForm(Request $request)
{
    // Retrieve all question IDs and answers
    $questionIds = $request->input('questionid', []);

    $responses = [];
    $userName = session('userName');
    $quizId = session('quizId');
    $startQuest = new QuizSection();
    $startQuest->quizid = $quizId;
    $startQuest->username = $userName;
    $startQuest->timetaken = $request->timetaken;
    $startQuest->save();
    $insertedId = $startQuest->id;
    $totalscore = 0;

    foreach ($questionIds as $key => $questionId) {
        $answerKey = 'question_' . ($key + 1); // Match the question key
        $answerId = $request->input($answerKey); 

        // Fetch the correct answer for the question
        $correctAnswer = Answers::where('question_id', $questionId)
                                ->where('is_correct_answer', 1)
                                ->first();

        // Check if the provided answer matches the correct answer
        $correct_ans = ($correctAnswer && $correctAnswer->id == $answerId) ? 1 : 0;

        // Increment total score if the answer is correct
        if ($correct_ans == 1) {
            $questionData = Questions::find($questionId);
            $totalscore += $questionData->score_per_quest;
        }

        // Save quiz details for each question
        $quizDetail = new QuizDetails();
        $quizDetail->section_id = $insertedId;
        $quizDetail->question_id = $questionId;
        $quizDetail->answer_id = $answerId;
        $quizDetail->is_correct_anser = $correct_ans;
        $quizDetail->save();
    }

    // Update total score for the quiz section
    $updateData = ['score' => $totalscore];
    $updated = QuizSection::where('id', $insertedId)->update($updateData);

    if ($updated) {
        return response()->json(['status' => 200,'quizID'=>$insertedId]);
    } else {
        return response()->json(['status' => 400]);
    }
}

public function submitOverview()
{
return view('overview');
}
public function viewOverview(Request $request)
{

// $quizLoop= QuizSection::where('id',$quizId)->get();

// return view('overview');
$userName = session('userName');
$quizId = session('quizId');
$quizsess=QuizSection::where('quizid',$quizId)->where('username',$userName)->first();
$quiz = Quiz::where('id', $quizId)->first();
$disp = '';
$quest = Questions::where('quiz_id', $quizId)->get();
$questcount = Questions::where('quiz_id', $quizId)->count();


$loop = 1;
foreach ($quest as $questList) {
    $class = ($loop == 1) ? 'question active' : 'question';
    $disp .= '<div class="'.$class.'" data-question="'.$loop.'">';
    $disp .= '<div class="form-group">';
    $disp .= '<label>'.$questList->question_name.'</label>';
    $disp .= '<input type="hidden" name="questionid[]" value="'.$questList->id.'">';
    // Set a common name attribute for radio buttons within each question
    $nameAttribute = 'question_'.$loop;
    
    $loop2 = 1;
    $answer = Answers::where('question_id', $questList->id)->get();
    foreach ($answer as $ans) {
 $answerInputted = QuizDetails::where('is_correct_anser', 1)->where('section_id', $quizsess->id)->where('question_id',$questList->id)->where('answer_id',$ans->id)->count();
        $disp .= '<div class="form-check">';
        if($answerInputted>0)
{        $disp .= '<input class="form-check-input" type="radio" name="'.$nameAttribute.'" id="question'.$loop.'_option'.$loop2.'" value="'.$ans->id.'" checked disabled>';
}
else
{
        $disp .= '<input class="form-check-input" type="radio" name="'.$nameAttribute.'" id="question'.$loop.'_option'.$loop2.'" value="'.$ans->id.'"  disabled>';

}        

$disp .= '<label class="form-check-label" for="question'.$loop.'_option'.$loop2.'">'.$ans->answer_name.'</label>';
        $disp .= '</div>';
        $loop2++;
    }
    $disp .= '<div class="form-check"><input class="form-check-input" type="hidden" name="timetaken" id="timetaken" value="0"/></div>';
    $disp .= '</div>';
    $disp .= '</div>';
    $loop++;
}
$status=($quizsess->score>=$quiz->passmark)?'<strong style="color:green;">Passed</strong>':'<strong style="color:red;">Failed</strong>';
$output = [
    'quizData' => $quiz,
    'totalquestcount' => $questcount,
    'quizId' => $quizId,
    'quizHtml' => $disp,
    'score'=>$quizsess->score,
    'status'=>$status
];
echo json_encode($output);
}
}

