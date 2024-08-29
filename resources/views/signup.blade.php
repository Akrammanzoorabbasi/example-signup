@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Signup</h2>
    <form id="signupForm">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary" id="signupButton">Signup</button>
        <div id="errorMessages" class="mt-3 text-danger"></div>
    </form>
</div>

<script>
    document.getElementById('signupForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const signupButton = document.getElementById('signupButton');
        const errorMessages = document.getElementById('errorMessages');
        
        // Clear previous error messages
        errorMessages.innerHTML = '';
        signupButton.disabled = true; // Disable button

        try {
            const response = await fetch('/api/signup', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok) {
                alert(result.message);
                // Optionally, redirect or reset the form
                window.location.href = '/login'; // Redirect to login page
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
            signupButton.disabled = false; // Re-enable button
        }
    });
</script>
@endsection
