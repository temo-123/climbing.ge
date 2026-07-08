<template>
    <div class="barcode-image-wrapper">
        <div class="barcode-label">{{ $t('admin.shop.ean13_barcode_label') }}</div>
        <div class="barcode-canvas-box">
            <canvas ref="barcodeCanvas" class="barcode-canvas"></canvas>
        </div>
        <button type="button" class="barcode-download-btn" @click="download">
            <i class="fa fa-download"></i> {{ $t('admin.shop.download_png') }}
        </button>
    </div>
</template>

<script>
const L = ['0001101','0011001','0010011','0111101','0100011','0110001','0101111','0111011','0110111','0001011']
const G = ['0100111','0110011','0011011','0100001','0011101','0111001','0000101','0010001','0001001','0010111']
const R = ['1110010','1100110','1101100','1000010','1011100','1001110','1010000','1000100','1001000','1110100']
const FIRST_DIGIT_PATTERN = ['LLLLLL','LLGLGG','LLGGLG','LLGGGL','LGLLGG','LGGLLG','LGGGLL','LGLGLG','LGLGGL','LGGLGL']

function encodeEAN13(code) {
    const d = code.split('').map(Number)
    const pat = FIRST_DIGIT_PATTERN[d[0]]
    let bits = '101'
    for (let i = 0; i < 6; i++) bits += (pat[i] === 'L' ? L : G)[d[i + 1]]
    bits += '01010'
    for (let i = 6; i < 12; i++) bits += R[d[i + 1]]
    bits += '101'
    return bits
}

export default {
    name: 'BarcodeImageComponent',
    props: {
        code: { type: String, required: true }
    },
    mounted() { this.$nextTick(this.render) },
    watch: { code() { this.$nextTick(this.render) } },
    methods: {
        render() {
            const canvas = this.$refs.barcodeCanvas
            if (!canvas || !this.code) return
            const ctx = canvas.getContext('2d')
            const barW = 3
            const padX = 20
            const padY = 10
            const barH = 80
            const fontSize = 14
            const textH = fontSize + 6

            if (/^\d{13}$/.test(this.code)) {
                const bits = encodeEAN13(this.code)
                canvas.width = bits.length * barW + padX * 2
                canvas.height = padY + barH + textH + padY

                ctx.fillStyle = '#fff'
                ctx.fillRect(0, 0, canvas.width, canvas.height)
                ctx.fillStyle = '#111'

                for (let i = 0; i < bits.length; i++) {
                    if (bits[i] === '1') {
                        ctx.fillRect(padX + i * barW, padY, barW, barH)
                    }
                }

                ctx.font = `bold ${fontSize}px monospace`
                ctx.fillStyle = '#111'
                ctx.textBaseline = 'top'
                const textY = padY + barH + 4
                ctx.textAlign = 'center'
                ctx.fillText(this.code[0], padX / 2, textY)
                const leftCenter = padX + (3 + 21) * barW
                ctx.fillText(this.code.slice(1, 7), leftCenter, textY)
                const rightCenter = padX + (3 + 42 + 5 + 21) * barW
                ctx.fillText(this.code.slice(7, 13), rightCenter, textY)
            } else {
                const chars = this.code.slice(0, 40)
                canvas.width = chars.length * 10 * barW + padX * 2
                canvas.height = padY + barH + textH + padY

                ctx.fillStyle = '#fff'
                ctx.fillRect(0, 0, canvas.width, canvas.height)
                ctx.fillStyle = '#111'

                let x = padX
                for (let i = 0; i < chars.length; i++) {
                    const bits7 = chars.charCodeAt(i).toString(2).padStart(7, '0')
                    for (let b = 0; b < 7; b++) {
                        if (bits7[b] === '1') ctx.fillRect(x, padY, barW, barH)
                        x += barW
                    }
                    x += barW * 3
                }

                ctx.font = `bold ${fontSize}px monospace`
                ctx.fillStyle = '#111'
                ctx.textBaseline = 'top'
                ctx.textAlign = 'center'
                ctx.fillText(this.code, canvas.width / 2, padY + barH + 4)
            }
        },
        download() {
            const canvas = this.$refs.barcodeCanvas
            if (!canvas) return
            const link = document.createElement('a')
            link.download = `barcode-${this.code}.png`
            link.href = canvas.toDataURL('image/png')
            link.click()
        }
    }
}
</script>

<style scoped>
.barcode-image-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 14px 16px 12px;
    background: #f8faff;
    border: 1px solid #d0daf5;
    border-radius: 10px;
}
.barcode-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #7a8aaa;
}
.barcode-canvas-box {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    padding: 4px 8px;
    line-height: 0;
}
.barcode-canvas {
    display: block;
    max-width: 100%;
}
.barcode-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 500;
    color: #4a6cf7;
    background: #eef1ff;
    border: 1px solid #c5cffa;
    border-radius: 6px;
    padding: 4px 12px;
    cursor: pointer;
    transition: background 0.15s;
}
.barcode-download-btn:hover {
    background: #dce2fd;
}
</style>
