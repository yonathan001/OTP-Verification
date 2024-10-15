<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email OTP Verification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a; 
            color: #e0e0e0; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #2a2a2a; 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            position: relative; 
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #f0f0f0; 
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #444; 
            border-radius: 4px;
            background-color: #3a3a3a; 
            color: #e0e0e0; 
        }
        .form-group input::placeholder {
            color: #a0a0a0; 
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff; 
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
        .message.success {
            background-color: #2e7d32; 
            color: #ffffff; 
        }
        .message.error {
            background-color: #c62828; 
            color: #ffffff; 
        }
        
        .footer {
            font-size: 12px; 
            color: rgba(255, 255, 255, 0.5); 
            text-align: center;
            position: absolute; 
            bottom: 10px; 
            left: 50%; 
            transform: translateX(-50%); 
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>OTP_TEST_LARAVEL</h4>
        <!-- Form to send OTP -->
        <form id="sendOtpForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <button type="submit">Send OTP</button>
            </div>
        </form>

        <!-- Form to verify OTP -->
        <form id="verifyOtpForm" style="display:none;">
            <div class="form-group">
                <label for="otp">OTP</label>
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <div class="form-group">
                <button type="submit">Verify</button>
            </div>
        </form>

        <!-- Message display area -->
        <div class="message" id="responseMessage"></div>

        <!-- Footer span -->
        <span class="footer">By yonathan001 | GitHub</span>
    </div>

    <script>
        let userEmail = '';

        // Function to display messages
        function showMessage(type, text) {
            const messageDiv = document.getElementById('responseMessage');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = text;
            messageDiv.style.display = 'block';
        }

        // Handle sending OTP
        document.getElementById('sendOtpForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            userEmail = email; // Store the email for later verification

            fetch('/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showMessage('success', 'OTP sent successfully.');
                    document.getElementById('sendOtpForm').style.display = 'none';
                    document.getElementById('verifyOtpForm').style.display = 'block';
                } else {
                    showMessage('error', 'Failed to send OTP.');
                }
            })
            .catch(error => {
                showMessage('error', 'An error occurred.');
            });
        });

        // Handle verifying OTP
        document.getElementById('verifyOtpForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const otp = document.getElementById('otp').value;

            fetch('/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: userEmail, otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showMessage('success', 'OTP verified successfully.');
                    document.getElementById('verifyOtpForm').style.display = 'none';
                } else {
                    showMessage('error', 'Invalid or expired OTP.');
                }
            })
            .catch(error => {
                showMessage('error', 'An error occurred.');
            });
        });
    </script>
</body>
</html>
