{{-- Admin-editable coefficients (App\Services\CoefficientService), printed
     into the page so the SPA can read them synchronously on load instead of
     calling an API. Read on the frontend via resources/js/services/coefficients.js. --}}
<script>window.__COEFFICIENTS__ = @json(\App\Services\CoefficientService::all());</script>
