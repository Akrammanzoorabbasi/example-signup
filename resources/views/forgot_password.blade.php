@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Forgot Password</h2>
    <form id="forgotPasswordForm">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" id="sendResetLinkButton">Send Reset Link</button>
        <div id="errorMessages" class="mt-3 text-danger"></div>
        <div id="successMessage" class="mt-3 text-success"></div>
    </form>
</div>

<script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const sendResetLinkButton = document.getElementById('sendResetLinkButton');
        const errorMessages = document.getElementById('errorMessages');
        const successMessage = document.getElementById('successMessage');

        // Clear previous messages
        errorMessages.innerHTML = '';
        successMessage.innerHTML = '';
        sendResetLinkButton.disabled = true; // Disable button while processing

        try {
            const response = await fetch('/api/password/email', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                successMessage.textContent = result.message;
            } else {
                // Show validation errors or other error messages
                if (result.errors) {
                    result.errors.forEach(error => {
                        const errorElement = document.createElement('div');
                        errorElement.textContent = error;
                        errorMessages.appendChild(errorElement);
                    });
                } else {
                    errorMessages.textContent = result.message;
                }
            }
        } catch (error) {
            console.error('An error occurred:', error);
            alert('An error occurred. Please try again.');
        } finally {
            sendResetLinkButton.disabled = false; // Re-enable button after processing
        }
    });
</script>
@endsection
