
function calcSubtotal(price, qty) {
    if (qty && price) {
        return (price * qty)
    } return 0;

}

function countOrder() {

    // Order form
    const orderForm = document.getElementById('order-form')

    // Items quantities
    const qtyJustJava = document.getElementById('qty-just-java')
    const qtyCafeAuLait = document.getElementById('qty-cafe-au-lait')
    const qtyIcedCappuccino = document.getElementById('qty-iced-cap')

    // Items Subtotals
    const subJustJava = document.getElementById('subtotal-just-java')
    const subCafeAuLait = document.getElementById('subtotal-cafe-au-lait')
    const subIcedCappuccino = document.getElementById('subtotal-iced-cap')

    let orderCafeAuLait = document.getElementsByName("cafeAuLait[]");
    let orderCappuccino = document.getElementsByName("icedCappuccino[]");

    let icedCappuccinoChecked = orderCafeAuLait[0].id;
    let cafeAuLaitChecked = orderCappuccino[0].id;

    // Total order
    const totalOrder = document.getElementById('total-order');

    function getJustJavaPrice() {
        let price = document.getElementById("priceJustJava");
        let justJavaPrice = parseFloat(price.innerHTML.replace('$', '')); // Remove the '$' and convert to a number
        return justJavaPrice;
    }

    function getSelectedCafeAuLaitPrice() {
        for (let i = 0; i < orderCafeAuLait.length; i++) {
            if (orderCafeAuLait[i].checked) {
                cafeAuLaitChecked = orderCafeAuLait[i].id;
                return parseFloat(document.getElementById(`price-${cafeAuLaitChecked}`).innerHTML.replace("$", ""));
            }
        }
    }
    function getSelectedIcedCappuccinoPrice() {
        for (let i = 0; i < orderCappuccino.length; i++) {
            if (orderCappuccino[i].checked) {
                icedCappuccinoChecked = orderCappuccino[i].id;
                return parseFloat(document.getElementById(`price-${icedCappuccinoChecked}`).innerHTML.replace("$", ""));
            }
        }
    }


    orderForm.onchange = () => {

        subJustJava.value = calcSubtotal(getJustJavaPrice(), qtyJustJava.value);

        let valid = false;

        if (getSelectedCafeAuLaitPrice()) {
            const priceSelectedCafeAuLait = getSelectedCafeAuLaitPrice();
            let qtyOrderedCafeAuLait = parseInt(qtyCafeAuLait.value);
            subCafeAuLait.value = calcSubtotal(priceSelectedCafeAuLait, qtyOrderedCafeAuLait);
        }
        if (getSelectedIcedCappuccinoPrice()) {
            const priceSelectedIcedCappuccino = getSelectedIcedCappuccinoPrice();
            let qtyOrderedIcedCappuccino = parseInt(qtyIcedCappuccino.value);
            console.log(qtyOrderedIcedCappuccino, priceSelectedIcedCappuccino)
            subIcedCappuccino.value = calcSubtotal(priceSelectedIcedCappuccino, qtyOrderedIcedCappuccino)
        }

        valid = validateInput(qtyIcedCappuccino.value) && validateInput(qtyIcedCappuccino.value) && validateInput(qtyJustJava.value)
        totalOrder.value = parseFloat(subJustJava.value) +
            parseFloat(subCafeAuLait.value) +
            parseFloat(subIcedCappuccino.value);

    }

    let checkoutBtn = document.getElementById('checkout-btn');
    checkoutBtn.onclick = () => {
        if (valid) {
            // alert(`Just Java x${qtyJustJava.value}\nCafe au Lait(${cafeAuLaitChecked}) x${qtyCafeAuLait.value}\nIced Cappuccino(${icedCappuccinoChecked}) x${qtyIcedCappuccino.value}`)
            return true;
        } else {
            alert("Invalid");
            return false;
        }
        // window.location.reload();
    }
}

// input must be a number
function validateInput(item) {
    let regex = /^(\.)?\d+(\.\d*)?/g
    return regex.test(item) && item > 0;
}

function init() {
    countOrder();

}

window.onload = init;