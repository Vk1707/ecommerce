$(document).ready(function () {
    $('#contactForm').on('submit', function (e) {
        let isValid = true;

        // Validate Name
        if ($('input[name="name"]').val().trim() === '') {
            $('input[name="name"]').next('.help-block').text('Please enter your name.');
            isValid = false;
        } else {
            $('input[name="name"]').next('.help-block').text('');
        }

        // Validate Email
        let email = $('input[name="email"]').val().trim();
        let emailPattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        if (email === '') {
            $('input[name="email"]').next('.help-block').text('Please enter your email.');
            isValid = false;
        } else if (!emailPattern.test(email)) {
            $('input[name="email"]').next('.help-block').text('Please enter a valid email.');
            isValid = false;
        } else {
            $('input[name="email"]').next('.help-block').text('');
        }

        // Validate Password
        let password = $('input#password').val().trim();
        if (password === '') {
            $('input#password').next('.help-block').text('Please enter a password.');
            isValid = false;
        } else {
            $('input#password').next('.help-block').text('');
        }

        // Validate Re-entered Password
        let repassword = $('input#repassword').val().trim();
        if (repassword === '') {
            $('input#repassword').next('.help-block').text('Please re-enter your password.');
            isValid = false;
        } else if (password !== repassword) {
            $('input#repassword').next('.help-block').text('Passwords do not match.');
            isValid = false;
        } else {
            $('input#repassword').next('.help-block').text('');
        }

        // Validate Mobile Number
        let mobile = $('input[name="mobile"]').val().trim();
        let mobilePattern = /^[0-9]{10}$/;
        if (mobile === '') {
            $('input[name="mobile"]').next('.help-block').text('Please enter your mobile number.');
            isValid = false;
        } else if (!mobilePattern.test(mobile)) {
            $('input[name="mobile"]').next('.help-block').text('Please enter a valid 10-digit mobile number.');
            isValid = false;
        } else {
            $('input[name="mobile"]').next('.help-block').text('');
        }

        // Validate Address
        if ($('input[name="address"]').val().trim() === '') {
            $('input[name="address"]').next('.help-block').text('Please enter your address.');
            isValid = false;
        } else {
            $('input[name="address"]').next('.help-block').text('');
        }

        // Validate Gender
        if ($('select[name="gender"]').val() === '') {
            $('select[name="gender"]').next('.help-block').text('Please select your gender.');
            isValid = false;
        } else {
            $('select[name="gender"]').next('.help-block').text('');
        }

        // Validate Date of Birth
        let dob = $('input[name="dob"]').val().trim();
        let dobPattern = /^\d{4}-\d{2}-\d{2}$/;
        if (dob === '') {
            $('input[name="dob"]').next('.help-block').text('Please enter your date of birth.');
            isValid = false;
        } else if (!dobPattern.test(dob)) {
            $('input[name="dob"]').next('.help-block').text('Please enter a valid date of birth (YYYY-MM-DD).');
            isValid = false;
        } else {
            $('input[name="dob"]').next('.help-block').text('');
        }

        // Validate State
        if ($('input[name="state"]').val().trim() === '') {
            $('input[name="state"]').next('.help-block').text('Please enter your state.');
            isValid = false;
        } else {
            $('input[name="state"]').next('.help-block').text('');
        }

        // Validate City
        if ($('input[name="city"]').val().trim() === '') {
            $('input[name="city"]').next('.help-block').text('Please enter your city.');
            isValid = false;
        } else {
            $('input[name="city"]').next('.help-block').text('');
        }

        // Validate Pincode
        let pincode = $('input[name="pincode"]').val().trim();
        let pincodePattern = /^[0-9]{6}$/;
        if (pincode === '') {
            $('input[name="pincode"]').next('.help-block').text('Please enter your pincode.');
            isValid = false;
        } else if (!pincodePattern.test(pincode)) {
            $('input[name="pincode"]').next('.help-block').text('Please enter a valid 6-digit pincode.');
            isValid = false;
        } else {
            $('input[name="pincode"]').next('.help-block').text('');
        }

        // Prevent form submission if there are validation errors
        if (!isValid) {
            e.preventDefault();
        }
    });
});
