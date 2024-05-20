@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Quiz
        <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Add Quiz
              </button>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                <th>S/no</th>
                  <th>Quiz Name</th>
                  <th>Created Date</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody id="dynamicTable">
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Modal -->
  <div class="modal fade in" id="modal-default">
  <div class="modal-dialog modal-lg">
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title">Add Quiz</h4>
        </div>
        <div class="modal-body">
        <div class="form-group">
                    <label for="quiz-selection">Select Quiz</label>
                    <div>
                        <label>
                            <input type="radio"  name="quiz-type" value="new" checked> New Quiz
                        </label>
                        <label>
                            <input type="radio" name="quiz-type" value="existing"> Select from existing
                        </label>
                    </div>
                    <div id="quiz-inputs">
                        <input type="text" class="form-control" id="new-quiz" placeholder="Enter New Quiz Name">
                    </div>
                    <div id="quiz-select" style="display: none;">
                        <select class="form-control" id="existing-quiz" onchange="onchangeQuiz(this.value)">
                           
                            <!-- Add options dynamically using JavaScript -->
                        </select>
                    </div>
                </div>
                <input type="hidden" id="quiztypes"/>
                <div class="form-group">
            <label for="quiz_passmark">Total Passmark</label>
            <input type="number" class="form-control" id="quiz_passmark" placeholder="PassMark">
          </div>
                <div class="form-group">
            <label for="quiz_duration">Quiz total Duration</label>
            <input type="number" class="form-control" id="quiz_duration" placeholder="Duration">
          </div>
          <div class="form-group">
            <label for="question">Question</label>
            <input type="text" class="form-control" id="question" placeholder="Enter Question">
          </div>
          <div class="form-group">
            <label for="question_marks">Mark For this  Question</label>
            <input type="number" class="form-control" id="question_marks" placeholder="Enter Marks">
          </div>
          <div class="form-group">
            <label>Answers</label>
            <div id="answers-container"></div>
            <button type="button" class="btn btn-default" id="add-answer-button">Add Answer</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveQuiz()">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade in" id="modal-view">
    <div class="modal-dialog">
      <div class="modal-content" style="border-radius:10px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title">Quiz Action</h4>
        </div>
        <div class="modal-body">
        <div class="form-group">
        <!-- <label for="new-quiz">Quiz</label>
        <input type="text" class="form-control" id="new-quiz" placeholder="Enter New Quiz Name"> -->
        </div>

        <div class="form-group">
                    <label for="quiz-selection">Select Questions</label>
                  

                    <div id="quiz-select2" >
                        <select class="form-control questIDS" id="Questions" onchange="changeQuestion(this.value)">
                           
                            <!-- Add options dynamically using JavaScript -->
                        </select>
                    </div>
                </div>
                
   
          <div class="form-group">
            <!-- <label for="question">Question</label>
            <input type="text" class="form-control" id="question_edit" placeholder="Enter Question"> -->
          </div>
          <div class="form-group">
            <label>Answers</label>
            <div id="answers-containers"></div>
            <!-- <button type="button" class="btn btn-default" id="add-answer-buttons">Add Answer</button> -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary pull-right" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary" onclick="saveQuiz()">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    
 
    // Function to fetch data from the database
    function fetchAnswers(questids) {
        $('#answers-containers').empty();
        // Perform an AJAX request to fetch answers from the database
        $.ajax({
            url: '{{route("answer.ansDropdown")}}', // URL to your backend route for fetching answers
            method: 'POST',
            data:{questid:questids,_token:"{{ csrf_token() }}"},
            success: function(response) {
                // Once the data is fetched successfully, generate input fields and radio buttons
                response.forEach(answer => {
                    const container = document.getElementById('answers-containers');

                    const answerDiv = document.createElement('div');
                    answerDiv.className = 'form-group';
                    answerDiv.style.display = 'flex';
                    answerDiv.style.alignItems = 'center';
                    answerDiv.style.marginBottom = '10px';

                    const answerInput = document.createElement('input');
                    answerInput.type = 'text';
                    answerInput.className = 'form-control';
                    answerInput.placeholder = 'Enter Answer';
                    answerInput.style.flex = '1';
                    answerInput.value = answer.text; // Set the value from the database

                    const correctLabel = document.createElement('label');
                    correctLabel.textContent = ' Correct Answer';
                    correctLabel.style.marginLeft = '10px';
                    correctLabel.style.display = 'flex';
                    correctLabel.style.alignItems = 'center';

                    const correctRadio = document.createElement('input');
                    correctRadio.type = 'radio';
                    correctRadio.name = 'correct-answer';
                    correctRadio.className = 'correct-answer-radio';
                    correctRadio.style.marginLeft = '5px';
                    if (answer.correct) {
                        correctRadio.checked = true; // Check the correct answer based on data from the database
                    }

                    correctLabel.appendChild(correctRadio);

                    answerDiv.appendChild(answerInput);
                    answerDiv.appendChild(correctLabel);
                    container.appendChild(answerDiv);
                });
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('An error occurred:', error);
            }
        });
    }

    // Call the fetchAnswers function when the page loads
   

   

    quizSelectDropdown();
    document.getElementById('add-answer-button').addEventListener('click', function() {
      const container = document.getElementById('answers-container');

      const answerDiv = document.createElement('div');
      answerDiv.className = 'form-group';
      answerDiv.style.display = 'flex';
      answerDiv.style.alignItems = 'center';
      answerDiv.style.marginBottom = '10px';

      const answerInput = document.createElement('input');
      answerInput.type = 'text';
      answerInput.className = 'form-control';
      answerInput.placeholder = 'Enter Answer';
      answerInput.style.flex = '1';

      const correctLabel = document.createElement('label');
      correctLabel.textContent = ' Correct Answer';
      correctLabel.style.marginLeft = '10px';
      correctLabel.style.display = 'flex';
      correctLabel.style.alignItems = 'center';

      const correctRadio = document.createElement('input');
      correctRadio.type = 'radio';
      correctRadio.name = 'correct-answer';
      correctRadio.className = 'correct-answer-radio';
      correctRadio.style.marginLeft = '5px';

      correctLabel.appendChild(correctRadio);

      answerDiv.appendChild(answerInput);
      answerDiv.appendChild(correctLabel);
      container.appendChild(answerDiv);
    });

    function saveQuiz() {
      // Collect the quiz name
      const name = document.getElementById('new-quiz').value;
      const selectname = document.getElementById('existing-quiz').value;

      // Collect the question
      const question = document.getElementById('question').value;

      // Collect the answers
      const answerElements = document.querySelectorAll('#answers-container .form-group');
      const answers = [];
      answerElements.forEach(element => {
        const answerText = element.querySelector('input[type="text"]').value;
        const isCorrect = element.querySelector('input[type="radio"]').checked ? 1 : 0;
        answers.push({ text: answerText, correct: isCorrect });
      });

      // Here you would handle saving the data, e.g., sending it to a server
      console.log({ name, question, answers });
      var quiz_type = $('input[name="quiz-type"]').val();
      var quiz_passmark = $('#quiz_passmark').val();
      var quiz_duration = $('#quiz_duration').val();
      // Close the modal
    
      $.ajax({
        url: '{{ route("save.quiz") }}', // The URL to the controller method
        method: 'POST',
        data: {
            name: name,
            selectname:selectname,
            question: question,
            quiz_passmark: quiz_passmark,
            quiz_duration: quiz_duration,
            question_marks:$('#question_marks').val(),
            quiz_type:$('#quiztypes').val(),
            answers: answers,
            _token:"{{ csrf_token() }}", // CSRF token for security
        },
        success: function(response) {
            // Handle the response
            console.log(response);
            // Close the modal
            listQuizes();
            $('#modal-default').modal('hide');
            // Optionally, refresh the page or update the table with new data
        },
        error: function(xhr, status, error) {
            // Handle any errors
            console.error('An error occurred:', error);
        }
    });
    }

    listQuizes();
    function listQuizes()
    {
      $.ajax({
                type: 'GET',
                url: "{{ route('quiz.list')}}", // Replace with your server-side script URL
                success: function(response){
                    // Handle success response
          
            $('#dynamicTable').html(response);
            $('#modal-default').modal('hide');
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
    }
    function quizSelectDropdown()
    {
      $.ajax({
                type: 'GET',
                url: "{{ route('quiz.dropdown')}}", // Replace with your server-side script URL
                success: function(response){
                    // Handle success response
          
            $('#existing-quiz').html(response);
         
         
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
    }
    $(document).ready(function() {
        $('input[name="quiz-type"]').change(function() {
            if ($(this).val() === 'new') {
                $('#quiz-inputs').show();
                $('#quiz-select').hide();
                $('#quiztypes').val('new');
                $('#quiz_duration').prop('disabled', false);
                $('#quiz_passmark').prop('disabled', false);
                $('#quiz_passmark').val('');
              $('#quiz_duration').val('');
              $('#existing-quiz').val('');
            } else {
                $('#quiz-inputs').hide();
                $('#quiz-select').show();
                $('#quiztypes').val('existing');
                $('#quiz_duration').prop('disabled', true);
                $('#quiz_passmark').prop('disabled', true);
                
            }
        });
    });
function changeQuestion(questid)
{
    fetchAnswers(questid);
}
    function view_modal(quizId)
    {
        $('#modal-view').modal('show');
        
        $(document).ready(function() {
        
        });
        $.ajax({
                type: 'POST',
                url: "{{ route('quest.dropdown')}}",
                data:{quizid:quizId,_token:"{{ csrf_token() }}"}, // Replace with your server-side script URL
                success: function(response){
                    // Handle success response
                  
            $('#Questions').html(response);
            var questid= $('.questIDS').val();
            fetchAnswers(questid);
         
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
    }

    document.getElementById('add-answer-buttons').addEventListener('click', function() {
      const container = document.getElementById('answers-containers');

      const answerDiv = document.createElement('div');
      answerDiv.className = 'form-group';
      answerDiv.style.display = 'flex';
      answerDiv.style.alignItems = 'center';
      answerDiv.style.marginBottom = '10px';

      const answerInput = document.createElement('input');
      answerInput.type = 'text';
      answerInput.className = 'form-control';
      answerInput.placeholder = 'Enter Answer';
      answerInput.style.flex = '1';

      const correctLabel = document.createElement('label');
      correctLabel.textContent = ' Correct Answer';
      correctLabel.style.marginLeft = '10px';
      correctLabel.style.display = 'flex';
      correctLabel.style.alignItems = 'center';

      const correctRadio = document.createElement('input');
      correctRadio.type = 'radio';
      correctRadio.name = 'correct-answer';
      correctRadio.className = 'correct-answer-radio';
      correctRadio.style.marginLeft = '5px';

      correctLabel.appendChild(correctRadio);

      answerDiv.appendChild(answerInput);
      answerDiv.appendChild(correctLabel);
      container.appendChild(answerDiv);
    });

    function delQuiz(quizId)
    {
Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete Quiz!"
}).then((result) => {
  if (result.isConfirmed) {
   
    $.ajax({
                type: 'POST',
                url: "{{ route('delete.quiz')}}",
                data:{quizid:quizId,_token:"{{ csrf_token() }}"}, // Replace with your server-side script URL
                success: function(response){
                    listQuizes();
    Swal.fire({
      title: "Deleted!",
      text: "Quiz has been deleted.",
      icon: "success"
    });
         
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
 
  }
});
    }
    function onchangeQuiz(quizId)

    {  $.ajax({
                type: 'POST',
                url: "{{ route('onchange.quiz.data')}}",// Replace with your server-side script URL
                data:{quizid:quizId,_token:"{{ csrf_token() }}"}, 
                success: function(response){
                    // Handle success response
               var jsonObj = $.parseJSON(response);
              $('#quiz_passmark').val(jsonObj.passmark);
              $('#quiz_duration').val(jsonObj.duration);
         
                },
                error: function(xhr, status, error){
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });
          }
  </script>
@endsection
