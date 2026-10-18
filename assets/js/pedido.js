document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const sizeSelect = document.getElementById('size_id');
    const fillingSelect = document.getElementById('filling_id');
    const toppingSelect = document.getElementById('topping_id');
    const decorationSelect = document.getElementById('decoration_id');
    const inputCustomText = document.getElementById('custom_text');
    const deliveryDateInput = document.getElementById('delivery_date');
    const sumFields = [productSelect, sizeSelect, fillingSelect, toppingSelect, decorationSelect];

    const formatMoney = (value) => new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        maximumFractionDigits: 0,
    }).format(value);

    const updateSummary = () => {
        const base = productSelect?.selectedOptions[0]?.text || '-';
        const size = sizeSelect?.selectedOptions[0]?.text || '-';
        const filling = fillingSelect?.selectedOptions[0]?.text || '-';
        const topping = toppingSelect?.selectedOptions[0]?.text || '-';
        const decoration = decorationSelect?.selectedOptions[0]?.text || '-';
        const customText = inputCustomText?.value?.trim() || '-';
        const dateValue = deliveryDateInput?.value || '-';

        document.getElementById('summary-product').textContent = base;
        document.getElementById('summary-size').textContent = size;
        document.getElementById('summary-filling').textContent = filling;
        document.getElementById('summary-topping').textContent = topping;
        document.getElementById('summary-decoration').textContent = decoration;
        document.getElementById('summary-text').textContent = customText;
        document.getElementById('summary-date').textContent = dateValue;
    };

    const updatePrice = () => {
        const productBase = Number(productSelect?.selectedOptions[0]?.dataset.basePrice || 0);
        const sizePrice = Number(sizeSelect?.selectedOptions[0]?.dataset.price || 0);
        const fillingPrice = Number(fillingSelect?.selectedOptions[0]?.dataset.price || 0);
        const toppingPrice = Number(toppingSelect?.selectedOptions[0]?.dataset.price || 0);
        const decorationPrice = Number(decorationSelect?.selectedOptions[0]?.dataset.price || 0);
        const total = productBase + sizePrice + fillingPrice + toppingPrice + decorationPrice;
        const priceBox = document.getElementById('estimated-price');
        if (priceBox) priceBox.textContent = formatMoney(total);
    };

    sumFields.filter(Boolean).forEach((field) => field.addEventListener('change', () => {
        updatePrice();
        updateSummary();
    }));

    inputCustomText?.addEventListener('input', updateSummary);
    deliveryDateInput?.addEventListener('change', updateSummary);

    updatePrice();
    updateSummary();
});
