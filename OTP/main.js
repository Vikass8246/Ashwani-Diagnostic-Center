// main.js
const phoneNumberInput = document.getElementById('phoneNumber');
const otpInput = document.getElementById('otp');

function sendOTP() {
    const phoneNumber = phoneNumberInput.value;
    if (!phoneNumber) {
        alert('Please enter a valid phone number');
        return;
    }

    // Send OTP to the server (to be implemented in the backend)
    // You can use AJAX, fetch, or other methods to send data to the server
    // Example: sendOTPToServer(phoneNumber);
}

function verifyOTP() {
    const otp = otpInput.value;
    if (!otp) {
        alert('Please enter the OTP');
        return;
    }

    // Verify OTP on the server (to be implemented in the backend)
    // Example: verifyOTPOnServer(otp);
}
