function validateForm() {
    let name = document.getElementById('name');
    let nameRegex = /^[A-Za-z\s]+$/;
    let nameLength = name.value.length;
    let invalidName = document.getElementById('invalid-name');
    invalidName.style.display = 'none';


    let email = document.getElementById('email');
    let regex = /^[\w.-]*@([A-Za-z]*\.){1,3}[a-zA-Z]{3}$/;
    ;
    let invalidEmail = document.getElementById('invalid-email');
    invalidEmail.style.display = 'none';


    let date = document.getElementById('date')
    let dateInput = new Date(date.value);
    let dateTs = dateInput.getTime();
    let currDate = new Date();
    let currTs = currDate.setUTCHours(0, 0, 0, 0);
    let invalidDate = document.getElementById('invalid-date');
    invalidDate.style.display = 'none';

    if (!nameRegex.test(name.value) || nameLength < 3) {
        invalidName.style.display = 'unset';
        alert("Invalid name! Name should be alphabet characters and at least 3 characters");
    }

    if (!regex.test(email.value)) {
        invalidEmail.style.display = 'unset';
        alert("Invalid email! Email domain should contain 2 to 4 address extensions. Last extension shall contain only and no more than 3 alphabets!");

    }

    if (dateTs <= currTs) {
        invalidDate.style.display = 'unset';
        alert("Invalid date! Date should be later than today!");

    };

    if (regex.test(email.value) && dateTs > currTs && nameRegex.test(name.value) && nameLength >= 3) {
        alert("Submission successful!")
        return true;
    } else {
        return false;
    }
}

function init() {

    let form = document.getElementById("jobsForm");
    let experience = document.getElementById('experience');
    let expLen = document.getElementById('experience-len');
    experience.onkeyup = () => {
        console.log("e");
        expLen.innerText = `${experience.value.length}/300`;
    }
    experience.onkeyup = () => {
        console.log("e");

        expLen.innerText = `${experience.value.length}/300`;
    }
    form.onsubmit = validateForm;
}

window.onload = init;
