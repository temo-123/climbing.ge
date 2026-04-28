import { ref } from 'vue';

// Kept as a plain ref since app.js router hooks use it directly.
// New code should use useRouteLoaderStore from Pinia instead.
export const isRouteLoading = ref(false);
