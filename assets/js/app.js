document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const sizeSelect = document.getElementById('size_id');
    const fillingSelect = document.getElementById('filling_id');
    const toppingSelect = document.getElementById('topping_id');
    const decorationSelect = document.getElementById('decoration_id');
    const deliveryDateInput = document.getElementById('delivery_date');
    const estimatedPriceEl = document.getElementById('estimated-price');

    if (!productSelect || !sizeSelect || !fillingSelect || !toppingSelect || !decorationSelect) {
        return;
    }

    const formatCurrency = (amount) => new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        maximumFractionDigits: 0,
    }).format(amount);

    const updateSummary = () => {
        const productName = productSelect.options[productSelect.selectedIndex]?.text || '-';
        const sizeName = sizeSelect.options[sizeSelect.selectedIndex]?.text || '-';
        const fillingName = fillingSelect.options[fillingSelect.selectedIndex]?.text || '-';
        const toppingName = toppingSelect.options[toppingSelect.selectedIndex]?.text || '-';
        const decorationName = decorationSelect.options[decorationSelect.selectedIndex]?.text || '-';
        const customText = document.getElementById('custom_text')?.value?.trim() || '-';
        const dateValue = deliveryDateInput?.value || '-';

        document.getElementById('summary-product').textContent = productName;
        document.getElementById('summary-size').textContent = sizeName;
        document.getElementById('summary-filling').textContent = fillingName;
        document.getElementById('summary-topping').textContent = toppingName;
        document.getElementById('summary-decoration').textContent = decorationName;
        document.getElementById('summary-text').textContent = customText;
        document.getElementById('summary-date').textContent = dateValue;
    };

    const updatePrice = () => {
        const productBase = Number(productSelect.selectedOptions[0]?.dataset.basePrice || 0);
        const sizePrice = Number(sizeSelect.selectedOptions[0]?.dataset.price || 0);
        const fillingPrice = Number(fillingSelect.selectedOptions[0]?.dataset.price || 0);
        const toppingPrice = Number(toppingSelect.selectedOptions[0]?.dataset.price || 0);
        const decorationPrice = Number(decorationSelect.selectedOptions[0]?.dataset.price || 0);
        const total = productBase + sizePrice + fillingPrice + toppingPrice + decorationPrice;

        if (estimatedPriceEl) {
            estimatedPriceEl.textContent = formatCurrency(total);
        }
    };

    [productSelect, sizeSelect, fillingSelect, toppingSelect, decorationSelect].forEach((element) => {
        element.addEventListener('change', () => {
            updatePrice();
            updateSummary();
        });
    });

    const textInput = document.getElementById('custom_text');
    if (textInput) {
        textInput.addEventListener('input', updateSummary);
    }

    if (deliveryDateInput) {
        deliveryDateInput.addEventListener('change', updateSummary);
    }

    updatePrice();
    updateSummary();
});
