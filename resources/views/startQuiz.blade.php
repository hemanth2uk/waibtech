<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Quiz Page</title>
    <style>
        .question { display: none; }
        .question.active { display: block; }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h1, h2, h3 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        #quizForm .question {
            margin-bottom: 30px;
            text-align: left;
            display: none;
        }
        #quizForm .question.active {
            display: block;
        }
        .question label {
            display: inline;
            font-weight: normal;
            text-align: left;
        }
        .question .question-text {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        .question input[type="radio"] {
            margin-right: 10px;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #assessmentMessage {
            text-align: center;
            font-size: 18px;
        }
        #timer {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Quiz!</h1>
        <form id="startForm">
            <label for="name">Select Quiz:</label>
            <select required style="width:50%" id="quizes"></select>
            <label for="name">Enter your name:</label>
            <input type="text" id="name" name="name" style="width:50%" required><br>
            <button type="button" id="startQuiz">Start Quiz</button>
        </form>
    </div>

    <div class="container" id="quiz" style="display: none;">
        <h2 id="quiztitle"></h2>
        <form method="post" id="quizForm">
            @csrf
            <div id="questions">
                <span id="quizHtml"></span>
            </div>
            <div id="timer">Time Left: <span id="countdown"></span></div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevButton" onclick="navigate(-1)" disabled>Previous</button>
                <button type="button" class="btn btn-primary" id="nextButton" onclick="navigate(1)">Next</button>
                <button type="submit" class="btn btn-success" id="submitButton" style="display: none;">Submit</button>
            </div>
        
        </form>
    </div>
    <input type="hidden" id="totalduration"/>
    <input type="hidden" id="totalquestions"/>
    <div class="container" id="assessment" style="display: none;">
        <h2>Assessment</h2>
        <p id="assessmentMessage"></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let timerStarted = false; // Flag to check if the timer has started
        let timer;

        $(document).ready(function() {
            quizSelectDropdown();

            $('#startQuiz').click(function() {
                startQuiz();
            });
        });

        function startQuiz() {
            const name = document.getElementById('name').value;
            if (name.trim() !== '') {
                $.ajax({
                    url: '{{ route("start.quiz") }}', // The URL to the controller method
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        quizId: $('#quizes').val(),
                        _token: "{{ csrf_token() }}", // CSRF token for security
                    },
                    success: function(response) {
                        var jsonObj = $.parseJSON(response);
                        document.getElementById('startForm').style.display = 'none';
                        document.getElementById('quiz').style.display = 'block';
                        $('#quizHtml').html(jsonObj.quizHtml);
                        $('#quiztitle').html(jsonObj.quizData.quiz_name);
                        $('#totalduration').val(jsonObj.quizData.duration);
                        $('#totalquestions').val(jsonObj.totalquestcount);
                        if (!timerStarted) {
                            startTimer();
                            timerStarted = true;
                        }
                        updateButtons();
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('An error occurred:', error);
                    }
                });
            } else {
                alert('Please enter your name to start the quiz.');
            }
        }
   
        function startTimer() {
            let timeleft = $('#totalduration').val();
            const countdownTimer = document.getElementById("countdown");
       
            if (countdownTimer) {
                timer = setInterval(function() {
                    if (timeleft <= 0) {
                        // $(document).ready(function() {
                        // $('#timetaken').val($('#totalduration').val() - timeleft);
                        // });
                        clearInterval(timer);
                        document.getElementById('quiz').style.display = 'none';
                        document.getElementById('assessment').style.display = 'block';
                        document.getElementById('assessmentMessage').textContent = "Time's up! Quiz submitted.";
                    } else {
                        countdownTimer.textContent = timeleft;
                        timeleft -= 1;
                    
                    }
                    // $(document).ready(function() {
                        $('#timetaken').val(timeleft);
                    //     });
                }, 1000);
            }
        }

        let currentQuestion = 1;

        function navigate(direction) {
            const totalQuestions = $('#totalquestions').val();
            document.querySelector(`.question[data-question="${currentQuestion}"]`).classList.remove('active');
            currentQuestion += direction;
            document.querySelector(`.question[data-question="${currentQuestion}"]`).classList.add('active');
            document.getElementById('prevButton').disabled = currentQuestion === 1;
            document.getElementById('nextButton').style.display = currentQuestion >= totalQuestions ? 'none' : 'inline-block';
            document.getElementById('submitButton').style.display = currentQuestion >= totalQuestions ? 'inline-block' : 'none';
        }

        function quizSelectDropdown() {
            $.ajax({
                type: 'GET',
                url: "{{ route('quiz.frontend.dropdown') }}", // Replace with your server-side script URL
                success: function(response) {
                    $('#quizes').html('<option>Select Quiz</option>');
                    $('#quizes').append(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
        $(document).ready(function() {
    $('#quizForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Serialize the form data
        var formData = $(this).serialize();

        // Send the AJAX request
        $.ajax({
            url: "{{route('submit.quiz')}}", // Your Laravel route to handle the form submission
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle the successful response
                if (response.status === 200) {
                    window.location.href = "{{ route('quiz.overview') }}?quizid=" + response.quizID;
       
 
    } else {
        // Handle failure
        console.error('Form submission failed');
    }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    });
});

    </script>
</body>
</html>
