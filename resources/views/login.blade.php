@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form id="loginForm">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" id="loginButton">Login</button>
        <div id="errorMessages" class="mt-3 text-danger"></div>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const loginButton = document.getElementById('loginButton');
        const errorMessages = document.getElementById('errorMessages');

        // Clear previous error messages
        errorMessages.innerHTML = '';
        loginButton.disabled = true; // Disable button while processing

        try {
            const response = await fetch('/api/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                alert(result.message);
                // Optionally, redirect the user upon successful login
                window.location.href = '/dashboard'; // Adjust the redirection URL as needed
            } else {
                // Show validation errors or other error messages
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
            loginButton.disabled = false; // Re-enable button after processing
        }
    });
</script>
@endsection
