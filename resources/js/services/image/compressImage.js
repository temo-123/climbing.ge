import imageCompression from 'browser-image-compression'

// Animated GIFs would be flattened to a single frame by the canvas-based
// compressor, and SVGs are vectors with no pixel data to shrink — both are
// passed through untouched.
const SKIP_MIME_TYPES = ['image/gif', 'image/svg+xml']

export async function compressImage(file, { maxSizeMB = 1.5, maxWidthOrHeight = 1920, skipIfUnderMB = 2 } = {}) {
  if (!file || !file.type?.startsWith('image/') || SKIP_MIME_TYPES.includes(file.type)) {
    return file
  }

  // Don't bother re-encoding images that are already small — it only costs
  // quality for no real size benefit.
  if (file.size <= skipIfUnderMB * 1024 * 1024) {
    return file
  }

  const targetBytes = maxSizeMB * 1024 * 1024
  // browser-image-compression's own iteration can still land above maxSizeMB
  // for high-detail source images (it's a best-effort target, not a
  // guarantee), and nothing downstream re-checks the result. Escalate with
  // smaller dimensions/quality and verify the actual output size ourselves
  // instead of trusting a single pass — the upload target has a hard cap, so
  // "close to the target" isn't good enough.
  const attempts = [
    { maxWidthOrHeight, initialQuality: 0.85 },
    { maxWidthOrHeight: Math.round(maxWidthOrHeight * 0.75), initialQuality: 0.7 },
    { maxWidthOrHeight: Math.round(maxWidthOrHeight * 0.5), initialQuality: 0.6 },
  ]

  let result = null
  for (const attempt of attempts) {
    try {
      result = await imageCompression(file, {
        maxSizeMB,
        useWebWorker: true,
        ...attempt,
      })
    } catch (err) {
      console.error('Image compression pass failed, trying a more aggressive pass:', err)
      continue
    }
    if (result.size <= targetBytes) return result
  }

  // Exhausted every attempt without reliably hitting the target — return the
  // smallest result we managed to produce (last, most aggressive attempt)
  // rather than the original oversized file.
  return result || file
}
