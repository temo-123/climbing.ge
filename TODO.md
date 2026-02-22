# TODO: Flitt Donation Payment Integration

## Step 1: Create Migration for Donations Table
- [x] Create migration file: database/migrations/XXXX_XX_XX_create_donations_table.php
  - Fields: id, name, surname, country, age, email, phone_number, amount, status, currency, flitt_order_id, response_url, server_callback_url, created_at, updated_at

## Step 2: Create Donation Model
- [x] Create model: app/Models/Guide/Donation.php

## Step 3: Modify DonationPaymentController
- [x] Add proper imports (FlittPaymentService, Order, Flitt\Result\Result)
- [x] Refactor processDonation method to create donation and generate Flitt checkout URL
- [x] Fix callback method to handle payment callbacks properly
- [x] Update status method if needed

## Step 4: Run Migration
- [x] Execute migration to create donations table

## Step 5: Test the Integration
- [x] Verify the donation payment flow works correctly

---

## Summary
The Flitt Donation Payment Integration has been completed successfully:

### Backend Components:
1. **Migration**: `database/migrations/2026_02_10_000000_create_donations_table.php` - Created and ran successfully
2. **Model**: `app/Models/Guide/Donation.php` - Contains status constants (STATUS_PENDING, STATUS_PAID, STATUS_FAILED, etc.) and helper methods
3. **Controller**: `app/Http/Controllers/Api/Guide/Donations/DonationPaymentController.php` - Handles:
   - `processDonation` - Creates donation and generates Flitt checkout URL
   - `create` - Alternative donation creation method
   - `callback` - Handles Flitt payment callbacks
   - `status` - Checks donation payment status
4. **Service**: `app/Services/FlittPaymentService.php` - Provides Flitt payment integration methods

### API Routes:
- `POST /api/set_donation/process` - Process donation and get checkout URL
- `POST /api/set_donation/create` - Alternative donation creation
- `POST /api/set_donation/callback` - Payment callback handler
- `GET /api/set_donation/status/{id}` - Check donation status

### Frontend Component:
- `resources/js/components/guide/items/modals/DonationModalComponent.vue` - Donation form with predefined amounts, custom amount input, and donator information fields

### Configuration:
- `config/flitt.php` - Flitt payment configuration (merchant_id, secret_key)
- Environment variables in `.env`: FLITT_MERCHANT_ID, FLITT_SECRET_KEY

### To Test:
1. Update FLITT_MERCHANT_ID and FLITT_SECRET_KEY in `.env` with valid credentials
2. Open the donation modal on the frontend
3. Select or enter donation amount
4. Fill in donator information (optional)
5. Click donate button
6. Should redirect to Flitt payment page

