<?php

declare(strict_types=1);

namespace Kkkonrad\FreeShippingBar\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class Progress implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly PriceCurrencyInterface $priceCurrency
    ) {
    }

    /**
     * @return array{percent: int, remaining: string}|null null when the bar should not show
     */
    public function get(): ?array
    {
        $threshold = (float)$this->scopeConfig->getValue(
            'checkout/free_shipping_bar/threshold',
            ScopeInterface::SCOPE_STORE
        );
        $quote = $this->checkoutSession->getQuote();
        if ($threshold <= 0 || !$quote->getItemsCount() || $quote->isVirtual()) {
            return null;
        }
        // Subtotal before discounts, the same figure core's "Subtotal (Excl. Tax)" rule condition reads
        [$percent, $remaining] = self::progress($threshold, (float)$quote->getBaseSubtotal());
        return [
            'percent' => $percent,
            'remaining' => $this->priceCurrency->convertAndFormat($remaining, false),
        ];
    }

    /**
     * @return array{0: int, 1: float} [percent 0-100, amount still missing in base currency]
     */
    public static function progress(float $threshold, float $subtotal): array
    {
        $remaining = max(0.0, round($threshold - $subtotal, 2));
        $percent = $remaining > 0 ? min(99, (int)floor($subtotal / $threshold * 100)) : 100;
        return [$percent, $remaining];
    }
}
