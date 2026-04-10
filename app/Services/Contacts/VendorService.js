// app/Services/Contacts/VendorService.js
const Contact = require('../../models/Contact');
const Decimal = require('decimal.js');

class VendorService {
    /**
     * Calculate TDS based on Indian Income Tax Sections
     * @param {number} amount - The base invoice amount (excluding GST)
     * @param {string} section - 194C, 194J, 194H, etc.
     * @param {boolean} hasPan - If no PAN, TDS is usually 20%
     */
    static calculateTDS(amount, section, hasPan = true) {
        const base = new Decimal(amount);
        let rate = new Decimal(0);

        if (!hasPan) {
            rate = new Decimal(0.20); // Default 20% without PAN
        } else {
            switch (section) {
                case '194C': // Contractors/Sub-contractors
                    rate = new Decimal(0.01); // 1% for Indiv/HUF, 2% for Others
                    break;
                case '194J': // Professional/Technical Services
                    rate = new Decimal(0.10); // 10% (or 2% for certain technical services)
                    break;
                case '194H': // Commission or Brokerage
                    rate = new Decimal(0.05); // 5%
                    break;
                default:
                    rate = new Decimal(0);
            }
        }

        const tdsAmount = base.times(rate);
        return {
            section: section,
            baseAmount: base.toFixed(2),
            tdsRate: rate.times(100).toString() + '%',
            tdsAmount: tdsAmount.toFixed(2),
            payableAmount: base.minus(tdsAmount).toFixed(2)
        };
    }

    /**
     * Create a Vendor with full compliance data
     */
    static async createVendor(data, tenantId) {
        const vendorData = {
            ...data,
            category: 'Vendor',
            tenant_id: tenantId,
            meta_data: {
                gstin: data.gstin,
                pan: data.pan,
                tds_section: data.tds_section || '194C',
                bank_details: {
                    account_no: data.account_no,
                    ifsc: data.ifsc
                }
            }
        };

        return await Contact.create(vendorData);
    }
}

module.exports = VendorService;