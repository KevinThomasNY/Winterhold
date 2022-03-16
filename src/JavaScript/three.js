/*
This file is for THREE.js
THREE.js is used for importing the 3D animated model
found in the homepage.
Annimation include two spinning computer fans
*/

//Importing THREE.js from cdn
import * as THREE from "https://cdn.jsdelivr.net/npm/three@0.118/build/three.module.js";
//GLTFLoader is to load the .gltf file
import { GLTFLoader } from "https://cdn.jsdelivr.net/npm/three@0.118.1/examples/jsm/loaders/GLTFLoader.js";
/*
OrbitControls allow for movement
Currently disabled 
*/
import { OrbitControls } from "https://cdn.jsdelivr.net/npm/three@0.118/examples/jsm/controls/OrbitControls.js";

// Star of THREE.js
/**
 * Loaders
 */
const gltfLoader = new GLTFLoader();

/**
 * Base
 */

// Canvas
const canvas = document.querySelector("canvas.webgl");

// Scene
const scene = new THREE.Scene();

/**
 * Update all materials shadows
 */
const updateAllMaterials = () => {
  scene.traverse((child) => {
    if (
      child instanceof THREE.Mesh &&
      child.material instanceof THREE.MeshStandardMaterial
    ) {
      child.material.needsUpdate = true;
      child.castShadow = true;
      child.receiveShadow = true;
    }
  });
};

scene.background = new THREE.Color(0x333645);

/**
 * 3D Database Model
 */
let mixer = null;
gltfLoader.load("./resources/models/Winterhold3DModel.glb", (gltf) => {
  gltf.scene.scale.set(10, 10, 10);
  gltf.scene.position.set(0, 0, 0);
  gltf.scene.rotateY(2.6);
  scene.add(gltf.scene);

  // Adding Animation
  mixer = new THREE.AnimationMixer(gltf.scene);
  const clips = gltf.animations;
  clips.forEach(function (clip) {
    mixer.clipAction(clip).play();
  });

  updateAllMaterials();
});

/**
 * Lighting to the scene
 */
const directionalLight = new THREE.DirectionalLight("#ffffff", 3);
directionalLight.castShadow = true;
directionalLight.shadow.camera.far = 15;
directionalLight.shadow.mapSize.set(1024, 1024);
directionalLight.shadow.normalBias = 0.05;
directionalLight.position.set(-2.2, 3, -4.464);
scene.add(directionalLight);

/**
 * Window Size
 */
const sizes = {
  width: window.innerWidth,
  height: window.innerHeight,
};
let screenWidth = window.innerWidth;
window.addEventListener("resize", () => {
  screenWidth = window.innerWidth;
  if (screenWidth < 850) {
    camera.position.set(-5, 5, -15);
  } else {
    camera.position.set(-4, 6, -14);
  }
  // Update sizes
  sizes.width = window.innerWidth;
  sizes.height = window.innerHeight;

  // Update camera
  camera.aspect = sizes.width / sizes.height;
  camera.updateProjectionMatrix();

  // Update renderer
  renderer.setSize(sizes.width, sizes.height);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
});

/**
 * Camera
 */
// Base camera
const camera = new THREE.PerspectiveCamera(
  75,
  sizes.width / sizes.height,
  0.1,
  100
);
//When resizing the window
if (screenWidth < 850) {
  camera.position.set(-5, 5, -15);
} else {
  camera.position.set(-4, 5, -14);
}
scene.add(camera);

// Controls
const controls = new OrbitControls(camera, canvas);
controls.enableDamping = true;
controls.enabled = false;

/**
 * Renderer
 */
const renderer = new THREE.WebGLRenderer({
  canvas: canvas,
  antialias: true,
});
//html5 div id (model)
const modelDiv = document.getElementById("model");
modelDiv.appendChild(renderer.domElement);
renderer.physicallyCorrectLights = true;
renderer.outputEncoding = THREE.sRGBEncoding;
renderer.toneMapping = THREE.ReinhardToneMapping;
renderer.toneMappingExposure = 4;
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
renderer.setSize(sizes.width, sizes.height);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

/**
 * Animate
 */
const clock = new THREE.Clock();
let previousTime = 0;
const tick = () => {
  const elapsedTime = clock.getElapsedTime();
  const deltaTime = elapsedTime - previousTime;
  previousTime = elapsedTime;
  // Update controls
  controls.update();

  // Fox animation
  if (mixer) {
    mixer.update(deltaTime);
  }

  // Render
  renderer.render(scene, camera);

  // Call tick again on the next frame
  window.requestAnimationFrame(tick);
};

tick();
//End of THREE.js
