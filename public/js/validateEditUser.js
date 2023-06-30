// CREATING THE REGEX FOR ALL INPUTS
const usernameEditRegex = /^[A-Za-z][A-Za-z0-9]{5,31}$/;
const passwordEditRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
const emailEditRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

// GRABBING INPUT ELEMENTS FROM HTML

const firstName = document.querySelector("#firstName");
if (firstName) {
	// VALIDATING THE FIRST NAME INPUT (EDIT)
	function checkFirstName(firstName) {
		if (firstName.value === "") {
			firstName.className = "red";
			firstNameMissing.style.display = "inline";
			firstNameNotValid.style.display = "none";
			return false;
		} else if (firstName.value !== "" && firstName.value.length < 3) {
			firstName.className = "red";
			firstNameNotValid.style.display = "inline";
			firstNameMissing.style.display = "none";
			return false;
		} else {
			firstName.className = "green";
			firstNameNotValid.style.display = "none";
			firstNameMissing.style.display = "none";
			return true;
		}
	}

	// VALIDATING FIRST NAME INPUT (EDIT) LIVE ON KEYUP
	firstName.addEventListener("keyup", () => {
		checkFirstName(firstName);
	});

	firstName.addEventListener("blur", function () {
		if (firstName.value.length == 0 || firstName.value) {
			firstName.className = "";
			firstNameNotValid.style.display = "none";
			firstNameMissing.style.display = "none";
		}
	});
}

const lastName = document.querySelector("#lastName");
if (lastName) {
	// VALIDATING THE LAST NAME INPUT (EDIT)
	function checkLastName(lastName) {
		if (lastName.value === "") {
			lastName.className = "red";
			lastNameMissing.style.display = "inline";
			lastNameNotValid.style.display = "none";
			return false;
		} else if (lastName.value !== "" && lastName.value.length < 3) {
			lastName.className = "red";
			lastNameNotValid.style.display = "inline";
			lastNameMissing.style.display = "none";
			return false;
		} else {
			lastName.className = "green";
			lastNameNotValid.style.display = "none";
			lastNameMissing.style.display = "none";
			return true;
		}
	}

	// VALIDATING LAST NAME INPUT (EDIT) LIVE ON KEYUP
	lastName.addEventListener("keyup", () => {
		checkLastName(lastName);
	});

	lastName.addEventListener("blur", function () {
		if (lastName.value.length == 0 || lastName.value) {
			lastName.className = "";
			lastNameNotValid.style.display = "none";
			lastNameMissing.style.display = "none";
		}
	});
}
const userNameEdit = document.querySelector("#usernameEdit");
if (userNameEdit) {
	// VALIDATING THE USERNAME INPUT (EDIT)
	function checkUsername(userNameEdit) {
		if (userNameEdit.value === "") {
			userNameEdit.className = "red";
			usernameMissing.style.display = "inline";
			usernameNotValid.style.display = "none";
			return false;
		} else if (
			userNameEdit.value !== "" &&
			(userNameEdit.value.length < 3 || !usernameEditRegex.test(userNameEdit.value))
		) {
			userNameEdit.className = "red";
			usernameNotValid.style.display = "inline";
			usernameMissing.style.display = "none";
			return false;
		} else {
			userNameEdit.className = "green";
			usernameNotValid.style.display = "none";
			usernameMissing.style.display = "none";
			return true;
		}
	}

	// VALIDATING THE USERNAME (EDIT) INPUT LIVE ON KEYUP
	userNameEdit.addEventListener("keyup", () => {
		checkUsername(userNameEdit);
	});

	userNameEdit.addEventListener("blur", function () {
		if (userNameEdit.value.length == 0 || userNameEdit.value) {
			userNameEdit.className = "";
			usernameNotValid.style.display = "none";
			usernameMissing.style.display = "none";
		}
	});
}

const emailEdit = document.querySelector("#emailEdit");
if (emailEdit) {
	// VALIDATING THE EMAIL (EDIT) INPUT
	function checkEmail(emailEdit) {
		if (emailEdit.value == "") {
			emailMissing.style.display = "inline";
			emailNotValid.style.display = "none";
			emailEdit.className = "red";
			return false;
		} else if (emailEdit.value !== "" && (emailEdit.value.length < 3 || !emailEditRegex.test(emailEdit.value))) {
			emailEdit.className = "red";
			emailNotValid.style.display = "inline";
			emailMissing.style.display = "none";
			return false;
		} else {
			emailEdit.className = "green";
			emailMissing.style.display = "none";
			emailNotValid.style.display = "none";
			return true;
		}
	}

	// VALIDATING THE EMAIL (EDIT) INPUT LIVE ON KEYUP
	emailEdit.addEventListener("keyup", () => {
		checkEmail(emailEdit);
	});

	emailEdit.addEventListener("blur", function () {
		if (emailEdit.value.length == 0 || emailEdit.value) {
			emailEdit.className = "";
			emailNotValid.style.display = "none";
			emailMissing.style.display = "none";
		}
	});
}

const passwordEdit = document.querySelector("#passwordEdit");
if (passwordEdit) {
	// VALIDATING THE PASSWORD (EDIT) INPUT
	function checkPassword(passwordEdit) {
		if (passwordEdit.value == "") {
			passwordEdit.className = "red";
			passwordMissing.style.display = "inline";
			passwordNotValid.style.display = "none";
			return false;
		} else if (
			passwordEdit.value !== "" &&
			(passwordEdit.value.length < 3 || !passwordEditRegex.test(passwordEdit.value))
		) {
			passwordEdit.className = "red";
			passwordNotValid.style.display = "inline";
			passwordMissing.style.display = "none";
			return false;
		} else {
			passwordEdit.className = "green";
			passwordNotValid.style.display = "none";
			passwordMissing.style.display = "none";
			return true;
		}
	}

	// VALIDATING THE PASSWORD (EDIT) INPUT LIVE ON KEYUP
	passwordEdit.addEventListener("keyup", () => {
		checkPassword(passwordEdit);
	});

	passwordEdit.addEventListener("blur", function () {
		if (passwordEdit.value.length == 0 || passwordEdit.value) {
			passwordEdit.className = "";
			passwordNotValid.style.display = "none";
			passwordMissing.style.display = "none";
		}
	});
}

function checkInputs(e) {
	// let valid = checkPassword(passwordEdit);
	if (!checkPassword(passwordEdit)) {
		e.preventDefault();
		checkPassword();
	}
}
if (passwordEdit) {
	changePassword.addEventListener("submit", checkInputs);
}
