<template>
     <div class="row">
        
        <left-menu />

        <div class="media-manager-container">
            <!-- Main Content Area -->
            <div class="main-content">
                
                <!-- Header Section -->
                <div class="header-section">
                    <h1 class="page-title">
                        <i class="fas fa-images"></i>
                        Media Files Manager
                    </h1>
                    
                    <!-- Search and Filter Bar -->
                    <div class="toolbar">
                        <div class="search-bar">
                            <input 
                                v-model="searchQuery" 
                                type="text" 
                                placeholder="Search images and folders..."
                                class="search-input"
                                @input="filterItems"
                            />
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-controls">
                            <select v-model="sortBy" @change="sortItems" class="sort-select">
                                <option value="name">Sort by Name</option>
                                <option value="date">Sort by Date</option>
                                <option value="size">Sort by Size</option>
                                <option value="type">Sort by Type</option>
                            </select>
                            
                            <select v-model="filterType" @change="filterItems" class="filter-select">
                                <option value="">All Files</option>
                                <option value="jpg">Images</option>
                                <option value="png">PNG Files</option>
                                <option value="gif">GIF Files</option>
                                <option value="svg">SVG Files</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Breadcrumbs -->
                    <div class="breadcrumbs">
                        <span @click="setCurrentFolder(null)" class="breadcrumb-item">Root</span>
                        <span v-if="currentFolder" class="breadcrumb-separator"> / </span>
                        <span v-if="currentFolder" class="breadcrumb-item active">{{ currentFolder.name }}</span>
                    </div>

                    <!-- Bulk Actions Toolbar -->
                    <div v-if="selectedItems.length > 0" class="bulk-actions">
                        <span class="selected-count">{{ selectedItems.length }} item(s) selected</span>
                        <button @click="bulkDownload" class="btn btn-primary">
                            <i class="fas fa-download"></i> Download Selected
                        </button>
                        <button @click="bulkDelete" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        <button @click="clearSelection" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear Selection
                        </button>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div v-if="loading" class="loading-state">
                    <div class="spinner"></div>
                    <p>Loading media files...</p>
                </div>
                
                <!-- Error State -->
                <div v-else-if="error" class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>{{ error }}</p>
                    <button @click="fetchImages" class="btn btn-primary">Retry</button>
                </div>
                
                <!-- Content Area -->
                <div v-else class="content-area">
                    <!-- Folder Tree View -->
                    <div class="folder-section">
                        <h3 class="section-title">
                            <i class="fas fa-folder-open"></i>
                            Folders
                        </h3>
                        <div class="folder-tree">
                            <div class="all-folders-tree">
                                <div 
                                    v-for="folder in filteredFolders" 
                                    :key="folder.path" 
                                    class="folder-node"
                                    :style="{ marginLeft: getFolderIndent(folder) + 'px' }"
                                >
                                    <div
                                        class="folder-header"
                                        :class="{ 
                                            'selected': selectedItems.includes(folder.path), 
                                            'current': currentFolder && currentFolder.path === folder.path,
                                            'root-folder': isRootFolder(folder),
                                            'nested-folder': !isRootFolder(folder)
                                        }"
                                        @click="setCurrentFolder(folder)"
                                    >
                                        <div class="folder-info">
                                            <i :class="expandedFolders[folder.path] ? 'fas fa-folder-open' : 'fas fa-folder'"></i>
                                            <span class="folder-name">{{ folder.name }}</span>
                                            <span class="folder-count">({{ getItemCount(folder) }})</span>
                                        </div>
                                        <div class="folder-actions">
                                            <button 
                                                @click.stop="deleteItem(folder)"
                                                class="delete-btn"
                                                title="Delete folder"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <label class="checkbox-container">
                                                <input 
                                                    type="checkbox" 
                                                    :checked="selectedItems.includes(folder.path)"
                                                    @change="toggleSelection(folder.path)"
                                                    @click.stop
                                                >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Images Grid View -->
                    <div class="images-section">
                        <h3 class="section-title">
                            <i class="fas fa-images"></i>
                            Images
                        </h3>
                        <div class="images-grid">
                            <div 
                                v-for="image in filteredImages" 
                                :key="image.path"
                                class="image-card"
                                :class="{ 'selected': selectedItems.includes(image.path) }"
                                @click="openImage(image)"
                            >
                                <div class="image-preview-container">
                                    <img 
                                        :src="image.url" 
                                        :alt="image.name" 
                                        class="image-preview"
                                        @error="handleImageError"
                                    />
                                    <div class="image-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                <div class="image-info">
                                    <div class="checkbox-container">
                                        <input 
                                            type="checkbox" 
                                            :checked="selectedItems.includes(image.path)"
                                            @change="toggleSelection(image.path)"
                                            @click.stop
                                        >
                                        <span class="checkmark"></span>
                                    </div>
                                    <div class="image-details">
                                        <h4 class="image-name">{{ image.name }}</h4>
                                        <p class="image-meta">{{ formatFileSize(image.size) }} • {{ image.type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-if="!loading && filteredImages.length === 0 && filteredFolders.length === 0" class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h3>No files found</h3>
                        <p>{{ searchQuery ? 'Try adjusting your search or filters' : 'Upload some images to get started' }}</p>
                        <button v-if="!searchQuery" @click="showUploadModal = true" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Images
                        </button>
                    </div>
                </div>
            </div>

            <!-- Image Viewer Modal -->
            <ImageViewerModal
                :show-modal="!!selectedImage"
                :image="selectedImage"
                :images="filteredImages"
                @close="selectedImage = null"
                @previous="previousImage"
                @next="nextImage"
            />

        </div>
    </div>
</template>

<script>
    import ImageViewerModal from './Modals/ImageViewerModal.vue';

    export default {
        components: {
            ImageViewerModal
        },
        data() {
            return {
                // Core data
                folderStructure: [],
                loading: true,
                error: null,
                
                // UI state
                expandedFolders: {},
                selectedImage: null,
                selectedItems: [],
                showUploadModal: false,
                currentFolder: null,
                
                // Search and filter
                searchQuery: '',
                sortBy: 'name',
                filterType: '',
                
                // File handling
                currentImageIndex: 0
            }
        },
        
        computed: {
            // Filter and sort folders - get all folders with proper hierarchy
            filteredFolders() {
                let folders = this.getAllFolders();
                return this.filterAndSortItems(folders);
            },
            
            // Filter and sort images
            filteredImages() {
                let images = this.getImagesInFolder(this.currentFolder);
                return this.filterAndSortItems(images);
            }
        },
        
        mounted() {
            this.fetchImages();
            this.setupKeyboardNavigation();
        },
        
        methods: {
            // Core data fetching
            async fetchImages() {
                try {
                    this.loading = true;
                    this.error = null;
                    const response = await axios.get('/set_multimedia/get_images_folder_structure');
                    
                    // Ensure response is an array
                    this.folderStructure = Array.isArray(response.data) ? response.data : [];
                    
                    // Debug logging
                    console.log('API Response:', response.data);
                    console.log('Folder Structure:', this.folderStructure);
                    
                } catch (error) {
                    console.error('Error fetching images:', error);
                    this.error = 'Failed to load media files. Please try again.';
                } finally {
                    this.loading = false;
                }
            },
            
            // Folder operations
            toggleFolder(path) {
                this.expandedFolders[path] = !this.expandedFolders[path];
            },

            setCurrentFolder(folder) {
                this.currentFolder = folder;
                this.selectedItems = []; // Clear selection when changing folder
            },
            
            getAllFolders() {
                const folders = [];
                this.recursiveFolderSearch(this.folderStructure, folders);
                return folders;
            },
            
            recursiveFolderSearch(items, folders, depth = 0, parent = null) {
                items.forEach(item => {
                    if (item.type === 'directory') {
                        // Add hierarchy info to folder
                        const folderWithInfo = {
                            ...item,
                            depth: depth,
                            parent: parent
                        };
                        folders.push(folderWithInfo);
                        
                        if (item.children && Array.isArray(item.children)) {
                            this.recursiveFolderSearch(item.children, folders, depth + 1, item);
                        }
                    }
                });
            },
            
            // Get folder indentation level for visual hierarchy
            getFolderIndent(folder) {
                return (folder.depth || 0) * 20; // 20px per level
            },
            
            // Check if folder is at root level
            isRootFolder(folder) {
                return (folder.depth || 0) === 0;
            },
            
            getAllImages() {
                const images = [];
                this.recursiveImageSearch(this.folderStructure, images);
                return images;
            },
            
            recursiveImageSearch(items, images) {
                items.forEach(item => {
                    if (item.type === 'file') {
                        images.push(item);
                    } else if (item.type === 'directory' && item.children && Array.isArray(item.children)) {
                        this.recursiveImageSearch(item.children, images);
                    }
                });
            },
            
            getFolderContents(folder) {
                return folder.children || [];
            },
            
            getItemCount(item) {
                if (item.type === 'directory') {
                    const children = item.children || [];
                    const fileCount = children.filter(child => child.type === 'file').length;
                    const folderCount = children.filter(child => child.type === 'directory').length;
                    return fileCount + folderCount;
                }
                return 1;
            },

            getImagesInFolder(folder) {
                if (!folder) return this.getAllImages();
                const images = [];
                this.recursiveImageSearch(folder.children || [], images);
                return images;
            },
            
            // Selection management
            toggleSelection(path) {
                const index = this.selectedItems.indexOf(path);
                if (index > -1) {
                    this.selectedItems.splice(index, 1);
                } else {
                    this.selectedItems.push(path);
                }
            },
            
            clearSelection() {
                this.selectedItems = [];
            },
            
            // Search and filtering
            filterAndSortItems(items) {
                let filtered = items;
                
                // Apply search filter
                if (this.searchQuery) {
                    const query = this.searchQuery.toLowerCase();
                    filtered = filtered.filter(item => 
                        item.name.toLowerCase().includes(query)
                    );
                }
                
                // Apply type filter
                if (this.filterType) {
                    filtered = filtered.filter(item => {
                        if (item.type === 'file') {
                            return (item.extension || item.name.split('.').pop()).toLowerCase() === this.filterType.toLowerCase();
                        }
                        return true;
                    });
                }
                
                // Apply sorting
                return filtered.sort((a, b) => {
                    switch (this.sortBy) {
                        case 'name':
                            return a.name.localeCompare(b.name);
                        case 'date':
                            return new Date(b.modified || 0) - new Date(a.modified || 0);
                        case 'size':
                            return (b.size || 0) - (a.size || 0);
                        case 'type':
                            return a.type.localeCompare(b.type);
                        default:
                            return 0;
                    }
                });
            },
            
            filterItems() {
                // Computed properties will automatically update
            },
            
            sortItems() {
                // Computed properties will automatically update
            },
            
            // Image operations
            openImage(image) {
                this.selectedImage = image;
                this.currentImageIndex = this.filteredImages.findIndex(img => img.path === image.path);
            },
            
            nextImage() {
                if (this.currentImageIndex < this.filteredImages.length - 1) {
                    this.currentImageIndex++;
                    this.selectedImage = this.filteredImages[this.currentImageIndex];
                }
            },
            
            previousImage() {
                if (this.currentImageIndex > 0) {
                    this.currentImageIndex--;
                    this.selectedImage = this.filteredImages[this.currentImageIndex];
                }
            },
            
            handleImageError(event) {
                console.error('Image failed to load:', event.target.src);
                event.target.style.display = 'none';
                const overlay = event.target.parentElement.querySelector('.image-overlay');
                if (overlay) {
                    overlay.innerHTML = '<i class="fas fa-image"></i><p>Failed to load</p>';
                }
            },
            
            // Bulk operations
            async bulkDownload() {
                try {
                    const selectedImages = this.filteredImages.filter(img => 
                        this.selectedItems.includes(img.path)
                    );
                    
                    // For now, download each image individually
                    // In a real implementation, you might create a ZIP file
                    selectedImages.forEach(image => {
                        const link = document.createElement('a');
                        link.href = image.url;
                        link.download = image.name;
                        link.click();
                    });
                    
                    this.clearSelection();
                } catch (error) {
                    console.error('Error downloading files:', error);
                }
            },
            
            // Individual delete operations with confirmation
            async deleteItem(item) {
                const itemType = item.type === 'directory' ? 'folder' : 'file';
                const confirmMessage = `Are you sure you want to delete this ${itemType} "${item.name}"? ${item.type === 'directory' ? 'This will also delete all contents inside the folder.' : ''}`;
                
                if (!confirm(confirmMessage)) {
                    return;
                }
                
                try {
                    // Remove from selection if it was selected
                    const index = this.selectedItems.indexOf(item.path);
                    if (index > -1) {
                        this.selectedItems.splice(index, 1);
                    }
                    
                    // Remove from local data structure
                    this.removeItemFromStructure(item);
                    
                    // In a real implementation, this would call the API
                    // await axios.delete(`/api/set_multimedia/delete/${encodeURIComponent(item.path)}`);
                    
                    console.log(`Deleted ${itemType}:`, item.name);
                    
                    // For now, just show a success message
                    alert(`${itemType.charAt(0).toUpperCase() + itemType.slice(1)} "${item.name}" has been deleted.`);
                    
                } catch (error) {
                    console.error(`Error deleting ${itemType}:`, error);
                    alert(`Failed to delete ${itemType}. Please try again.`);
                }
            },
            
            removeItemFromStructure(item) {
                const removeFromArray = (items, targetPath) => {
                    const index = items.findIndex(arrayItem => arrayItem.path === targetPath);
                    if (index > -1) {
                        items.splice(index, 1);
                        return true;
                    }
                    
                    // Search recursively in subdirectories
                    for (let arrayItem of items) {
                        if (arrayItem.type === 'directory' && arrayItem.children) {
                            if (removeFromArray(arrayItem.children, targetPath)) {
                                return true;
                            }
                        }
                    }
                    return false;
                };
                
                removeFromArray(this.folderStructure, item.path);
            },
            
            async bulkDelete() {
                if (!confirm(`Are you sure you want to delete ${this.selectedItems.length} item(s)?`)) {
                    return;
                }
                
                try {
                    // This would require a new API endpoint for bulk deletion
                    // For now, just clear the selection
                    this.clearSelection();
                    alert('Bulk delete functionality would be implemented with backend API');
                } catch (error) {
                    console.error('Error deleting files:', error);
                }
            },
            
            // File upload
            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                this.uploadFiles(files);
            },
            
            handleDrop(event) {
                event.preventDefault();
                const files = Array.from(event.dataTransfer.files);
                this.uploadFiles(files);
            },
            
            async uploadFiles(files) {
                try {
                    const formData = new FormData();
                    files.forEach(file => {
                        formData.append('files[]', file);
                    });
                    
                    const response = await axios.post('/set_multimedia/upload_images', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    
                    // Refresh the file list
                    await this.fetchImages();
                    this.showUploadModal = false;
                    
                } catch (error) {
                    console.error('Error uploading files:', error);
                    alert('Failed to upload files. Please try again.');
                }
            },
            
            // Utility functions
            formatFileSize(bytes) {
                if (!bytes) return 'Unknown size';
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
            },
            
            setupKeyboardNavigation() {
                document.addEventListener('keydown', (event) => {
                    if (!this.selectedImage) return;
                    
                    switch (event.key) {
                        case 'Escape':
                            this.selectedImage = null;
                            break;
                        case 'ArrowRight':
                        case 'ArrowDown':
                            this.nextImage();
                            break;
                        case 'ArrowLeft':
                        case 'ArrowUp':
                            this.previousImage();
                            break;
                    }
                });
        }
    }
}
</script>

<style scoped>
    .media-manager-container {
        max-width: 1400px;
        margin: 20px auto;
        padding: 0;
        background-color: #f8f9fa;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .media-manager-container .main-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .media-manager-container .header-section {
        padding: 24px;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        background: #838383;
        color: white;
    }

    .media-manager-container .page-title {
        margin: 0 0 20px 0;
        font-size: 28px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .media-manager-container .page-title i {
        color: #ffd700;
    }

    .media-manager-container .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        margin-bottom: 16px;
    }

    .media-manager-container .search-bar {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .media-manager-container .search-input {
        width: 100%;
        padding: 10px 40px 10px 16px;
        border: 2px solid transparent;
        border-radius: 25px;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        transition: all 0.3s ease;
    }

    .media-manager-container .search-input:focus {
        outline: none;
        border-color: #ffd700;
        background: white;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }

    .media-manager-container .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 16px;
    }

    .media-manager-container .filter-controls {
        display: flex;
        gap: 12px;
    }

    .media-manager-container .sort-select, 
    .media-manager-container .filter-select {
        padding: 8px 12px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .media-manager-container .sort-select:focus, 
    .media-manager-container .filter-select:focus {
        outline: none;
        border-color: #ffd700;
        background: white;
    }

    .media-manager-container .breadcrumbs {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
        font-size: 14px;
        background: rgb(255 255 255 / 32%);
        padding: 8px 16px;
        border-radius: 8px;
    }

    .media-manager-container .breadcrumb-item {
        color: #ffd700;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .media-manager-container .breadcrumb-item:hover {
        background: rgba(255, 215, 0, 0.2);
    }

    .media-manager-container .breadcrumb-item.active {
        color: white;
        cursor: default;
        background: rgba(255, 255, 255, 0.2);
    }

    .media-manager-container .breadcrumb-separator {
        margin: 0 8px;
        color: rgba(255, 255, 255, 0.7);
    }

    .media-manager-container .bulk-actions {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .media-manager-container .selected-count {
        color: #ffd700;
        font-weight: 600;
    }

    .media-manager-container .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .media-manager-container .btn-primary {
        background: #ffd700;
        color: #333;
    }

    .media-manager-container .btn-primary:hover {
        background: #ffed4e;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 215, 0, 0.3);
    }

    .media-manager-container .btn-danger {
        background: #dc3545;
        color: white;
    }

    .media-manager-container .btn-danger:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .media-manager-container .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .media-manager-container .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .media-manager-container .content-area {
        display: flex;
        min-height: 600px;
    }

    .media-manager-container .folder-section {
        width: 300px;
        background: #f8f9fa;
        border-right: 1px solid #e9ecef;
        padding: 20px;
        display: block !important;
    }

    .media-manager-container .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .media-manager-container .folder-tree {
        max-height: 500px;
        overflow-y: auto;
    }

    .media-manager-container .folder-node {
        margin-bottom: 4px;
    }

    .media-manager-container .all-folders-tree {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .media-manager-container .all-folders-tree::-webkit-scrollbar {
        width: 6px;
    }

    .media-manager-container .all-folders-tree::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .media-manager-container .all-folders-tree::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .media-manager-container .all-folders-tree::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .media-manager-container .folder-node {
        margin-bottom: 2px;
    }

    /* Folder Tree Structure Styles */
    .media-manager-container .folder-header.root-folder {
        background: white;
        border: 2px solid #e9ecef;
        font-weight: 600;
    }

    .media-manager-container .folder-header.nested-folder {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        font-weight: 500;
        opacity: 0.95;
    }

    .media-manager-container .folder-header.nested-folder:hover {
        background: #e9ecef;
        opacity: 1;
    }

    /* Sub-folder and nested folder styles */
    .media-manager-container .sub-folder {
        margin-left: 20px;
    }

    .media-manager-container .sub-folder-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        margin-bottom: 1px;
    }

    .media-manager-container .sub-folder-header:hover {
        background: #e9ecef;
        border-color: #007bff;
    }

    .media-manager-container .sub-folder-content {
        margin-left: 15px;
        padding-left: 5px;
        border-left: 2px dotted #dee2e6;
    }

    .media-manager-container .nested-folder-item {
        margin-left: 15px;
    }

    .media-manager-container .nested-folder-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 5px 8px;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        background: #ffffff;
        margin-bottom: 1px;
        font-size: 12px;
    }

    .media-manager-container .nested-folder-header:hover {
        background: #f8f9fa;
        border-color: #007bff;
    }

    .media-manager-container .nested-folder-content {
        margin-left: 12px;
        padding-left: 4px;
        border-left: 1px dotted #dee2e6;
    }

    .media-manager-container .nested-content-item {
        margin-left: 10px;
    }

    /* Expand button styles */
    .media-manager-container .expand-btn {
        background: #6c757d;
        border: none;
        color: white;
        padding: 2px 6px;
        border-radius: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 10px;
        min-width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 4px;
    }

    .media-manager-container .expand-btn:hover {
        background: #5a6268;
        transform: scale(1.1);
    }

    .media-manager-container .expand-btn.expanded {
        background: #28a745;
    }

    .media-manager-container .expand-btn.expanded:hover {
        background: #218838;
    }

    .media-manager-container .expand-btn.small {
        padding: 1px 4px;
        font-size: 9px;
        min-width: 14px;
        height: 14px;
    }

    .media-manager-container .expand-btn.tiny {
        padding: 0 3px;
        font-size: 8px;
        min-width: 12px;
        height: 12px;
    }

    /* Content item styles */
    .media-manager-container .content-item {
        margin-bottom: 1px;
    }

    .media-manager-container .sub-content-item {
        margin-left: 10px;
        margin-bottom: 1px;
    }

    /* File styles within folders */
    .media-manager-container .image-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background: #ffffff;
        margin-bottom: 1px;
    }

    .media-manager-container .image-file:hover {
        background: #f8f9fa;
        border-color: #007bff;
    }

    .media-manager-container .image-file.selected {
        background: #e3f2fd;
        border-color: #2196f3;
    }

    .media-manager-container .file-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .media-manager-container .file-info i {
        color: #6c757d;
        font-size: 14px;
    }

    .media-manager-container .file-info span {
        font-size: 12px;
        color: #495057;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .media-manager-container .folder-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .media-manager-container .folder-header.root-folder:hover {
        background: #f8f9fa;
        border-color: #007bff;
    }

    .media-manager-container .folder-header.selected {
        background: #e3f2fd;
        border-color: #2196f3;
        box-shadow: 0 2px 4px rgba(33, 150, 243, 0.2);
    }

    .media-manager-container .folder-header.current {
        background: #fff3cd;
        border-color: #ffc107;
        border-left: 4px solid #ffc107;
        box-shadow: 0 2px 4px rgba(255, 193, 7, 0.2);
    }

    .media-manager-container .folder-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .media-manager-container .folder-info i {
        flex-shrink: 0;
    }

    .media-manager-container .folder-name {
        font-weight: 500;
        color: #495057;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    .media-manager-container .root-folder .folder-name {
        font-weight: 600;
        font-size: 14px;
    }

    .media-manager-container .nested-folder .folder-name {
        font-weight: 500;
        font-size: 13px;
        color: #6c757d;
    }

    .media-manager-container .folder-count {
        color: #6c757d;
        font-size: 11px;
        background: #e9ecef;
        padding: 2px 6px;
        border-radius: 10px;
        flex-shrink: 0;
    }

    .media-manager-container .nested-folder .folder-count {
        background: #f8f9fa;
        font-size: 10px;
    }

    .media-manager-container .folder-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .media-manager-container .folder-header:hover .folder-actions {
        opacity: 1;
    }

    .media-manager-container .file-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .media-manager-container .folder-header:hover .file-actions,
    .media-manager-container .image-file:hover .file-actions,
    .media-manager-container .sub-folder-header:hover .file-actions {
        opacity: 1;
    }

    .media-manager-container .delete-btn {
        background: #dc3545;
        border: none;
        color: white;
        padding: 6px 8px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 28px;
        font-size: 12px;
    }

    .media-manager-container .delete-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .media-manager-container .checkbox-container {
        display: inline-block;
        position: relative;
        cursor: pointer;
    }

    .media-manager-container .checkbox-container input {
        opacity: 0;
        position: absolute;
    }

    .media-manager-container .checkmark {
        width: 18px;
        height: 18px;
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 3px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .media-manager-container .checkbox-container input:checked ~ .checkmark {
        background: #28a745;
        border-color: #28a745;
    }

    .media-manager-container .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .media-manager-container .checkbox-container input:checked ~ .checkmark:after {
        display: block;
    }

    .media-manager-container .images-section {
        flex: 1;
        padding: 20px;
    }

    .media-manager-container .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
        max-height: 600px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .media-manager-container .images-grid::-webkit-scrollbar {
        width: 6px;
    }

    .media-manager-container .images-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .media-manager-container .images-grid::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .media-manager-container .images-grid::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .media-manager-container .image-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .media-manager-container .image-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .media-manager-container .image-card.selected {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
    }

    .media-manager-container .image-preview-container {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
    }

    .media-manager-container .image-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .media-manager-container .image-card:hover .image-preview {
        transform: scale(1.05);
    }

    .media-manager-container .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        font-size: 24px;
    }

    .media-manager-container .image-card:hover .image-overlay {
        opacity: 1;
    }

    .media-manager-container .image-info {
        padding: 12px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .media-manager-container .image-details {
        flex: 1;
    }

    .media-manager-container .image-name {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        margin: 0 0 4px 0;
        display: -webkit-box;
        /* -webkit-line-clamp: 2; */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .media-manager-container .image-meta {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    .media-manager-container .loading-state, 
    .media-manager-container .error-state, 
    .media-manager-container .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }

    .media-manager-container .loading-state i, 
    .media-manager-container .error-state i, 
    .media-manager-container .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #dee2e6;
    }

    .media-manager-container .loading-state p, 
    .media-manager-container .error-state p, 
    .media-manager-container .empty-state p {
        margin: 0 0 16px 0;
        font-size: 16px;
    }

    .media-manager-container .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 16px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .media-manager-container .error-state i {
        color: #dc3545;
    }

    .media-manager-container .empty-state i {
        color: #ffc107;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .media-manager-container .media-manager-container {
            padding: 10px;
        }

        .media-manager-container .header-section {
            padding: 16px;
        }

        .media-manager-container .page-title {
            font-size: 24px;
        }

        .media-manager-container .toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .media-manager-container .search-bar {
            min-width: auto;
        }

        .media-manager-container .filter-controls {
            justify-content: center;
        }

        .media-manager-container .content-area {
            flex-direction: column;
        }

        .media-manager-container .folder-section {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #e9ecef;
            display: block !important;
        }

        .media-manager-container .images-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
        }
    }

    @media (max-width: 480px) {
        .media-manager-container .images-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .media-manager-container .bulk-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .media-manager-container .btn {
            justify-content: center;
        }
    }
</style>
