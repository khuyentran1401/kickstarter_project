var username = document.getElementById('username');
var password = document.getElementById('password');
var form = document.getElementById('form');


username.addEventListener('mouseout', isValidUsername);
password.addEventListener('keyup', isStrongPassword)

function isValidUsername(event) {

    var output = document.getElementById("username-output");

    var error = "";
    var illegalChars = /\W/; // allow letters, numbers, and underscores
 
    if (username.value == "") {
        username.style.background = 'Yellow';
        error = "You didn't enter a username.\n";
        output.innerHTML = error;
        // event.preventDefault();
 
    } else if ((username.value.length < 5) || (username.value.length > 15)) {
        username.style.background = 'Yellow';
        error = "The username is the wrong length. Username must have minimum length of 5 characters and maximum length of 15\n";
        output.innerHTML = error;
        // event.preventDefault();
 
    } else if (illegalChars.test(username.value)) {
        username.style.background = 'Yellow';
        error = "The username contains illegal characters.\n";
        output.innerHTML = error;
        // event.preventDefault();
 
    } else {
        username.style.background = 'White';
        error = '';
        output.innerHTML = error;
    }
}


function isStrongPassword() {

    var output = document.getElementById("password-output");

    pass_level = 0;
    if (password.value.match(/[a-z]/g)) {
        pass_level++;
    }
    if (password.value.match(/[A-Z]/g)) {
        pass_level++;
    }
    if (password.value.match(/[0-9]/g)) {
        pass_level++;
    }
    if (password.value.length < 5) {
        if(pass_level >= 1) pass_level--;
    } else if (password.value.length >= 20) {
        pass_level++;
    }
    output_val = '';
    switch (pass_level) {
        case 1: output_val='Weak'; break;
        case 2: output_val='Normal'; break;
        case 3: output_val='Strong'; break;
        case 4: output_val='Very strong'; break;
        default: output_val='Very weak'; break;
    }
    if (output.value != pass_level) {
        output.value = pass_level;
        output.innerHTML = output_val;
    }
    return 1;
}

