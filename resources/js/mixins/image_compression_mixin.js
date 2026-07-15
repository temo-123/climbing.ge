import { compressImage } from '../services/image/compressImage.js'

export default {
    methods: {
        async compressIfNeeded(file) {
            this.isCompressing = true;
            try {
                return await compressImage(file, { maxSizeMB: this.max_size_mb ?? 1.5 });
            } finally {
                this.isCompressing = false;
            }
        },

        validateFileType(file) {
            const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowed.includes(file.type)) {
                this.$bus.$emit('toast', { type: 'warning', title: this.$t('admin.gallery_manager.invalid_file_title'), message: this.$t('admin.gallery_manager.invalid_file_message', { name: file.name }), duration: 4000 });
                return false;
            }
            return true;
        },

        formatFileSize(bytes) {
            if (!bytes || bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },
    }
};
