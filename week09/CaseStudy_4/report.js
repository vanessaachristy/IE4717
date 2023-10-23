function onCheckboxClicked(id) {
    let checkbox = document.getElementById(id);

    if (checkbox.checked) {
        document.getElementById("reportForm").submit();
    } else {
        alert("Invalid!");
    }
}

window.onload = () => {
    let revenueCheckbox = document.getElementById("revenueCheckbox");
    let quantityCheckbox = document.getElementById("quantityCheckbox");

    revenueCheckbox.checked = false;
    quantityCheckbox.checked = false;

}