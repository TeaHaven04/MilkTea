// 🔹 Replace with your Firebase project config
const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "your-project.firebaseapp.com",
    projectId: "your-project",
    storageBucket: "your-project.appspot.com",
    messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    appId: "YOUR_APP_ID"
};

// 🔹 Initialize Firebase
firebase.initializeApp(firebaseConfig);

// 🔹 Get Firestore database
const db = firebase.firestore();
