const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});



  // Generate a random CAPTCHA
  function generateCaptcha() {
	var captcha = Math.random().toString(36).substring(2, 8);
	document.getElementById('captcha').innerText = captcha;
  }

  // Validate user's input
  function validateCaptcha() {
	var userInput = document.getElementById('userInput').value;
	var captcha = document.getElementById('captcha').innerText;

	if (userInput === captcha) {
	  alert('Account created');
	  generateCaptcha();
	  document.getElementById('userInput').value = '';
	} else {
	  alert('CAPTCHA failed!');
	  // You may choose to perform additional actions here, like displaying an error message or disabling a form submission.
	}
  }

  // Generate the initial CAPTCHA on page load
  window.addEventListener('load', generateCaptcha);

// for active buttons inside the sidebar
  document.addEventListener("DOMContentLoaded", function() {
	const buttons = document.querySelectorAll('.sidebar .btn');
	buttons.forEach(function(button) {
		button.addEventListener('click', function() {
			buttons.forEach(function(btn) {
				btn.classList.remove('active');
			});
			this.classList.add('active');
		});
	});
});
