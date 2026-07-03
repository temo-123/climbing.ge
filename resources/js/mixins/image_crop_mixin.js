export default {
    props: {
        crop_ratio_prop: { type: Object, default: null },
    },
    data() {
        return {
            crop_modal_visible: false,
            crop_modal_file: null,
            _cropResolve: null,
        }
    },
    methods: {
        maybeCropImage(file) {
            if (!this.crop_ratio_prop) return Promise.resolve(file);

            return new Promise((resolve) => {
                const url = URL.createObjectURL(file);
                const img = new Image();
                img.onload = () => {
                    URL.revokeObjectURL(url);

                    const targetRatio = this.crop_ratio_prop.width / this.crop_ratio_prop.height;
                    const actualRatio = img.naturalWidth / img.naturalHeight;

                    if (Math.abs(actualRatio - targetRatio) / targetRatio < 0.02) {
                        resolve(file);
                        return;
                    }

                    this.crop_modal_file = file;
                    this.crop_modal_visible = true;
                    this._cropResolve = resolve;
                };
                img.src = url;
            });
        },

        onCropConfirm(croppedFile) {
            this.crop_modal_visible = false;
            if (this._cropResolve) this._cropResolve(croppedFile);
            this._cropResolve = null;
        },

        onCropCancel() {
            this.crop_modal_visible = false;
            if (this._cropResolve) this._cropResolve(null);
            this._cropResolve = null;
        },
    }
};
