<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Questions;
use App\Models\Answers;
use App\Models\Quiz;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Question\Question;

class Users extends Controller
{
   public function dashboard()
   {
   $loginUserID=auth()->id();

   $userFullData= User::where('id',$loginUserID)->first();
   $passData['userData']=$userFullData;
   return view('dashboard')->with('passData', $passData);
   }
   public function users()
   {
      
       return view('users');
   }
   public function quiz()
   {
      
       return view('quiz_list');
   }
   public function listUsers()
   {
      $loginUserID=auth()->id();
      $userData= User::get();
      $disp='';
      $i=1;
      if($userData!=[])
      {
      foreach($userData as $usrList)
      {
         $disp.='<tr>';
         $disp.='<td>'.$i.'</td>';
         $disp.='<td>'.$usrList->name.'</td>';
         $disp.='<td>'.$usrList->email.'</td>';
         $disp.='<td>'.$usrList->created_at.'</td>';
         $disp.='</tr>';
         $i++;
      }
   }
   else
   {
      $disp.='<tr>';
      $disp.='<td></td>';
      $disp.='<td>No data !</td>';
      $disp.='<td></td>';
      $disp.='<td></td>';
      $disp.='</tr>';
   }
      echo $disp;
   }
   public function saveUser(Request $request)
   {
      $loginUserID=auth()->id();
      $userData= User::where('id',$loginUserID)->first();

$user = new User();
$user->name = $request->name;
$user->email = $request->email;
$user->parent_id = $loginUserID;
$user->password = Hash::make('123456'); 
$user->first_level_of=$loginUserID; 
if($user->save())
{
   $response=array('status'=>200,'msg'=>'sucessfully saved  User');
}
     
      echo json_encode($response);
   }
   public function listQuiz()
   {
      $loginUserID=auth()->id();
      $quizData= Quiz::get();
      $disp='';
      $i=1;
      if($quizData!=[])
      {
      foreach($quizData as $quizList)
      {
         $disp.='<tr>';
         $disp.='<td>'.$i.'</td>';
         $disp.='<td>'.$quizList->quiz_name.'</td>';

         $disp.='<td>'.$quizList->created_at.'</td>';
         $disp.='<td><button type="button" class="btn btn-primary" onclick="view_modal('.$quizList->id.')">View</button><button type="button" class="btn btn-warning" onclick="delQuiz('.$quizList->id.')">Delete</button></td>';
         $disp.='</tr>';
         $i++;
      }
   }
   else
   {
      $disp.='<tr>';
      $disp.='<td></td>';
      $disp.='<td>No data !</td>';
      
      $disp.='<td></td>';
      $disp.='</tr>';
   }
      echo $disp;
   }
   public function saveQuiz(Request $request)
  
   {
  
      $loginUserID=auth()->id();

$questions = new Questions;
// $answers = new Answers;
$quiz = new Quiz;

if($request->quiz_type=='new' ||$request->quiz_type==null )
{
   $quiz->quiz_name = $request->name;
   $quiz->duration = $request->quiz_duration;
   $quiz->passmark = $request->quiz_passmark;
   $quiz->save();
   // Get the ID of the newly inserted quiz
   
   $quizId = $quiz->id;
}
else if($request->quiz_type=='existing')
{
   $quizId = $request->selectname;
}

$answers=$request->answers;
if($quizId)
{
   $questions->quiz_id=$quizId;
   $questions->question_name=$request->question;
   $questions->score_per_quest=$request->question_marks;
   $questions->save();
   $questionId = $questions->id; // Get the ID of the newly inserted question
   if($questionId)
   {
   foreach($answers as $ans)
   {
       $answers = new Answers;
      if($ans['correct']==1)
      {
         $answers->is_correct_answer=1;  
      }
      else
      {
         $answers->is_correct_answer=0;  
      }
       
       $answers->quiz_id=$quizId; 
       $answers->question_id=$questionId; 
       $answers->answer_name=$ans['text']; 
       $answers->save();
   }
   }
}

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
         $disp.='<option value="">Select a Quiz</option>';
         $disp.='<option value="'.$quizDrpDwn->id.'">'.$quizDrpDwn->quiz_name.'</option>';
       
   
      }
   }

      echo $disp;
   }
   public function questionDropdown(Request $request)
   {
      $loginUserID=auth()->id();
      $qsrDropDown= Questions::where('quiz_id',$request->quizid)->get();
      $disp='';
    
      if($qsrDropDown!=[])
      {
      foreach($qsrDropDown as $qsrDropDowns)
      {
       
         $disp.='<option value="'.$qsrDropDowns->id.'">'.$qsrDropDowns->question_name.'</option>';
       
   
      }
   }

      echo $disp;
   }

   public function ansDropdown(Request $request)
   {
     
      $ansDropDown= Answers::where('question_id',$request->questid)->get();
      $newArray=array();
      foreach($ansDropDown as $drpdwn)
      { 
         $correct=($drpdwn->is_correct_answer==1)?true:false;
         $newArray[]=array('text'=>$drpdwn->answer_name,'correct'=>$correct);
      }
      return $newArray;
   }
   public function deleteQuiz(Request $request)

   {
      $quiz = Quiz::find($request->quizid);
      $anwers=Answers::find($request->quizid);
      $question=Questions::find($request->quizid);
      $quiz->delete();
      $anwers->delete();
      $question->delete();
   
   
   }
   public function quizData(Request $request)
{
    $quiz=Quiz::where('id',$request->quizid)->first();
    echo json_encode($quiz);
}
}
