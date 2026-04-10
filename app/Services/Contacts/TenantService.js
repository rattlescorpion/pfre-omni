// app/Services/Contacts/TenantService.js
const Contact = require('../../models/Contact');
const Decimal = require('decimal.js');

class TenantService {
    /**
     * Create a Tenant and link to property lease
     */
    static async createTenant(data, tenantId) {
        // 1. Logic for Aadhaar/PAN validation for Indian KYC
        const tenantData = {
            ...data,
            category: 'Tenant',
            tenant_id: tenantId, // Multi-tenant ERP ID
            meta_data: {
                occupation: data.occupation,
                permanent_address: data.permanent_address,
                emergency_contact: data.emergency_contact
            }
        };

        return await Contact.create(tenantData);
    }

    /**
     * Calculate security deposit and advance rent
     */
    static calculateInitialPayable(monthlyRent, depositMonths) {
        const rent = new Decimal(monthlyRent);
        const deposit = rent.times(depositMonths);
        return {
            rent: rent.toFixed(2),
            deposit: deposit.toFixed(2),
            total: rent.plus(deposit).toFixed(2)
        };
    }
}

module.exports = TenantService;