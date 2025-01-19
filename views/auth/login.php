<?php 
// views/auth/login.php
include 'views/public/templates/header.php'; 
?>

<style>
    @keyframes rotate {
        100% {
            transform: rotate(360deg);
        }
    }

    .icon {
        display: inline-flex;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px 18px;
        width: 18px;
        height: 18px;
        fill: white;
        animation: rotate 1s linear infinite;
        margin-left: 10px;
        background-image: url("data:image/svg+xml;utf8,%0A%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2024%2024%22%20stroke-width%3D%221.5%22%20stroke%3D%22currentColor%22%20class%3D%22w-6%20h-6%22%3E%0A%20%20%3Cpath%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20d%3D%22M16.023%209.348h4.992v-.001M2.985%2019.644v-4.992m0%200h4.992m-4.993%200l3.181%203.183a8.25%208.25%200%200013.803-3.7M4.031%209.865a8.25%208.25%200%200113.803-3.7l3.181%203.182m0-4.991v4.99%22%20/%3E%0A%3C/svg%3E%0A");
    }
</style>

<main class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-black via-gray-800 to-gray-900">
    
    <div class="flex flex-col w-96">
        <h2 class="text-4xl mb-6 text-white font-medium">Login</h2>

        <div id="errorContainer" class="mb-4">
            <?php
                if (isset($_SESSION['login_errors'])) {
                    foreach($_SESSION['login_errors'] as $error) {
                        echo '<p class="text-red-500 mb-2">' . htmlspecialchars($error) . '</p>';
                    }
                    unset($_SESSION['login_errors']);
                }

                // Retain input values after form submission
                $usernameValue = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
                $passwordValue = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            ?>
        </div>

        <form id="loginForm" class="flex flex-col gap-4">
            <input type="text" name="username" placeholder="Username" required value="<?php echo $usernameValue; ?>">
            <input type="password" name="password" placeholder="Password" required value="<?php echo $passwordValue; ?>">
            
            <button class="button" type="submit">Login</button>

            <p class="text-sm text-slate-300">Forgot password? <a href="reset" class="underline">Reset password</a></p>
        </form>
    </div>

    <p class="mt-6 text-slate-400">Don't have an account? <a href="register" class="text-slate-300 underline">Sign up</a></p>

</main>

<script>
    
    /**document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        submitLogin(formData);
    });

    function submitLogin(formData) {
        // Prepare and send AJAX request
        // Handle response and errors
        // Update UI accordingly
    }**/

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form elements
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button');

    // Get form field elements
    const formFields = form.querySelectorAll('input');

    // Disable the submit button to prevent multiple submissions
    submitButton.disabled = true;
    submitButton.textContent = 'Logging in..';
    submitButton.innerHTML += '<div class="icon"></div>';
    submitButton.classList.add('!bg-blue-400');

    // Send the AJAX request
    fetch('/login', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Manually set the header
        },
        body: formData
    })
    .then(response => response.text()) // First, get the text of the response
    .then(text => {
        try {
            console.log(text);
            return JSON.parse(text); // Try to parse it as JSON
        } catch (e) {
            console.log(text);
            console.error("Not a JSON response:", text);
            throw e; // Re-throw to handle it in the subsequent catch block
        }
    })
   /* .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })*/
    .then(data => {
        if (data.success) {
            // Redirect if login is successful
            // add a delay to allow the user to see the success message
            submitButton.classList.add('!bg-green-500'); // Assuming green indicates success
            submitButton.textContent = 'Success! Redirecting...';

            setTimeout(() => {
                window.location.href = data.redirect || '/dashboard';
            }, 1000);
            

            // create a time out function to redirect to the dashboard

        } else {
            // Handle errors
            setTimeout(() => {
                displayErrors(data.errors, formFields);
                submitButton.disabled = false;
                submitButton.classList.remove('!bg-blue-400');
                submitButton.textContent = 'Login';
            }, 1000);
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        //alert('An error occurred. Please try again.');
        submitButton.disabled = false;
        submitButton.textContent = 'Login';
    });
});

function displayErrors(errors, formFields) {
    // Clear any previous errors
    const errorContainer = document.getElementById('errorContainer');
    if (!errorContainer) {
        console.error('Error container not found');
        return;
    }
    errorContainer.innerHTML = '';

    formFields.forEach(field => {
        field.classList.add('!border-red-500');
    });

    // Add new errors
    if (Array.isArray(errors)) {
        errors.forEach(error => {
            const errorElement = document.createElement('p');
            errorElement.textContent = error;
            errorElement.classList.add('text-red-500'); // Add your error message styling class
            errorElement.classList.add('mb-2');
            errorContainer.appendChild(errorElement);
        });
    } else {
        const errorElement = document.createElement('p');
        errorElement.textContent = 'An unexpected error occurred.';
        errorElement.classList.add('text-red-500');
        errorContainer.appendChild(errorElement);
    }
}

</script>

<?php include 'views/public/templates/footer.php'; ?>