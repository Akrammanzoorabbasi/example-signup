@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reset Password</h2>
    <form id="resetPasswordForm">
        @csrf
        <div class="form-group">
            <label for="token">Token:</label>
            <input type="text" id="token" name="token" class="form-control" value="{{ request()->route('token') }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary" id="resetButton">Reset Password</button>
        <div id="errorMessages" class="mt-3 text-danger"></div>
    </form>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const resetButton = document.getElementById('resetButton');
        const errorMessages = document.getElementById('errorMessages');
        
        // Clear previous error messages
        errorMessages.innerHTML = '';
        resetButton.disabled = true; // Disable button while processing

        try {
            const response = await fetch('/api/password/reset', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                alert(result.message);
                window.location.href = '/login'; // Redirect to login page upon success
            } else {
                // Show validation errors
                if (result.errors) {
                    result.errors.forEach(error => {
                        const errorElement = document.createElement('div');
                        errorElement.textContent = error;
                        errorMessages.appendChild(errorElement);
                    });
                } else {
                    alert(result.message);
                }
            }
        } catch (error) {
            console.error('An error occurred:', error);
            alert('An error occurred. Please try again.');
        } finally {
            resetButton.disabled = false; // Re-enable button after processing
        }
    });
</script>
@endsection
