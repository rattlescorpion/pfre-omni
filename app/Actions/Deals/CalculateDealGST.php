<?php
declare(strict_types=1);

namespace App\Actions\Deals;

use App\Models\Deal;

class CalculateDealGST
{
    /**
     * Calculate and apply GST to a Deal's brokerage amount.
     * Defaults to Maharashtra for intra-state CGST/SGST splitting.
     */
    public function execute(Deal $deal, string $partyState = 'Maharashtra'): Deal
    {
        $taxable = (string) $deal->brokerage_amount;
        
        // 18% Total GST Rate
        if ($partyState === 'Maharashtra') {
            // Intra-state: 9% CGST + 9% SGST
            $deal->cgst_amount = bcmul($taxable, bcdiv('9', '100', 6), 2);
            $deal->sgst_amount = bcmul($taxable, bcdiv('9', '100', 6), 2);
            $deal->igst_amount = '0.00';
        } else {
            // Inter-state: 18% IGST
            $deal->cgst_amount = '0.00';
            $deal->sgst_amount = '0.00';
            $deal->igst_amount = bcmul($taxable, bcdiv('18', '100', 6), 2);
        }

        $totalGst = bcadd(
            bcadd($deal->cgst_amount, $deal->sgst_amount, 2), 
            $deal->igst_amount, 
            2
        );

        $deal->total_brokerage_with_gst = bcadd($taxable, $totalGst, 2);
        
        $deal->save();

        return $deal;
    }
}