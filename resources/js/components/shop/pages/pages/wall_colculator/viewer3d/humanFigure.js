// A simple low-poly human figure, ~1.7m tall, purely for visual scale reference next
// to the wall — not part of the priced model. Built in world units (1 unit = 1 meter,
// same convention as the rest of the scene), feet at local y=0.

const SKIN = 0xe0ac8b;
const CLOTHES = 0x3d4f5c;
const PANTS = 0x2b2b33;

export function buildHumanFigure(THREE) {
    const group = new THREE.Group();
    const skinMat = new THREE.MeshStandardMaterial({ color: SKIN, roughness: 0.9 });
    const clothesMat = new THREE.MeshStandardMaterial({ color: CLOTHES, roughness: 0.9 });
    const pantsMat = new THREE.MeshStandardMaterial({ color: PANTS, roughness: 0.9 });

    const legHeight = 0.85;
    const torsoHeight = 0.55;
    const headRadius = 0.11;

    [-0.09, 0.09].forEach((x) => {
        const leg = new THREE.Mesh(new THREE.CapsuleGeometry(0.07, legHeight - 0.14, 4, 8), pantsMat);
        leg.position.set(x, legHeight / 2, 0);
        group.add(leg);
    });

    const torso = new THREE.Mesh(new THREE.CapsuleGeometry(0.16, torsoHeight - 0.32, 4, 8), clothesMat);
    torso.position.set(0, legHeight + torsoHeight / 2, 0);
    group.add(torso);

    [-0.22, 0.22].forEach((x) => {
        const arm = new THREE.Mesh(new THREE.CapsuleGeometry(0.045, torsoHeight - 0.18, 4, 8), clothesMat);
        arm.position.set(x, legHeight + torsoHeight / 2 + 0.02, 0);
        group.add(arm);
    });

    const head = new THREE.Mesh(new THREE.SphereGeometry(headRadius, 12, 12), skinMat);
    head.position.set(0, legHeight + torsoHeight + headRadius + 0.04, 0);
    group.add(head);

    group.traverse((obj) => { obj.castShadow = false; obj.receiveShadow = false; });
    return group;
}
