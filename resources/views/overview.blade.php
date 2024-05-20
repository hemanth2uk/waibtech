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
       
        
    </div>

    <div class="container" id="quiz" >
        <h2 id="quiztitle"></h2>
        <h2>Result</h2>
        <p id="assessmentMessage">Score:<span id="score"></span></p>
    
        <p id="assessmentMessage">Result:<span id="result"></span></p>
        <form method="post">
            @csrf
            <div id="questions">
                <span id="quizHtml"></span>
            </div>
           <br>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="prevButton" onclick="navigate(-1)" disabled>Previous</button>
                <button type="button" class="btn btn-primary" id="nextButton" onclick="navigate(1)">Next</button>
                <button type="submit" class="btn btn-success" id="submitButton" style="display: none;">Submit</button>
            </div>
        
        </form>
    </div>
    <input type="hidden" id="totalduration"/>
    <input type="hidden" id="totalquestions"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       ListOverview();
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

        function ListOverview() {
     
       
                $.ajax({
                    url: '{{ route("quiz.overview.view") }}', // The URL to the controller method
                    method: 'POST',
                    data: {
                   
                        quizId: {quizid:"{{$_GET['quizid']}}"},
                        _token: "{{ csrf_token() }}", // CSRF token for security
                    },
                    success: function(response) {
                        var jsonObj = $.parseJSON(response);
                      
                        document.getElementById('quiz').style.display = 'block';
                        $('#quizHtml').html(jsonObj.quizHtml);
                        $('#score').html(jsonObj.score);
                        $('#totalquestions').val(jsonObj.totalquestcount);
                        $('#result').html(jsonObj.status);
          
             
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors
                        console.error('An error occurred:', error);
                    }
                });
       
        }
   
    </script>
</body>
</html>
