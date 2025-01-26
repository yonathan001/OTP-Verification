<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email OTP Verifications</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
    
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            transition: all 0.3s ease;
        }
    
        .container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
    
        .form-group {
            margin-bottom: 20px;
        }
    
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }
    
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: #fafafa;
            color: #333;
            font-size: 14px;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }
    
        .form-group input::placeholder {
            color: #bbb;
        }
    
        .form-group input:focus {
            border-color: #007bff;
            background-color: #fff;
            outline: none;
        }
    
        .form-group button {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }
    
        .form-group button:hover {
            background-color: #0056b3;
        }
    
        .message {
            margin-top: 20px;
            padding: 14px;
            border-radius: 20px;
            text-align: center;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
    
        .message.success {
            background-color: #28a745;
            color: white;
        }
    
        .message.error {
            background-color: #dc3545;
            color: white;
        }
    
        .footer {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <h4></h4>
        <!-- Form to send OTP -->
        <form id="sendOtpForm">
            <div class="form-group">
                <label for="email">Your Email</label>
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
