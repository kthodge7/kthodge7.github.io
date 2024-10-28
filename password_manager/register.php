<?php
require_once "config.php";
// This section only initializes variables - processing will be in Part 3
$password_err = "";
$email_err = "";
$registration_success = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Password Manager</title>
    <!-- Tailwind CSS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS for password strength indicator -->
    <style>
        .password-strength-meter {
            height: 4px;
            background-color: #eee;
            border-radius: 2px;
            margin-top: 5px;
        }
        
        .strength-weak { width: 25%; background-color: #ff4444; }
        .strength-medium { width: 50%; background-color: #ffbb33; }
        .strength-strong { width: 75%; background-color: #00C851; }
        .strength-very-strong { width: 100%; background-color: #007E33; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <!-- Logo/Header Section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                <p class="mt-2 text-sm text-gray-600">Secure Password Manager</p>
            </div>

            <!-- Error Messages Display -->
            <?php if(!empty($email_err)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p><?php echo $email_err; ?></p>
                </div>
            <?php endif; ?>

            <?php if(!empty($password_err)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p><?php echo $password_err; ?></p>
                </div>
            <?php endif; ?>

            <?php if(!empty($registration_success)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p><?php echo $registration_success; ?></p>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form id="registrationForm" action="process_registration.php" method="post" class="space-y-6">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                                      focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="your@email.com">
                    </div>
                    <p id="emailError" class="mt-1 text-sm text-red-600 hidden">Please enter a valid email address</p>
                </div>

                <!-- Master Password Field -->
                <div>
                    <label for="master_password" class="block text-sm font-medium text-gray-700">Master Password</label>
                    <div class="mt-1">
                        <input id="master_password" name="master_password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                                      focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="••••••••">
                        <div class="password-strength-meter mt-2">
                            <div id="strength-meter" class=""></div>
                        </div>
                    </div>
                    <!-- Password Requirements List -->
                    <div class="mt-2 text-xs space-y-1">
                        <p class="text-gray-600 font-medium">Password must contain:</p>
                        <ul class="grid grid-cols-2 gap-1">
                            <li id="length" class="text-gray-500">
                                <span class="inline-block w-4">○</span> 8+ characters
                            </li>
                            <li id="uppercase" class="text-gray-500">
                                <span class="inline-block w-4">○</span> Uppercase letter
                            </li>
                            <li id="lowercase" class="text-gray-500">
                                <span class="inline-block w-4">○</span> Lowercase letter
                            </li>
                            <li id="number" class="text-gray-500">
                                <span class="inline-block w-4">○</span> Number
                            </li>
                            <li id="special" class="text-gray-500">
                                <span class="inline-block w-4">○</span> Special character
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="mt-1">
                        <input id="confirm_password" name="confirm_password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 
                                      focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="••••••••">
                    </div>
                    <p id="passwordMatchError" class="mt-1 text-sm text-red-600 hidden">Passwords do not match</p>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium 
                                   text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-blue-500">
                        Create Account
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center text-sm">
                    <p class="text-gray-600">
                        Already have an account?
                        <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Client-side Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const passwordInput = document.getElementById('master_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const emailInput = document.getElementById('email');
            const strengthMeter = document.getElementById('strength-meter');

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };

                // Update requirement indicators
                Object.keys(checks).forEach(key => {
                    const element = document.getElementById(key);
                    if (checks[key]) {
                        element.classList.remove('text-gray-500');
                        element.classList.add('text-green-500');
                        element.querySelector('span').textContent = '✓';
                        strength++;
                    } else {
                        element.classList.remove('text-green-500');
                        element.classList.add('text-gray-500');
                        element.querySelector('span').textContent = '○';
                    }
                });

                // Update strength meter
                strengthMeter.className = '';
                if (strength < 2) strengthMeter.classList.add('strength-weak');
                else if (strength < 3) strengthMeter.classList.add('strength-medium');
                else if (strength < 5) strengthMeter.classList.add('strength-strong');
                else strengthMeter.classList.add('strength-very-strong');

                return Object.values(checks).every(Boolean);
            }

            // Real-time password validation
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Form submission validation
            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Email validation
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(emailInput.value)) {
                    document.getElementById('emailError').classList.remove('hidden');
                    isValid = false;
                } else {
                    document.getElementById('emailError').classList.add('hidden');
                }

                // Password validation
                if (!checkPasswordStrength(passwordInput.value)) {
                    isValid = false;
                }

                // Confirm password validation
                if (passwordInput.value !== confirmPasswordInput.value) {
                    document.getElementById('passwordMatchError').classList.remove('hidden');
                    isValid = false;
                } else {
                    document.getElementById('passwordMatchError').classList.add('hidden');
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
<script>
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Reset any existing error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = 'Creating Account...';
    submitButton.disabled = true;
    
    // Send form data
    fetch('process_registration.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4';
            successAlert.innerHTML = 'Account created successfully! Redirecting...';
            this.insertBefore(successAlert, this.firstChild);
            
            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            // Show errors
            const errorContainer = document.createElement('div');
            errorContainer.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4';
            
            let errorHTML = '<ul class="list-disc list-inside">';
            data.errors.forEach(error => {
                errorHTML += `<li>${error}</li>`;
            });
            errorHTML += '</ul>';
            
            errorContainer.innerHTML = errorHTML;
            this.insertBefore(errorContainer, this.firstChild);
        }
    })
    .catch(error => {
        // Show system error
        const errorContainer = document.createElement('div');
        errorContainer.className = 'bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4';
        errorContainer.textContent = 'A system error occurred. Please try again later.';
        this.insertBefore(errorContainer, this.firstChild);
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
    });
});
</script>
</body>
</html>