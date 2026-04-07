const Decimal = require('decimal.js');



class FinanceService {

    // Basic INR Calculation with 2-decimal precision

    static calculateGST(amount) {

        const base = new Decimal(amount);

        const rate = new Decimal(0.18); // 18% GST

        const gstAmount = base.times(rate);

        return {

            cgst: gstAmount.div(2).toFixed(2),

            sgst: gstAmount.div(2).toFixed(2),

            total: base.plus(gstAmount).toFixed(2)

        };

    }

}



module.exports = FinanceService;