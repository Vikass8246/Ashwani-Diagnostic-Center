// functions/index.js
const functions = require('firebase-functions');
const admin = require('firebase-admin');
admin.initializeApp();

exports.verifyPhoneNumber = functions.https.onCall(async (data, context) => {
    const { phoneNumber, uid } = data;

    try {
        // Perform additional verification logic here
        // You can check user data in Firestore, validate user information, etc.

        // Example: Check if the user has completed some additional profile information
        const userSnapshot = await admin.firestore().collection('users').doc(uid).get();
        const userData = userSnapshot.data();

        if (!userData || !userData.additionalInfo) {
            throw new Error('Additional verification required.');
        }

        return { success: true, message: 'Verification successful.' };
    } catch (error) {
        console.error('Verification failed:', error.message);
        throw new functions.https.HttpsError('invalid-argument', 'Verification failed', { error });
    }
});
