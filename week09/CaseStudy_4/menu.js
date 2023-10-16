
function calcSubtotal(price, qty) {
    return (price * qty)
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


    let icedCappuccinoChecked = orderForm.icedCappuccino[0].id;
    let cafeAuLaitChecked = orderForm.cafeAuLait[0].id;

    // Total order
    const totalOrder = document.getElementById('total-order');

    function getSelectedCafeAuLaitPrice() {
        for (let i = 0; i < orderForm.cafeAuLait.length; i++) {
            if (orderForm.cafeAuLait[i].checked) {
                cafeAuLaitChecked = orderForm.cafeAuLait[i].id;
                return (parseFloat(orderForm.cafeAuLait[i].value))
            }
        }
    }
    function getSelectedIcedCappuccinoPrice() {
        for (let i = 0; i < orderForm.icedCappuccino.length; i++) {
            if (orderForm.icedCappuccino[i].checked) {
                icedCappuccinoChecked = orderForm.icedCappuccino[i].id;
                return (parseFloat(orderForm.icedCappuccino[i].value))
            }
        }
    }


    orderForm.onchange = () => {
        subJustJava.value = qtyJustJava.value;

        if (getSelectedCafeAuLaitPrice()) {
            const priceSelectedCafeAuLait = getSelectedCafeAuLaitPrice();
            const qtyOrderedCafeAuLait = parseInt(qtyCafeAuLait.value);
            subCafeAuLait.value = calcSubtotal(priceSelectedCafeAuLait, qtyOrderedCafeAuLait)
        }
        if (getSelectedIcedCappuccinoPrice()) {
            const priceSelectedIcedCappuccino = getSelectedIcedCappuccinoPrice()
            const qtyOrderedIcedCappuccino = parseInt(qtyIcedCappuccino.value)
            subIcedCappuccino.value = calcSubtotal(priceSelectedIcedCappuccino, qtyOrderedIcedCappuccino)
        }

        totalOrder.value = parseFloat(subJustJava.value) +
            parseFloat(subCafeAuLait.value) +
            parseFloat(subIcedCappuccino.value)

    }

    let checkoutBtn = document.getElementById('checkout-btn');
    checkoutBtn.onclick = () => {
        alert(`Just Java x${qtyJustJava.value}\nCafe au Lait(${cafeAuLaitChecked}) x${qtyCafeAuLait.value}\nIced Cappuccino(${icedCappuccinoChecked}) x${qtyIcedCappuccino.value}`)
        window.location.reload();
    }
}


function init() {
    countOrder();

}

window.onload = init;