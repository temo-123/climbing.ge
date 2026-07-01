// Scene/camera/renderer/lights/controls plumbing for the wall 3D viewer.
// Kept separate from WallViewer3D.vue so the Vue component only deals with
// lifecycle wiring, not raw Three.js setup boilerplate.

export function createViewerScene(THREE, OrbitControls, canvas, container) {
    const scene = new THREE.Scene();
    scene.background = null;

    const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 100);
    camera.position.set(3.5, 2.5, 6);

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

    const ambient = new THREE.AmbientLight(0xffffff, 0.6);
    const directional = new THREE.DirectionalLight(0xffffff, 1.1);
    directional.position.set(4, 6, 4);
    const fill = new THREE.DirectionalLight(0xffffff, 0.4);
    fill.position.set(-4, 2, -3);
    scene.add(ambient, directional, fill);

    const grid = new THREE.GridHelper(20, 20, 0xcccccc, 0xe5e5e5);
    scene.add(grid);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.08;
    controls.minDistance = 1.5;
    controls.maxDistance = 60;
    controls.maxPolarAngle = Math.PI * 0.49;
    controls.target.set(0, 1, 0);
    // Avoid fighting page scroll on touch devices: one-finger drag rotates,
    // two-finger handles pan/zoom (default touch gesture for pinch-zoom/scroll stays usable).
    controls.touches = {
        ONE: THREE.TOUCH.ROTATE,
        TWO: THREE.TOUCH.DOLLY_PAN,
    };
    controls.update();

    function resize() {
        const w = container.clientWidth || 1;
        const h = container.clientHeight || 1;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h, false);
    }

    function dispose() {
        controls.dispose();
        renderer.dispose();
        scene.remove(ambient, directional, fill, grid);
        grid.geometry.dispose();
        grid.material.dispose();
    }

    return { scene, camera, renderer, controls, resize, dispose };
}
