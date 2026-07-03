export default {
    methods: {
        async compressIfNeeded(file) {
            const maxBytes = this.max_size_mb * 1024 * 1024;
            if (file.size <= maxBytes) return file;

            this.isCompressing = true;
            try {
                return await this.compressImage(file, maxBytes);
            } finally {
                this.isCompressing = false;
            }
        },

        compressImage(file, maxBytes) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let w = img.width, h = img.height;

                        const MAX_DIM = 2560;
                        if (w > MAX_DIM || h > MAX_DIM) {
                            const r = Math.min(MAX_DIM / w, MAX_DIM / h);
                            w = Math.round(w * r);
                            h = Math.round(h * r);
                        }

                        canvas.width = w;
                        canvas.height = h;
                        canvas.getContext('2d').drawImage(img, 0, 0, w, h);

                        const tryQuality = (q) => {
                            canvas.toBlob((blob) => {
                                if (blob.size <= maxBytes || q <= 0.3) {
                                    const name = file.name.replace(/\.[^.]+$/, '.jpg');
                                    resolve(new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() }));
                                } else {
                                    tryQuality(Math.round((q - 0.1) * 10) / 10);
                                }
                            }, 'image/jpeg', q);
                        };
                        tryQuality(0.85);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        },

        validateFileType(file) {
            const allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowed.includes(file.type)) {
                this.$bus.$emit('toast', { type: 'warning', title: 'Invalid file', message: `${file.name}: only JPEG, PNG, GIF, WebP allowed.`, duration: 4000 });
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
