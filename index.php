<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            flex-direction: column;
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 20px;
        }

        #typing-effect {
            font-size: 1.5rem;
            color: #ff9800;
            white-space: nowrap;
            overflow: hidden;
            border-right: 2px solid #ff9800;
            display: inline-block;
        }

        @keyframes blink {
            from {
                border-color: #ff9800;
            }
            to {
                border-color: transparent;
            }
        }

        /* Styling for the Login Button */
        a {
            text-decoration: none;
            background-color: #ff9800; /* Button color */
            color: #000; /* Text color */
            padding: 10px 20px; /* Padding inside the button */
            border-radius: 5px; /* Rounded corners */
            font-size: 1rem; /* Font size */
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth hover effect */
            margin-top: 30px; /* Space between typing text and button */
        }

        /* Hover effect for the Login Button */
        a:hover {
            background-color: #fff; /* Button background on hover */
            transform: scale(1.1); /* Button grows slightly on hover */
        }
    </style>
</head>
<body>
    <h1>Welcome to Our Platform</h1>
    <div id="typing-effect"></div>
    <a href="login.php">Login</a>

    <script>
        const messages = [
            "Empowering secure and transparent elections...",
            "Your vote matters...",
            "Join us in reshaping democracy..."
        ];
        let currentMessage = 0;
        let currentChar = 0;

        const typingElement = document.getElementById('typing-effect');

        function typeMessage() {
            if (currentChar < messages[currentMessage].length) {
                typingElement.textContent += messages[currentMessage][currentChar];
                currentChar++;
                setTimeout(typeMessage, 100); // Typing speed
            } else {
                setTimeout(() => {
                    currentChar = 0;
                    currentMessage = (currentMessage + 1) % messages.length;
                    typingElement.textContent = ''; // Clear the text
                    typeMessage(); // Start next message
                }, 2000); // Pause before next message
            }
        }

        // Start typing the first message
        typeMessage();
    </script>
    
</body>
</html>
