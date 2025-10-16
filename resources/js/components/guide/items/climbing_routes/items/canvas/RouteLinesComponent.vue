<template>
    <div class="canvas-container">
        <img
            v-if="image_src"
            ref="backgroundImage"
            :src="image_src"
            class="background-image"
            @load="onImageLoad"
            @error="onImageError"
        />
        <canvas
            ref="canvas"
            class="canvas-overlay"
        />
    </div>
</template>

<script>
    // http://paperjs.org/
    const paper = require('paper');
    import axios from 'axios';

    export default {
        props: [
            'sector_image_id',
            'image_src'
        ],
        data: () => ({
            scope: null,
            routes: [],
            image: null,
            canvasSize: { width: 0, height: 0 },
            request: null
        }),
        mounted() {
            this.$nextTick(() => {
                this.initializeCanvas();
                this.fetchRoutes();
            });
        },
        methods: {
            createPathManually(layer, data, routeId) {
                console.log('Creating path manually for route', routeId, 'with data:', data);

                try {
                    // The data structure appears to be an array of Paper.js objects
                    // We need to find the Path object and extract its segments
                    if (Array.isArray(data)) {
                        data.forEach((item, index) => {
                            console.log('Processing item', index, ':', item);
                            if (item && item[1] && item[1].children) {
                                item[1].children.forEach((child, childIndex) => {
                                    console.log('Processing child', childIndex, ':', child);
                                    if (child && child[0] === 'Path' && child[1] && child[1].segments) {
                                        console.log('Found Path with segments:', child[1].segments);
                                        try {
                                            const path = new this.scope.Path();
                                            path.segments = child[1].segments.map(seg => {
                                                if (Array.isArray(seg) && seg.length >= 2) {
                                                    return new this.scope.Point(seg[0], seg[1]);
                                                }
                                                return null;
                                            }).filter(point => point !== null);
                                            path.strokeColor = '#ff0000'; // Default red
                                            path.strokeWidth = 3;
                                            path.strokeJoin = 'round';
                                            layer.addChild(path);
                                            console.log('Successfully created manual path for route', routeId);
                                        } catch (pathError) {
                                            console.error('Error creating manual path:', pathError);
                                        }
                                    }
                                });
                            }
                        });
                    } else if (data && data[1] && data[1].segments) {
                        // Direct path data
                        console.log('Direct path data found with segments:', data[1].segments);
                        const path = new this.scope.Path();
                        path.segments = data[1].segments.map(seg => {
                            if (Array.isArray(seg) && seg.length >= 2) {
                                return new this.scope.Point(seg[0], seg[1]);
                            }
                            return null;
                        }).filter(point => point !== null);
                        path.strokeColor = '#ff0000';
                        path.strokeWidth = 3;
                        path.strokeJoin = 'round';
                        layer.addChild(path);
                        console.log('Successfully created direct manual path for route', routeId);
                    }
                } catch (error) {
                    console.error('Error in createPathManually:', error);
                }
            },

            onImageLoad() {
                console.log('Background image loaded');
                // Re-initialize canvas with correct dimensions
                this.initializeCanvas();
                // Re-render routes if they exist
                if (this.routes && this.routes.length > 0) {
                    this.renderRoutes();
                }
            },
            onImageError() {
                console.log('Background image failed to load');
                this.initializeCanvas();
            },
            initializeCanvas() {
                // Get the canvas element
                const canvas = this.$refs.canvas;
                if (!canvas) {
                    console.log('Canvas element not found');
                    return;
                }

                // Get the background image element
                const bgImage = this.$refs.backgroundImage;
                if (bgImage && bgImage.complete && bgImage.naturalWidth > 0) {
                    // Image is already loaded
                    this.canvasSize = { width: bgImage.naturalWidth, height: bgImage.naturalHeight };
                    canvas.width = bgImage.naturalWidth;
                    canvas.height = bgImage.naturalHeight;
                    this.setupPaper();
                } else if (this.image_src) {
                    // Wait for image to load or use default size
                    this.canvasSize = { width: 800, height: 600 };
                    canvas.width = 800;
                    canvas.height = 600;
                    this.setupPaper();
                } else {
                    // No image, use default size
                    this.canvasSize = { width: 800, height: 600 };
                    canvas.width = 800;
                    canvas.height = 600;
                    this.setupPaper();
                }
            },

            setupPaper() {
                if (!this.$refs.canvas) {
                    console.log('Canvas ref not available for Paper.js setup');
                    return;
                }

                // Clean up previous scope if it exists
                if (this.scope) {
                    this.scope.project.clear();
                }

                this.scope = new paper.PaperScope();
                this.scope.setup(this.$refs.canvas);
                this.scope.view.draw();
            },

            loadImage() {
                if (this.image_src && this.scope) {
                    try {
                        const raster = new this.scope.Raster(this.image_src);
                        raster.onLoad = () => {
                            raster.fitBounds(this.scope.view.bounds);
                            this.scope.view.draw();
                        };
                        raster.onError = () => {
                            console.log('Failed to load raster image:', this.image_src);
                        };
                    } catch (error) {
                        console.log('Error creating raster:', error);
                    }
                }
            },

            fetchRoutes() {
                if (!this.sector_image_id) return;

                console.log('Fetching routes for sector_image_id:', this.sector_image_id);

                // Cancel previous request if exists
                if (this.request) {
                    this.request.cancel();
                }

                // Create a new cancel token
                this.request = axios.CancelToken.source();

                axios
                .get("route/get_route_jsons_for_sector_image", {
                    params: { sector_image_id: this.sector_image_id },
                    cancelToken: this.request.token
                })
                .then(response => {
                    console.log('Fetched routes response:', response.data);
                    this.routes = response.data;
                    console.log('Routes set to:', this.routes);
                    this.renderRoutes();
                })
                .catch(error => {
                    if (!axios.isCancel(error)) {
                        console.log('Error fetching routes:', error);
                    }
                });
            },

            renderRoutes() {
                console.log('Rendering routes, scope:', this.scope, 'routes:', this.routes);
                if (!this.scope || !this.routes || !this.routes.length) {
                    console.log('No scope or routes to render');
                    return;
                }

                // Clear existing route layers
                this.scope.project.layers.forEach(layer => {
                    if (layer.name && layer.name.startsWith('route-')) {
                        layer.remove();
                    }
                });

                this.routes.forEach((route, index) => {
                    console.log('Processing route:', route);
                    if (route.json) {
                        try {
                            let parsedData = route.json;
                            if (typeof route.json === 'string') {
                                // The JSON is double-encoded, so parse twice
                                parsedData = JSON.parse(route.json);
                                if (typeof parsedData === 'string') {
                                    parsedData = JSON.parse(parsedData);
                                }
                            }
                            console.log('Parsed JSON data for route', route.route_id, ':', parsedData);
                            console.log('Parsed JSON data:', parsedData);

                            // Create a new layer for each route
                            const routeLayer = new this.scope.Layer();
                            routeLayer.name = `route-${route.route_id}`;

                            // Import into the new layer
                            try {
                                console.log('Attempting to import JSON for route', route.route_id, 'with data:', parsedData);
                                const importedItems = routeLayer.importJSON(parsedData);
                                console.log('Imported items for route', route.route_id, ':', importedItems);

                                // Check if items were actually imported
                                if (importedItems && importedItems.length > 0) {
                                    console.log('Successfully imported', importedItems.length, 'items for route', route.route_id);
                                } else {
                                    console.log('No items imported for route', route.route_id);
                                }
                            } catch (importError) {
                                console.error('Error importing JSON for route', route.route_id, ':', importError);
                                console.error('Import error stack:', importError.stack);
                                console.error('Parsed data that caused error:', parsedData);
                                // Try alternative approach - manually create path
                                try {
                                    console.log('Trying manual path creation for route', route.route_id);
                                    this.createPathManually(routeLayer, parsedData, route.route_id);
                                } catch (manualError) {
                                    console.error('Manual path creation also failed:', manualError);
                                }
                            }

                            // Apply different color based on index
                            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#ff8000', '#8000ff', '#00ff80', '#ff0080'];
                            const color = colors[index % colors.length];

                            // Function to recursively set color
                            const setItemProperties = (item) => {
                                if (item.strokeColor) {
                                    item.strokeColor = color;
                                }
                                if (item.fillColor) {
                                    item.fillColor = color;
                                }
                                // Recursively apply to children
                                if (item.children) {
                                    item.children.forEach(child => setItemProperties(child));
                                }
                            };

                            // Apply properties to imported items
                            if (Array.isArray(importedItems)) {
                                importedItems.forEach(item => setItemProperties(item));
                            } else if (importedItems) {
                                setItemProperties(importedItems);
                            }

                            console.log('Applied colors to route', route.route_id);

                            // Add the layer to the project
                            this.scope.project.addLayer(routeLayer);
                            console.log('Added route layer to project');

                        } catch (error) {
                            console.log(`Error importing route ${route.route_id} JSON:`, error);
                        }
                    } else {
                        console.log('Route has no JSON data:', route);
                    }
                });

                // Update the view
                this.scope.view.draw();
                console.log('View updated - routes should now be visible on canvas');

                // Log final project state
                console.log('Final project layers:', this.scope.project.layers.length);
                this.scope.project.layers.forEach((layer, index) => {
                    console.log(`Layer ${index}: ${layer.name}, children: ${layer.children ? layer.children.length : 0}`);
                });
            },
        },
        watch: {
            sector_image_id: function(newVal) {
                if (newVal) {
                    this.fetchRoutes();
                }
            },
            image_src: function(newVal) {
                if (newVal) {
                    this.loadImage();
                }
            }
        },
        beforeDestroy() {
            if (this.request) {
                this.request.cancel();
            }
            if (this.scope) {
                this.scope.project.clear();
            }
        }
    }
</script>

<style scoped>
    .canvas-container {
        position: relative;
        display: inline-block;
        width: 100%;
        height: auto;
    }

    .background-image {
        width: 100%;
        height: auto;
        display: block;
    }

    .canvas-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none; /* Allow clicks to pass through to background */
        border: none;
    }

    /* stylize panel */
    paperjs-layer-panel main {
        position : fixed;
        top      : 15px;
        z-index  : 1;
        left     : 15px;
    }
</style>
