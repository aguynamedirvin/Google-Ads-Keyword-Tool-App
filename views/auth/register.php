<?php
// auth/login.php
include 'views/public/templates/header.php';
?>

<main class="flex flex-col justify-center items-center min-h-screen bg-gradient-to-r from-black via-gray-800 to-gray-900">
    
    <div class="flex flex-col w-96">
        <h2 class="text-4xl mb-6 text-white font-medium">Register</h2>

        <div id="errorContainer" class="mb-4">
            <?php
            // Check for registration errors and display them
            if (isset($_SESSION['registration_errors'])) {
                foreach ($_SESSION['registration_errors'] as $error) {
                    echo '<p class="text-red-500 mb-2">' . htmlspecialchars($error) . '</p>';
                }
                unset($_SESSION['registration_errors']); // Clear the error messages after displaying
            }
            
            $usernameValue = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
            $passwordValue = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
            $confirmPasswordValue = isset($_POST['confirmPassword']) ? htmlspecialchars($_POST['confirmPassword']) : '';
            $emailValue = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
            $firstNameValue = isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : '';
            $lastNameValue = isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : '';

            ?>
        </div>

       <form id="registerForm" action="register" method="post" class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row gap-4">
                <input type="text" name="firstName" placeholder="First Name"  value="<?php echo $firstNameValue ?>">
                <input type="text" name="lastName" placeholder="Last Name"  value="<?php echo $lastNameValue?>">
            </div>
            <input type="text" name="username" placeholder="Username"  value="<?php echo $usernameValue?>">
            <input type="email" name="email" placeholder="Email"  value="<?php echo $emailValue?>">
            <input type="password" name="password" placeholder="Password"  value="<?php echo $passwordValue?>">
            <input type="password" name="confirmPassword" placeholder="Confirm Password"  value="<?php echo $confirmPasswordValue?>">
            <button class="button" type="submit">Register</button>
        </form>


    </div>

    <p class="mt-6 text-slate-400">Already have an account? <a href="login" class="text-slate-300 underline">Log in</a></p>
</main>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form elements
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button');

    // Get form field elements
    const formFields = form.querySelectorAll('input');

    // Disable the submit button to prevent multiple submissions
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';
    submitButton.classList.add('text-blue-100');
    submitButton.classList.add('!bg-blue-400');

    // Send the AJAX request
    fetch('/register', {
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
            }), 10000;
            

            // create a time out function to redirect to the dashboard

        } else {
            // Handle errors
            setTimeout(() => {
                displayErrors(data.errors, formFields);
                submitButton.disabled = false;
                submitButton.classList.remove('!bg-blue-400');
                submitButton.textContent = 'Register';
            }, 1000);
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        //alert('An error occurred. Please try again.');
        submitButton.disabled = false;
        submitButton.textContent = 'Register';
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