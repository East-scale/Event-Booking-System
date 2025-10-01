<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Event Booking System</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        h3 {
            color: #7f8c8d;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 15px;
        }
        ul {
            margin-bottom: 15px;
            padding-left: 30px;
        }
        li {
            margin-bottom: 8px;
        }
        .effective-date {
            font-style: italic;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: #2980b9;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Privacy Policy</h1>
        
        <p class="effective-date">Effective Date: {{ date('F d, Y') }}</p>

        <p>This Privacy Policy explains how we collect, use, store, and protect your personal information when you use our Event Booking System. By creating an account and using our services, you agree to the terms outlined in this policy.</p>

        <h2>1. Information We Collect</h2>
        
        <h3>1.1 Information You Provide</h3>
        <p>When you register for an account, we collect the following information:</p>
        <ul>
            <li><strong>Name:</strong> Used to identify you within the system and personalize your experience</li>
            <li><strong>Email Address:</strong> Used for account authentication, communication, and notifications</li>
            <li><strong>Password:</strong> Stored in encrypted form to secure your account</li>
            <li><strong>User Type:</strong> Whether you are registered as an Attendee or Organiser</li>
        </ul>

        <h3>1.2 Information Automatically Collected</h3>
        <p>When you use our services, we automatically collect:</p>
        <ul>
            <li><strong>Booking History:</strong> Records of events you have booked or organised</li>
            <li><strong>Event Interactions:</strong> Your interactions with events, including views and bookings</li>
            <li><strong>Account Activity:</strong> Login times and activity within the system</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        
        <p>We use your personal information for the following purposes:</p>
        
        <h3>2.1 Authentication and Account Management</h3>
        <ul>
            <li>To create and maintain your user account</li>
            <li>To verify your identity when you log in</li>
            <li>To distinguish between Organisers and Attendees</li>
        </ul>

        <h3>2.2 Event Participation</h3>
        <ul>
            <li>To allow you to book spots at events (Attendees)</li>
            <li>To enable you to create and manage events (Organisers)</li>
            <li>To track event capacity and manage bookings</li>
            <li>To display your name to event organisers when you book an event</li>
        </ul>

        <h3>2.3 Communication</h3>
        <ul>
            <li>To send you notifications about your bookings</li>
            <li>To communicate important updates about events you're involved with</li>
            <li>To respond to your inquiries and provide customer support</li>
        </ul>

        <h3>2.4 Service Improvement</h3>
        <ul>
            <li>To understand how users interact with our platform</li>
            <li>To improve our services and user experience</li>
            <li>To identify and fix technical issues</li>
        </ul>

        <h2>3. How We Store and Protect Your Information</h2>

        <h3>3.1 Data Security</h3>
        <p>We implement industry-standard security measures to protect your information:</p>
        <ul>
            <li><strong>Password Encryption:</strong> All passwords are hashed using bcrypt encryption before storage</li>
            <li><strong>Secure Database:</strong> Your data is stored in a secure database with restricted access</li>
            <li><strong>Access Controls:</strong> Only authorized personnel can access user data</li>
            <li><strong>Regular Updates:</strong> We maintain our security systems with regular updates and patches</li>
        </ul>

        <h3>3.2 Data Retention</h3>
        <p>We retain your personal information for as long as your account is active or as needed to provide you services. If you wish to delete your account, you may contact us to request account deletion.</p>

        <h2>4. Information Sharing</h2>

        <h3>4.1 Within the Platform</h3>
        <p>Your information may be visible to other users in the following ways:</p>
        <ul>
            <li>Your name is visible to Organisers when you book their events</li>
            <li>Event organisers can see your name in their attendee lists</li>
            <li>Your user type (Organiser or Attendee) is displayed when you're logged in</li>
        </ul>

        <h3>4.2 Third-Party Sharing</h3>
        <p>We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
        <ul>
            <li>With your explicit consent</li>
            <li>To comply with legal obligations or court orders</li>
            <li>To protect the rights, property, or safety of our platform and users</li>
        </ul>

        <h2>5. Your Rights and Choices</h2>

        <p>You have the following rights regarding your personal information:</p>

        <h3>5.1 Access and Correction</h3>
        <ul>
            <li>You can view and update your account information at any time by logging into your account</li>
            <li>You can request a copy of the personal data we hold about you</li>
        </ul>

        <h3>5.2 Data Portability</h3>
        <ul>
            <li>You have the right to request your data in a portable format</li>
        </ul>

        <h3>5.3 Deletion</h3>
        <ul>
            <li>You can request deletion of your account and associated data</li>
            <li>Please note that some information may be retained for legal or legitimate business purposes</li>
        </ul>

        <h3>5.4 Opt-Out</h3>
        <ul>
            <li>You may opt out of certain communications by contacting us</li>
            <li>Some communications related to essential account functions cannot be opted out of</li>
        </ul>

        <div class="highlight">
            <h3>5.5 Managing Your Data</h3>
            <p><strong>To exercise any of these rights, please contact us using the information provided in the Contact section below.</strong></p>
        </div>

        <h2>6. Cookies and Tracking</h2>
        
        <p>Our platform uses cookies and similar technologies to:</p>
        <ul>
            <li>Keep you logged in to your account</li>
            <li>Remember your preferences</li>
            <li>Understand how you use our platform</li>
        </ul>
        <p>You can control cookies through your browser settings, but disabling cookies may limit your ability to use certain features of our platform.</p>

        <h2>7. Children's Privacy</h2>
        
        <p>Our platform is not intended for users under the age of 18. We do not knowingly collect personal information from children. If you believe we have inadvertently collected information from a child, please contact us immediately.</p>

        <h2>8. Changes to This Privacy Policy</h2>
        
        <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify users of significant changes by:</p>
        <ul>
            <li>Posting the updated policy on this page</li>
            <li>Updating the "Effective Date" at the top of this policy</li>
            <li>Sending notification emails for material changes</li>
        </ul>
        <p>Your continued use of the platform after changes are posted constitutes acceptance of the updated policy.</p>

        <h2>9. Legal Basis for Processing (GDPR Compliance)</h2>
        
        <p>For users in the European Economic Area, we process your personal data based on:</p>
        <ul>
            <li><strong>Consent:</strong> You have given explicit consent for processing your data</li>
            <li><strong>Contract:</strong> Processing is necessary to provide the services you requested</li>
            <li><strong>Legal Obligation:</strong> Processing is required to comply with legal requirements</li>
            <li><strong>Legitimate Interests:</strong> Processing is necessary for our legitimate business interests</li>
        </ul>

        <h2>10. International Data Transfers</h2>
        
        <p>Your data is stored on servers located in Australia. If you access our platform from outside Australia, please be aware that your information may be transferred to, stored, and processed in Australia where our servers are located.</p>

        <h2>11. Contact Us</h2>
        
        <p>If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> privacy@eventbooking.com</li>
            <li><strong>Phone:</strong> +61 (0) 123 456 789</li>
            <li><strong>Address:</strong> Event Booking System, Brisbane, Queensland, Australia</li>
        </ul>

        <h2>12. Your Consent</h2>
        
        <div class="highlight">
            <p><strong>By registering for an account and using our Event Booking System, you acknowledge that you have read, understood, and agree to be bound by this Privacy Policy and our Terms of Use.</strong></p>
        </div>

        <a href="{{ route('register') }}" class="back-link">← Back to Registration</a>
    </div>
</body>
</html>