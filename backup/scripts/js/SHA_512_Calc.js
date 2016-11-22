function RegisterCalcHash() {
	var pw = document.getElementById("pw");
	var pw_check = document.getElementById("pw_check");
	pw.value = new jsSHA(pw.value, "TEXT").getHash("SHA-512", "HEX", 2048);
	pw_check.value = new jsSHA(pw_check.value, "TEXT").getHash("SHA-512", "HEX", 2048);
}

function LoginCalcHash() {
	var pw = document.getElementById("password");
	pw.value = new jsSHA(pw.value, "TEXT").getHash("SHA-512", "HEX", 2048);
}

function ChangePWCalcHash() {
	var old_pw = document.getElementById("old_pw");
	var new_pw = document.getElementById("new_pw");
	var new_pw_check = document.getElementById("new_pw_check");
	old_pw.value = new jsSHA(old_pw.value, "TEXT").getHash("SHA-512", "HEX", 2048);
	new_pw.value = new jsSHA(new_pw.value, "TEXT").getHash("SHA-512", "HEX", 2048);
	new_pw_check.value = new jsSHA(new_pw_check.value, "TEXT").getHash("SHA-512", "HEX", 2048);
}