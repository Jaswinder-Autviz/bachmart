/**
 * BachatMart 3D Spatial Interactive Engine
 * 1. Three.js Interactive Floating 3D Scene in Hero Stage
 * 2. High-Performance 60fps Card Tilt & Dynamic Specular Sheen
 */

(function () {
    'use strict';

    /* ─── 1. THREE.JS 3D HERO CANVAS ─── */
    function initHeroThreeScene() {
        const canvasContainer = document.getElementById('heroThreeCanvas');
        if (!canvasContainer || typeof THREE === 'undefined') return;

        // Container dimensions
        const width = canvasContainer.clientWidth || 440;
        const height = canvasContainer.clientHeight || 420;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.z = 24;

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'high-performance' });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        canvasContainer.innerHTML = '';
        canvasContainer.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.9);
        scene.add(ambientLight);

        const pointLight1 = new THREE.PointLight(0xFF5722, 2.5, 60);
        pointLight1.position.set(12, 14, 12);
        scene.add(pointLight1);

        const pointLight2 = new THREE.PointLight(0xF59E0B, 1.8, 50);
        pointLight2.position.set(-12, -10, 8);
        scene.add(pointLight2);

        const rimLight = new THREE.PointLight(0x38BDF8, 1.4, 40);
        rimLight.position.set(0, 14, -10);
        scene.add(rimLight);

        // Group for 3D Objects
        const objectsGroup = new THREE.Group();
        scene.add(objectsGroup);

        // 1. 3D Floating Clearance Coin (Gold / Orange Cylinder)
        const coinGeometry = new THREE.CylinderGeometry(2.8, 2.8, 0.5, 36);
        const coinMaterial = new THREE.MeshStandardMaterial({
            color: 0xF59E0B,
            metalness: 0.85,
            roughness: 0.22,
            emissive: 0x78350F,
            emissiveIntensity: 0.2
        });
        const coinMesh = new THREE.Mesh(coinGeometry, coinMaterial);
        coinMesh.position.set(4.5, 3.8, 2);
        coinMesh.rotation.x = Math.PI / 3;
        coinMesh.rotation.y = Math.PI / 6;
        objectsGroup.add(coinMesh);

        // 2. 3D Clearance Parcel Cube (Brand Orange with Ribbon)
        const boxGeometry = new THREE.BoxGeometry(3.6, 3.6, 3.6);
        const boxMaterial = new THREE.MeshStandardMaterial({
            color: 0xFF5722,
            metalness: 0.3,
            roughness: 0.35,
            emissive: 0x9A3412,
            emissiveIntensity: 0.15
        });
        const boxMesh = new THREE.Mesh(boxGeometry, boxMaterial);
        boxMesh.position.set(-5, -3.2, 1);
        boxMesh.rotation.x = 0.4;
        boxMesh.rotation.y = 0.6;
        objectsGroup.add(boxMesh);

        // Ribbon 1 (Horizontal)
        const ribbon1Geo = new THREE.BoxGeometry(3.7, 0.65, 3.7);
        const ribbonMat = new THREE.MeshStandardMaterial({
            color: 0xFFF3E0,
            metalness: 0.1,
            roughness: 0.2,
            emissive: 0xFDBA74,
            emissiveIntensity: 0.25
        });
        const ribbon1 = new THREE.Mesh(ribbon1Geo, ribbonMat);
        boxMesh.add(ribbon1);

        // Ribbon 2 (Vertical)
        const ribbon2Geo = new THREE.BoxGeometry(0.65, 3.7, 3.7);
        const ribbon2 = new THREE.Mesh(ribbon2Geo, ribbonMat);
        boxMesh.add(ribbon2);

        // 3. Floating 3D Torus Rings
        const torusGeometry = new THREE.TorusGeometry(1.8, 0.32, 16, 60);
        const torusMaterial = new THREE.MeshStandardMaterial({
            color: 0x38BDF8,
            metalness: 0.7,
            roughness: 0.25,
            emissive: 0x0284C7,
            emissiveIntensity: 0.2
        });
        const torusMesh = new THREE.Mesh(torusGeometry, torusMaterial);
        torusMesh.position.set(5.8, -4.2, -1);
        torusMesh.rotation.x = 1.2;
        torusMesh.rotation.y = 0.5;
        objectsGroup.add(torusMesh);

        // 4. Particle Starfield Sparkles
        const particlesCount = 100;
        const positions = new Float32Array(particlesCount * 3);
        const colors = new Float32Array(particlesCount * 3);

        const colorPalette = [
            new THREE.Color(0xFF5722),
            new THREE.Color(0xF59E0B),
            new THREE.Color(0x38BDF8),
            new THREE.Color(0xFFFFFF)
        ];

        for (let i = 0; i < particlesCount; i++) {
            positions[i * 3] = (Math.random() - 0.5) * 40;
            positions[i * 3 + 1] = (Math.random() - 0.5) * 36;
            positions[i * 3 + 2] = (Math.random() - 0.5) * 20;

            const clr = colorPalette[Math.floor(Math.random() * colorPalette.length)];
            colors[i * 3] = clr.r;
            colors[i * 3 + 1] = clr.g;
            colors[i * 3 + 2] = clr.b;
        }

        const particleGeometry = new THREE.BufferGeometry();
        particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particleGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

        const particleMaterial = new THREE.PointsMaterial({
            size: 0.22,
            vertexColors: true,
            transparent: true,
            opacity: 0.85
        });

        const particlePoints = new THREE.Points(particleGeometry, particleMaterial);
        scene.add(particlePoints);

        // Parallax Interaction
        let mouseX = 0;
        let mouseY = 0;
        let targetX = 0;
        let targetY = 0;

        const heroSection = document.querySelector('.hero-marketplace-3d');
        if (heroSection) {
            heroSection.addEventListener('mousemove', (e) => {
                const rect = heroSection.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width;
                const y = (e.clientY - rect.top) / rect.height;
                targetX = (x - 0.5) * 1.8;
                targetY = (y - 0.5) * 1.8;
            });
            heroSection.addEventListener('mouseleave', () => {
                targetX = 0;
                targetY = 0;
            });
        }

        // Animation Loop
        let clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const elapsedTime = clock.getElapsedTime();

            // Smooth mouse parallax lerp
            mouseX += (targetX - mouseX) * 0.05;
            mouseY += (targetY - mouseY) * 0.05;

            objectsGroup.rotation.y = mouseX * 0.4;
            objectsGroup.rotation.x = mouseY * 0.4;

            // Coin bounce & spin
            coinMesh.rotation.z = elapsedTime * 0.9;
            coinMesh.position.y = 3.8 + Math.sin(elapsedTime * 1.8) * 0.6;

            // Parcel cube slow hover
            boxMesh.rotation.y = elapsedTime * 0.5;
            boxMesh.rotation.x = 0.4 + Math.sin(elapsedTime * 1.2) * 0.15;
            boxMesh.position.y = -3.2 + Math.cos(elapsedTime * 1.4) * 0.45;

            // Torus gentle rotate
            torusMesh.rotation.x = elapsedTime * 0.7;
            torusMesh.rotation.z = elapsedTime * 0.4;
            torusMesh.position.y = -4.2 + Math.sin(elapsedTime * 2.0 + 1) * 0.5;

            // Particle drift
            particlePoints.rotation.y = elapsedTime * 0.03;

            renderer.render(scene, camera);
        }

        animate();

        // Responsive Resize
        window.addEventListener('resize', () => {
            if (!canvasContainer) return;
            const newWidth = canvasContainer.clientWidth || 440;
            const newHeight = canvasContainer.clientHeight || 420;
            camera.aspect = newWidth / newHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(newWidth, newHeight);
        });
    }

    /* ─── 2. 60FPS CARD TILT & SPECULAR SHEEN ENGINE ─── */
    function init3DCardTilt() {
        // Find all cards with data-3d-tilt
        const tiltCards = document.querySelectorAll('[data-3d-tilt]');
        if (!tiltCards.length) return;

        tiltCards.forEach((card) => {
            const maxTilt = parseFloat(card.dataset.tiltMax) || 10;
            const glare = card.querySelector('.card-3d-glare');

            let rect = null;
            let currentX = 0;
            let currentY = 0;
            let targetX = 0;
            let targetY = 0;
            let isHovered = false;
            let rafId = null;

            function updateTilt() {
                // Smooth linear interpolation (lerp)
                currentX += (targetX - currentX) * 0.14;
                currentY += (targetY - currentY) * 0.14;

                const rotateY = currentX * maxTilt;
                const rotateX = -currentY * maxTilt;

                card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateZ(10px)`;

                // Update glare position
                if (glare && isHovered) {
                    const glareX = ((currentX + 1) / 2) * 100;
                    const glareY = ((currentY + 1) / 2) * 100;
                    glare.style.background = `radial-gradient(circle 280px at ${glareX}% ${glareY}%, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0) 75%)`;
                }

                // Continue loop while moving or returning
                if (isHovered || Math.abs(currentX) > 0.005 || Math.abs(currentY) > 0.005) {
                    rafId = requestAnimationFrame(updateTilt);
                } else {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0)';
                    rafId = null;
                }
            }

            card.addEventListener('mouseenter', () => {
                isHovered = true;
                rect = card.getBoundingClientRect();
                if (!rafId) rafId = requestAnimationFrame(updateTilt);
            });

            card.addEventListener('mousemove', (e) => {
                if (!rect) rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                // Normalized between -1 and 1
                targetX = (x / rect.width) * 2 - 1;
                targetY = (y / rect.height) * 2 - 1;

                if (!rafId) rafId = requestAnimationFrame(updateTilt);
            });

            card.addEventListener('mouseleave', () => {
                isHovered = false;
                targetX = 0;
                targetY = 0;
            });
        });
    }

    // Initialize on DOM Ready
    function init() {
        initHeroThreeScene();
        init3DCardTilt();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // In case Three.js finishes loading after DOMContentLoaded
    window.addEventListener('load', () => {
        const canvas = document.getElementById('heroThreeCanvas');
        if (canvas && (!canvas.children || canvas.children.length === 0)) {
            initHeroThreeScene();
        }
    });

})();
