<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
	<link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="submit"] {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .converted-text {
            margin-top: 20px;
        }
        .translated-column {
            display: inline-block;
            width: calc(50% - 10px);
            vertical-align: top;
        }
        .translated-column h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <p>Enter the title (which you have given previously while converting the audio file to text) to retrieve the text</p>
    <form method="post">
		
        <input type="text" name="search">
        <input type="submit" name="submit" >
		
    </form>
    <?php
    $con = new PDO("mysql:host=localhost;dbname=userform", 'root', '');
    if (isset($_POST["submit"])) {
        $str = $_POST["search"];
        $sth = $con->prepare("SELECT * FROM `studentinfo` WHERE value1 = '$str'");

        $sth->setFetchMode(PDO::FETCH_OBJ);
        $sth->execute();

        if ($row = $sth->fetch()) {
            echo '
            <div class="converted-text">
                
            </div>';
        } else {
            echo '<p class="error-msg">Name does not exist</p>';
        }
    }
    ?>
    <div class="translated-column">
        <h2>Retrieved Text</h2>
        <p id="retrievedText"><?php echo $row->value2; ?></p>
    </div>
    <div class="translated-column">
        <h2>Translated Text</h2>
        <p id="englishText"></p>
    </div>
    <button onclick="translateText()">Convert Into English</button>
</div>
<script>
    function translateText() {
        var tamilText = "<?php echo $row->value2; ?>";
        var englishTextElement = document.getElementById("englishText");
        var chunks = tamilText.match(/.{1,1000}/g); // Split text into chunks of 1000 characters
        var translatedText = "";

        function translateChunk(index) {
            if (index < chunks.length) {
                var xhr = new XMLHttpRequest();
                var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=ta&tl=en&dt=t&q=" + encodeURI(chunks[index]);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        for (var i = 0; i < response[0].length; i++) {
                            translatedText += response[0][i][0] + ' ';
                        }
                        translateChunk(index + 1);
                    }
                };
                xhr.open("GET", url, true);
                xhr.send();
            } else {
                englishTextElement.textContent = translatedText.trim();
            }
        }
        translateChunk(0);
    }
</script>
</body>
</html>
