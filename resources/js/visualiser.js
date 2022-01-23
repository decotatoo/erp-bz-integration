/*
 * X: width
 * Y: length
 * Z: depth.
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import * as THREE from 'three';
import { TrackballControls } from "three/examples/jsm/controls/TrackballControls.js";
import { TextGeometry } from 'three/examples/jsm/geometries/TextGeometry.js';
import { FontLoader } from 'three/examples/jsm/loaders/FontLoader.js';
const stc = require('string-to-color');

document.addEventListener("DOMContentLoaded", async () => {

    const results = PACKING_SIMULATION;

    console.log(results);

    const mainEl = document.getElementById('simulation_canvas');

    const infoPanelEl = document.createElement('div');
    infoPanelEl.id = 'visualization-info-panel';

    const unfitItemsEl = document.createElement('div');
    unfitItemsEl.id = 'unfit-items';
    unfitItemsEl.className = 'col-md-4';

    let elWidth = mainEl.clientWidth;
    let elHeight = window.innerHeight;

    const simulationWrapperEl = document.createElement('div');
    simulationWrapperEl.id = 'visualization-wrapper';
    simulationWrapperEl.style.maxWidth = '100%';

    let perspectiveCamera, orthographicCamera, controls, scene, renderer;

    const params = {
        orthographicCamera: false
    };

    const frustumSize = 400;

    const aspect = elWidth / elHeight;

    perspectiveCamera = new THREE.PerspectiveCamera(60, aspect, 1, 8000);
    perspectiveCamera.position.z = 500;

    orthographicCamera = new THREE.OrthographicCamera(frustumSize * aspect / - 2, frustumSize * aspect / 2, frustumSize / 2, frustumSize / - 2, 1, 1000);
    orthographicCamera.position.z = 500;

    // world

    scene = new THREE.Scene();
    scene.background = new THREE.Color(0xcccccc);
    // scene.fog = new THREE.FogExp2(0xcccccc, 0.001);

    // lights
    const light = new THREE.AmbientLight(0xffffff);
    scene.add(light);

    // axis helper
    const axesHelper = new THREE.AxesHelper(5000);
    scene.add(axesHelper);

    // raycaster
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let INTERSECTED;

    // font loader
    let font;
    const fontName = 'helvetiker';
    const fontWeight = 'regular';
    let isFontLoaded = false;

    const fontLoader = new FontLoader();
    fontLoader.load('https://threejs.org/examples/fonts/' + fontName + '_' + fontWeight + '.typeface.json', function (response) {
        font = response;
        isFontLoaded = true;
    });

    // add simulation results to scene
    let master_row_x = 0;

    if (results.packed) {
        await new Promise(resolve => {
            let waitingForRender = setInterval(() => {
                if (isFontLoaded) {
                    simulationResultVisualization();

                    clearInterval(waitingForRender);
                    resolve();
                }
            }, 100);
        });
    }

    // renderer
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(elWidth, elHeight);


    simulationWrapperEl.appendChild(renderer.domElement);
    simulationWrapperEl.appendChild(infoPanelEl);
    simulationWrapperEl.appendChild(unfitItemsEl);
    mainEl.appendChild(simulationWrapperEl);

    window.addEventListener('resize', onWindowResize);
    window.addEventListener('mousemove', onMouseMove, false);

    createControls(perspectiveCamera);

    animate();

    function simulationResultVisualization() {
        results.packed.forEach((packed, index) => {
            const pos = createMasterObject(packed);
            const items = packed.items;

            items.forEach(unit => {
                createUnitObject(unit, pos);
            });

            master_row_x += (packed.box.outer_width + 100);
        });

        let unfit_items = [];

        results.unpacked.forEach((x) => {
            if (
                unfit_items.some(
                    (val) => val.id == x.description
                )
            ) {
                unfit_items.forEach((k) => {
                    if (k.id === x.description) {
                        k.qty++;
                    }
                });
            } else {
                unfit_items.push({
                    id: x.description,
                    qty: 1
                });
            }
        });

        showUnfitItems(unfit_items);
    }

    function createMasterObject(item) {
        const master = item.box;

        const outer_trans_x = master.outer_width / 2;
        const outer_trans_y = master.outer_length / 2;
        const outer_trans_z = master.outer_depth / 2;

        const inner_trans_x = master_row_x + ((master.outer_width - master.inner_width) / 2);
        const inner_trans_y = (master.outer_length - master.inner_length) / 2;
        const inner_trans_z = (master.outer_depth - master.inner_depth) / 2;

        // create outer box
        const outer_geometry = new THREE.BoxGeometry(master.outer_width, master.outer_length, master.outer_depth);
        outer_geometry.translate(outer_trans_x, outer_trans_y, outer_trans_z);
        outer_geometry.translate(master_row_x, 0, 0);
        const outer_edge = new THREE.EdgesGeometry(outer_geometry);
        const outer_line = new THREE.LineSegments(outer_edge, new THREE.LineBasicMaterial({ color: 0xffffff }));
        outer_line.updateMatrix();
        outer_line.matrixAutoUpdate = true;

        // create inner box
        const inner_geometry = new THREE.BoxGeometry(master.inner_width, master.inner_length, master.inner_depth);
        inner_geometry.translate(master.inner_width / 2, master.inner_length / 2, master.inner_depth / 2);
        inner_geometry.translate(inner_trans_x, inner_trans_y, inner_trans_z);
        const inner_edge = new THREE.EdgesGeometry(inner_geometry);
        const inner_line = new THREE.LineSegments(inner_edge, new THREE.LineBasicMaterial({ color: 0x000000 }));
        inner_line.updateMatrix();
        inner_line.matrixAutoUpdate = true;

        // create masterbox signage
        const text_geo = new TextGeometry(master.reference, {
            font: font,
            size: 30,
            height: 2,
            curveSegments: 4,
            bevelThickness: 2,
            bevelSize: 1,
            bevelEnabled: true
        });

        text_geo.computeBoundingBox();
        text_geo.translate(
            master_row_x - (text_geo.boundingBox.max.x / 2) + outer_trans_x,
            0,
            master.outer_depth + 100
        );

        const text_material = new THREE.MeshPhongMaterial({ color: 0x1C78C0, specular: 0xffffff });
        const text_mesh = new THREE.Mesh(text_geo, text_material);

        text_mesh.castShadow = true;
        text_mesh.receiveShadow = true;

        text_mesh.tooltip = true;
        text_mesh.tooltipType = 'master-signage';

        let master_contents = [];

        item.items.forEach((x) => {
            if (
                master_contents.some(
                    (val) => val.id == x.id
                )
            ) {
                master_contents.forEach((k) => {
                    if (k.id === x.id) {
                        k.qty++;
                    }
                });
            } else {
                master_contents.push({
                    id: x.id,
                    qty: 1
                });
            }
        });

        text_mesh.metadata = master_contents;

        scene.add(outer_line);
        scene.add(inner_line);
        scene.add(text_mesh);

        return {
            outer: {
                x: outer_trans_x,
                y: outer_trans_y,
                z: outer_trans_z
            },
            inner: {
                x: inner_trans_x,
                y: inner_trans_y,
                z: inner_trans_z
            },
        }
    }

    function createUnitObject(item, pos) {
        const geometry = new THREE.BoxGeometry(item.width, item.length, item.depth);

        geometry.translate(item.width / 2, item.length / 2, item.depth / 2);
        geometry.translate(
            pos.inner.x + item.x,
            pos.inner.y + item.y,
            pos.inner.z + item.z
        );

        const material = new THREE.MeshStandardMaterial({
            color: stc(item.id),
            roughness: 0.1,
            metalness: 0.1,
            transparent: true,
            opacity: 0.3
        });

        const mesh = new THREE.Mesh(geometry, material);
        mesh.tooltip = true;
        mesh.tooltipType = 'unit';
        mesh.metadata = item;

        const edge = new THREE.EdgesGeometry(geometry);
        const line = new THREE.LineSegments(edge, new THREE.LineBasicMaterial({ color: 0xffffff }));
        line.updateMatrix();
        line.matrixAutoUpdate = true;

        scene.add(mesh);
        scene.add(line);
    }

    function createControls(camera) {
        controls = new TrackballControls(camera, renderer.domElement);

        controls.rotateSpeed = 1.0;
        controls.zoomSpeed = 0.5;
        controls.panSpeed = 0.8;

        controls.keys = ['KeyA', 'KeyS', 'KeyD'];
    }

    function onMouseMove(event) {
        // calculate mouse position in normalized device coordinates (-1 to +1) for both components
        mouse.x = ((((event.pageX - simulationWrapperEl.offsetLeft) / renderer.domElement.clientWidth) * 2) - 1);
        mouse.y = ((((event.pageY - simulationWrapperEl.offsetTop) / renderer.domElement.clientHeight) * -2) + 1);
    }

    function onWindowResize() {
        const aspect = elWidth / elHeight;

        perspectiveCamera.aspect = aspect;
        perspectiveCamera.updateProjectionMatrix();

        orthographicCamera.left = - frustumSize * aspect / 2;
        orthographicCamera.right = frustumSize * aspect / 2;
        orthographicCamera.top = frustumSize / 2;
        orthographicCamera.bottom = - frustumSize / 2;
        orthographicCamera.updateProjectionMatrix();

        renderer.setSize(elWidth, elHeight);

        controls.handleResize();
    }

    function animate() {
        requestAnimationFrame(animate);

        controls.update();

        render();
    }

    function render() {
        const camera = (params.orthographicCamera) ? orthographicCamera : perspectiveCamera;

        // update the picking ray with the camera and mouse position
        raycaster.setFromCamera(mouse, camera);

        // calculate objects intersecting the picking ray
        const intersects = raycaster.intersectObjects(scene.children);

        if (intersects.length > 0) {
            if (intersects[0].object != INTERSECTED) {
                // show information, if it has a "tooltip" property.
                if (intersects[0].object.tooltip) {
                    if (INTERSECTED) {
                        INTERSECTED.material.color.setHex(INTERSECTED.currentHex);
                    }
                    // store reference to closest object as current intersection object
                    INTERSECTED = intersects[0].object;
                    // store color of closest object (for later restoration)
                    INTERSECTED.currentHex = INTERSECTED.material.color.getHex();
                    // set a new color for closest object
                    INTERSECTED.material.color.setHex(0xff0000);

                    infoPanelEl.style.display = 'block';
                    updateInfoPanelContent(INTERSECTED);
                }
            }
        } else {
            // restore previous intersection object (if it exists) to its original color
            if (INTERSECTED) {
                INTERSECTED.material.color.setHex(INTERSECTED.currentHex);
            }
            // remove previous intersection object reference by setting current intersection object to "nothing"
            INTERSECTED = null;

            infoPanelEl.style.display = 'none';
            infoPanelEl.innerHTML = '';
        }

        renderer.render(scene, camera);
    }

    function updateInfoPanelContent(obj) {
        if (obj.tooltipType === 'unit') {
            infoPanelEl.innerHTML = `
            <div class="info-panel-container">
                <div class="info-panel-item">
                    <div class="info-panel-item-title">
                        <span>Ref</span>
                    </div>
                    <div class="info-panel-item-content">
                        <span>${obj.metadata.id}</span>
                    </div>
                </div>
                <div class="info-panel-item">
                    <div class="info-panel-item-title">
                        <span>As specified (mm) (W × L × D)</span>
                    </div>
                    <div class="info-panel-item-content">
                        <span>${obj.metadata.item.width} × ${obj.metadata.item.length} × ${obj.metadata.item.depth} </span>
                    </div>
                </div>
                <div class="info-panel-item">
                    <div class="info-panel-item-title">
                        <span>As packed (mm) (W × L × D)</span>
                    </div>
                    <div class="info-panel-item-content">
                        <span>${obj.metadata.width} × ${obj.metadata.length} × ${obj.metadata.depth} </span>
                    </div>
                </div>
                <div class="info-panel-item">
                    <div class="info-panel-item-title">
                        <span>x, y, z</span>
                    </div>
                    <div class="info-panel-item-content">
                        <span>${obj.metadata.x}, ${obj.metadata.y}, ${obj.metadata.z}</span>
                    </div>
                </div>
            </div>
        `;
        } else if (obj.tooltipType === 'master-signage') {

            let itemListContent = ``;

            obj.metadata.forEach(element => {
                itemListContent += `
                <div class="info-panel-item">
                    <div class="info-panel-item-title">
                        <span>${element.id}</span>
                    </div>
                    <div class="info-panel-item-content">
                        <span>${element.qty}</span>
                    </div>
                </div>
            `;
            });

            infoPanelEl.innerHTML = `
            <div class="info-panel-container">
                ${itemListContent}
            </div>
        `;
        }

    }


    function showUnfitItems(item) {

        let unfitItemsContent = ``;

        item.forEach(element => {
            unfitItemsContent += `
            <div class="info-panel-item">
                <div class="">
                    <span>${element.id}</span>
                </div>
                <div class="">
                    <span>${element.qty}</span>
                </div>
            </div>
        `;
        });

        unfitItemsEl.innerHTML = `
        <div class="form-group " style="padding-bottom:20px;">
            <legend class="col-form-label" style="font-weight:700;">Units that doesn't fit in any Master Boxes</legend>
            ${unfitItemsContent != '' ? unfitItemsContent : '<span>None</span>'}
        </div>
    `;

    }
});