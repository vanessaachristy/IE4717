function toggleInput(productID) {
    const productRow = document.getElementById(`product-${productID}`);
    const variants = productRow.getElementsByClassName("variants");

    for (variant of variants) {
        const text = variant.getElementsByTagName("b")[0];
        const input = variant.getElementsByTagName("input")[0];
        text.style.display = text.style.display === 'none' ? 'unset' : 'none';
        input.style.display = input.style.display === 'unset' ? 'none' : 'unset';
    }

}

function handlePriceChange(productID) {
    var isValid = true;
    const productRow = document.getElementById(`product-${productID}`);
    console.log(productID)

    const variants = productRow.getElementsByClassName("variants");
    for (variant of variants) {
        const input = variant.getElementsByTagName("input")[0];
        const invalid = variant.getElementsByTagName("p")[0];
        isValid = validateInput(input.value);
        if (!isValid) {
            invalid.style.display = 'unset';
        } else {
            invalid.style.display = 'none';
        }
    }

    return isValid;

}

// input must be a number
function validateInput(item) {
    let regex = /^(\.)?\d+(\.\d*)?/g
    return regex.test(item) && item > 0;
}

function handleSubmit() {
    for (let i = 1; i <= 3; i++) {
        if (handlePriceChange(i)) {
            return true;
        }
        else {
            alert("Please key in valid price! (No empty  / non numeric input)")
            return false;
        }
    }
}