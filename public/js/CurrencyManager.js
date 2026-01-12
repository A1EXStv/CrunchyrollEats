class CurrencyManager {
    constructor() {
        this.apiKey = 'fca_live_seZo6w56isEeCVqtNHLFESLo5vS2AKENB7LjAViK';
        this.apiUrl = 'https://api.freecurrencyapi.com/v1/latest';
        this.baseCurrency = 'EUR';
        this.rates = { EUR: 1 }; // Default
        this.currentCurrency = localStorage.getItem('currency') || 'EUR';

        this.init();
    }

    async init() {
        // Set initial select value
        const select = document.getElementById('currencySelect');
        if (select) {
            select.value = this.currentCurrency;
            select.addEventListener('change', (e) => this.setCurrency(e.target.value));
        }

        // Try to load cached rates
        const cached = localStorage.getItem('exchange_rates');
        if (cached) {
            const data = JSON.parse(cached);
            // Cache for 1 hour
            if (Date.now() - data.timestamp < 3600000) {
                this.rates = { ...this.rates, ...data.rates };
                this.updatePrices();
                return;
            }
        }

        await this.fetchRates();
    }

    async fetchRates() {
        try {
            const response = await fetch(`${this.apiUrl}?apikey=${this.apiKey}&base_currency=${this.baseCurrency}&currencies=USD,JPY`);
            const data = await response.json();

            if (data.data) {
                this.rates = { ...this.rates, ...data.data };

                // Cache rates
                localStorage.setItem('exchange_rates', JSON.stringify({
                    timestamp: Date.now(),
                    rates: data.data
                }));

                this.updatePrices();
            }
        } catch (error) {
            console.error('Error fetching exchange rates:', error);
        }
    }

    setCurrency(currency) {
        this.currentCurrency = currency;
        localStorage.setItem('currency', currency);
        this.updatePrices();
    }

    convert(amountInEur) {
        const rate = this.rates[this.currentCurrency] || 1;
        return amountInEur * rate;
    }

    format(amount) {
        return new Intl.NumberFormat(navigator.language, {
            style: 'currency',
            currency: this.currentCurrency
        }).format(amount);
    }

    updatePrices() {
        // Find elements. We look for specific patterns or data attributes.
        // Strategy: 
        // 1. Look for elements with 'currency-price' class (NEW PREFERRED WAY)
        // 2. Look for elements with 'data-price-eur'
        // 3. Fallback to broad classes only if they are leaf nodes or specific legacy containers

        // Priority to specific price spans
        const specificPrices = document.querySelectorAll('.currency-price');

        // Legacy/General selectors (be careful not to select containers that already have children handled above)
        // We exclude elements that contain other elements to avoid wiping out HTML structure
        const generalPrices = document.querySelectorAll('.precio, .price, .product-price, .card-text');

        // Helper to process list
        const processElements = (list) => {
            list.forEach(el => {
                // Skip if this element has children (unless it's a known leaf like a span)
                // If we are targeting .currency-price, we assume it's safe.
                // If targeting generic .product-price, we check if it has children.
                if (!el.classList.contains('currency-price') && el.children.length > 0) return;

                // First time: store original EUR price
                if (!el.dataset.eurPrice) {
                    const text = el.innerText.trim();
                    // Extract number from "€ 10.50" or "10.50 €"
                    const match = text.match(/[0-9]+[.,][0-9]+/);
                    if (match) {
                        // Normalize decimal (comma to dot)
                        let numStr = match[0].replace(',', '.');
                        el.dataset.eurPrice = parseFloat(numStr);
                    } else {
                        return; // Skip if no number found
                    }
                }

                const eur = parseFloat(el.dataset.eurPrice);
                const converted = this.convert(eur);
                el.innerText = this.format(converted);
            });
        };

        processElements(specificPrices);
        processElements(generalPrices);

        // Also update cart total if exists
        const cartTotal = document.getElementById('cart-total');
        if (cartTotal) {
            if (!cartTotal.dataset.eurPrice) {
                // Assuming format "Total: 100 €"
                const match = cartTotal.innerText.match(/[0-9]+[.,][0-9]+/);
                if (match) cartTotal.dataset.eurPrice = parseFloat(match[0].replace(',', '.'));
            }
            if (cartTotal.dataset.eurPrice) {
                const newVal = this.format(this.convert(parseFloat(cartTotal.dataset.eurPrice)));
                cartTotal.innerText = `Total: ${newVal}`;
            }
        }
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    window.CurrencyManager = new CurrencyManager();
});
