// server.js
const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const port = 3000;

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Twilio setup (replace with your Twilio credentials)
const accountSid = 'your_account_sid';
const authToken = 'your_auth_token';
const client = require('twilio')(accountSid, authToken);

// Endpoint to send OTP (to be implemented)
app.post('/send-otp', (req, res) => {
    const phoneNumber = req.body.phoneNumber;

    // Generate OTP and send it to the user's phone number
    const otp = Math.floor(1000 + Math.random() * 9000);

    client.messages
        .create({
            body: `Your OTP is: ${otp}`,
            from: 'your_twilio_phone_number',
            to: phoneNumber
        })
        .then(message => {
            console.log(message.sid);
            res.send('OTP sent successfully');
        })
        .catch(err => {
            console.error(err);
            res.status(500).send('Error sending OTP');
        });
});

// Endpoint to verify OTP (to be implemented)
app.post('/verify-otp', (req, res) => {
    const otp = req.body.otp;

    // Verify OTP logic goes here

    // For simplicity, let's assume any 4-digit OTP is valid
    if (otp && otp.length === 4 && /^\d+$/.test(otp)) {
        res.send('OTP verified successfully');
    } else {
        res.status(400).send('Invalid OTP');
    }
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
