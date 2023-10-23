function onDateChange() {
    let dateInput = document.getElementById("datepicker");
    console.log(dateInput.value);
    let form = document.getElementById("reportContentForm");
    if (dateInput.value !== "") {
        form.submit();
    }
}