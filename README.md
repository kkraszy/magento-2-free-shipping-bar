# Kkkonrad_FreeShippingBar

"Do darmowej dostawy brakuje Ci X" with a thin progress bar in the cart summary, above
"Przejdź do kasy", Hyvä and Luma.

- **Admin:** Stores > Configuration > Sales > Checkout > **Free Shipping Bar** > threshold
  (base currency, subtotal before discounts). Empty or 0 hides the bar.
- **Display only.** It does not grant free shipping - keep the threshold equal to whatever
  does (here: cart price rule "Spend $50 or more", `base_subtotal >= 50`).
- Hidden for an empty or virtual-only cart. Turns green with "Masz darmową dostawę!" once reached.
- CLI: `bin/magento config:set checkout/free_shipping_bar/threshold 50`
- Test: `vendor/bin/phpunit app/code/Kkkonrad/FreeShippingBar/Test/Unit`
