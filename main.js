
function usernameMessage(usernameAvailable) {
  let usernameAvailableMsg = document.querySelector('#usernameAvailable');
  let usernameTakenMsg = document.querySelector('#usernameTaken');

  if (usernameAvailable) {
    usernameAvailableMsg.style.display = 'block';
    usernameTakenMsg.style.display = 'none';
  } else {
    usernameAvailableMsg.style.display = 'none';
    usernameTakenMsg.style.display = 'block';
  }
}

function checkUsername(event) {
	// Fetch the username provided by the user in the target input.
	let username = event.target.value;
	let usernameRequired = document.querySelector('#usernameRequired');
	// Don't bother checking blank usernames.
	if (username === '') { 	
		usernameRequired.style.display = 'block';
		let usernameAvailableMsg = document.querySelector('#usernameAvailable');
  		let usernameTakenMsg = document.querySelector('#usernameTaken');
		usernameTakenMsg.style.display = 'none';
		usernameAvailableMsg.style.display = 'none';
		return;
	}
	usernameRequired.style.display = 'none';
  // AJAX GET request to test the username for availability.
  fetch('username.php?username=' + username)
    .then(function(rawResponse) { 
      return rawResponse.json(); // Promise for parsed JSON.
    })
    .then(function(response) {
      // If the API check was successful.
      if (response['success']) {
        // Show the relevant username message (available / taken).
        usernameMessage(response['usernameAvailable'])

        // If the username is take put the focus back on the input
        // and select all text.
        if (! response['usernameAvailable']) {
          event.target.select();
        }
      }
    });
};

document.addEventListener('DOMContentLoaded', e => {
	let usernameElement = document.querySelector('#username');
	usernameElement.onblur = checkUsername;
})

