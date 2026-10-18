<?php

/**
 * Legal details of the organization that receives donations (shown in the donation
 * terms and the About page "Organization Contacts" block). Applied to site data by
 * migration 2026_10_19_110000_update_donation_legal_texts.php — set them before migrating.
 * Separate from COMPANY_* (shop invoice issuer), which may be a different entity.
 */
return [
    'legal_name_us' => env('ORGANIZATION_LEGAL_NAME_US'),
    'legal_name_ka' => env('ORGANIZATION_LEGAL_NAME_KA'),
    'legal_form_us' => env('ORGANIZATION_LEGAL_FORM_US', 'Non-entrepreneurial (non-commercial) legal entity (NNLE)'),
    'legal_form_ka' => env('ORGANIZATION_LEGAL_FORM_KA', 'არასამეწარმეო (არაკომერციული) იურიდიული პირი (ა(ა)იპ)'),
    'reg_number'    => env('ORGANIZATION_REG_NUMBER'),
    'address_us'    => env('ORGANIZATION_ADDRESS_US'),
    'address_ka'    => env('ORGANIZATION_ADDRESS_KA'),
    // Actual tax status + legal basis (Google Play: donations outside Play billing need tax-exempt status).
    // Leave empty if the organization doesn't have one — the line is then omitted.
    'tax_status_us' => env('ORGANIZATION_TAX_STATUS_US'),
    'tax_status_ka' => env('ORGANIZATION_TAX_STATUS_KA'),
    // Falls back to the site contact email (sites.email)
    'email'         => env('ORGANIZATION_EMAIL'),
];
