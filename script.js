document.addEventListener("DOMContentLoaded", function () {

let amountInput = document.getElementById("sendAmount");
let currencySelect = document.getElementById("currency");
let resultBox = document.getElementById("result");

function calculate() {
    let amount = parseFloat(amountInput.value);

    if (!amount || amount <= 0) {
        resultBox.innerHTML = "0";
        return;
    }

    let currency = currencySelect.value;
    let rate = aldahabRates[currency];

    if (!rate) {
        resultBox.innerHTML = "0";
        return;
    }

    let result = amount * parseFloat(rate);
    resultBox.innerHTML = result.toFixed(2) + " " + currency;
}

amountInput.addEventListener("input", calculate);
currencySelect.addEventListener("change", calculate);

calculate();

});