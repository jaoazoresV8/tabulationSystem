<x-admin-layout>
    <x-slot name="title">DASHBOARD</x-slot>

    <div class="flex flex-col gap-12">
        <!-- TOP SECTION: GREETING & INTEGRATED STATS -->
        <div class="flex flex-col gap-8">
            <div>
                <h2 class="text-5xl tracking-[0.25em] mb-2 leading-none font-bebas">Welcome Back, Operator</h2>
                <div class="flex items-center gap-4">
                    <span class="h-0.5 w-12 bg-accent"></span>
                </div>
            </div>

            <!-- EMBEDDED INFO BLOCKS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $statIcons = [
                        'ACTIVE CONTESTS' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4l3 2"/>',
                        'TOTAL CONTESTANTS' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                        'TOTAL JUDGES' => '<path d="M12 11v11"/><path d="M12 2v6"/><path d="M16.2 7.8l2.9-2.9"/><path d="M7.8 7.8L4.9 4.9"/><path d="M16.2 16.2l2.9 2.9"/><path d="M7.8 16.2l-2.9 2.9"/><path d="M22 12h-6"/><path d="M2 12h6"/>',
                        'COMPLETED EXPOSURES' => '<polyline points="20 6 9 17 4 12"/>'
                    ];
                @endphp
                @foreach($stats as $label => $value)
                <div class="tactical-panel p-6 border-l-2 border-l-accent/50 group hover:border-l-accent transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="tactical-icon-badge shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                {!! $statIcons[$label] ?? '' !!}
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-mono text-text-secondary uppercase tracking-[0.3em] mb-1 group-hover:text-white transition-colors">{{ $label }}</span>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-mono text-white leading-none">{{ sprintf('%02d', $value) }}</span>
                                <span class="text-accent text-[8px] font-mono mb-1 animate-pulse tracking-tighter">LIVE</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- MAIN CONTROL AREA GRID -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            <!-- RECENT CONTESTS (Integrated Table) -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex justify-between items-center border-b border-border pb-4">
                    <h3 class="text-xl tracking-widest text-accent font-bebas">Active Contest Stream</h3>
                    <a href="{{ route('contests') }}" class="text-[9px] font-mono text-text-secondary hover:text-white uppercase tracking-widest transition-colors">ACCESS_ALL_DATA</a>
                </div>

                <div class="tactical-panel bg-panel/30">
                    <table class="w-full text-left font-mono">
                        <thead>
                            <tr class="text-[9px] text-text-secondary uppercase tracking-[0.3em] border-b border-border/50">
                                <th class="p-6 font-normal">Ident Code</th>
                                <th class="p-6 font-normal">Contest Designation</th>
                                <th class="p-6 font-normal text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/20 text-xs">
                            @foreach($recentContests as $contest)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="p-6 text-text-secondary">#C{{ sprintf('%03d', $contest->id) }}</td>
                                <td class="p-6 text-text-primary tracking-widest uppercase">{{ $contest->name }}</td>
                                <td class="p-6 text-right">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 border border-accent/20 bg-accent/5">
                                         <span class="w-1 h-1 bg-accent"></span>
                                         <span class="text-[9px] text-accent uppercase tracking-widest">{{ $contest->status }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TECHNICAL DECORATION / CENTERPIECE -->
            <div class="space-y-6 h-full flex flex-col">
                 <div class="flex justify-between items-center border-b border-border pb-4">
                    <h3 class="text-xl tracking-widest text-text-secondary font-bebas">Mission Control</h3>
                </div>

                <div class="tactical-panel flex-1 flex flex-col items-center justify-center min-h-[500px] relative overflow-hidden bg-[#0B0F14]">
                    <!-- Scanline Noise Overlay -->
                    <div class="absolute inset-0 opacity-[0.05] pointer-events-none z-40"
                         style="background-image: repeating-linear-gradient(0deg, transparent, transparent 1px, rgba(255,255,255,0.05) 1px, rgba(255,255,255,0.05) 2px); background-size: 100% 4px;"></div>

                    <div id="trophy-container" class="absolute inset-0 z-10 opacity-70">
                         <!-- Three.js will inject canvas here -->
                    </div>

                    <!-- UI Overlay Layer -->
                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-end pb-16 pointer-events-none">
                        <!-- Bottom Integrated Labeling -->
                        <div class="flex flex-col items-center text-center w-full pb-4">
                            <h4 class="text-8xl font-bebas tracking-[0.1em] text-white leading-none drop-shadow-[0_0_30px_rgba(255,70,85,0.5)] animate-tactical-reveal opacity-0" style="animation-fill-mode: forwards;">
                                {{ $currentExposure->name ?? 'SYSTEM STANDBY' }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS / SYSTEM CMDS -->
        <div class="flex flex-col gap-6">
             <div class="flex items-center gap-4">
                 <h3 class="text-xl tracking-widest text-text-secondary whitespace-nowrap font-bebas">Rapid Response Commands</h3>
                 <div class="h-px w-full bg-border/50"></div>
             </div>

             <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('contests.create') }}" class="tactical-panel p-8 group hover:bg-accent/[0.03] transition-all flex flex-col items-center justify-center gap-4 group">
                    <div class="w-10 h-10 border border-white/10 flex items-center justify-center text-accent bg-accent/5 group-hover:bg-accent group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <span class="text-[10px] font-mono text-text-secondary group-hover:text-white uppercase tracking-[0.3em]">Init New Contest</span>
                </a>
                <a href="{{ route('exposures') }}" class="tactical-panel p-8 group hover:bg-accent/[0.03] transition-all flex flex-col items-center justify-center gap-4 group">
                    <div class="w-10 h-10 border border-white/10 flex items-center justify-center text-accent bg-accent/5 group-hover:bg-accent group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="text-[10px] font-mono text-text-secondary group-hover:text-white uppercase tracking-[0.3em]">Manage Timeline</span>
                </a>
                <a href="{{ route('judges') }}" class="tactical-panel p-8 group hover:bg-accent/[0.03] transition-all flex flex-col items-center justify-center gap-4 group">
                    <div class="w-10 h-10 border border-white/10 flex items-center justify-center text-accent bg-accent/5 group-hover:bg-accent group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span class="text-[10px] font-mono text-text-secondary group-hover:text-white uppercase tracking-[0.3em]">Auth Personnel</span>
                </a>
                <a href="{{ route('tabulation') }}" class="tactical-panel p-8 group hover:bg-accent/[0.03] transition-all flex flex-col items-center justify-center gap-4 group">
                    <div class="w-10 h-10 border border-white/10 flex items-center justify-center text-accent bg-accent/5 group-hover:bg-accent group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <span class="text-[10px] font-mono text-text-secondary group-hover:text-white uppercase tracking-[0.3em]">Start Tabulation</span>
                </a>
             </div>
        </div>
    </div>
</x-admin-layout>

<script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
            "three/examples/jsm/loaders/GLTFLoader": "https://unpkg.com/three@0.160.0/examples/jsm/loaders/GLTFLoader.js"
        }
    }
</script>

<script type="module">
    import * as THREE from 'three';
    import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('trophy-container');
        if (!container) return;

        const width = container.clientWidth;
        const height = container.clientHeight;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 1000);
        camera.position.set(0, 0, 10);

        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        container.appendChild(renderer.domElement);

        // Dramatic Lights for Seamless Blending
        const mainLight = new THREE.PointLight(0xff4655, 100, 30);
        mainLight.position.set(2, 5, 5);
        scene.add(mainLight);

        const fillLight = new THREE.PointLight(0xffffff, 20, 20);
        fillLight.position.set(-5, 2, 5);
        scene.add(fillLight);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.1);
        scene.add(ambientLight);

        // This light creates the "Red Bloom" glow in the background
        const backLight = new THREE.PointLight(0xff4655, 400, 40);
        backLight.position.set(0, 2, -5);
        scene.add(backLight);

        // Add Fog for seamless distance blending
        scene.fog = new THREE.Fog(0x0B0F14, 5, 25);

        // Tiny Light Up Details (LED Particles)
        const detailGroup = new THREE.Group();
        for(let i = 0; i < 60; i++) {
            const ledGeo = new THREE.BoxGeometry(0.04, 0.04, 0.04);
            const ledMat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true });
            const led = new THREE.Mesh(ledGeo, ledMat);
            led.position.set(
                (Math.random() - 0.5) * 20,
                (Math.random() - 0.5) * 15,
                (Math.random() - 0.5) * 5 - 4
            );
            led.userData.speed = 0.5 + Math.random();
            detailGroup.add(led);
        }
        scene.add(detailGroup);

        const trophyGroup = new THREE.Group();
        trophyGroup.position.y = -0.5; // Lowered to meet the text base
        scene.add(trophyGroup);

        // MODEL LOADER
        const loader = new GLTFLoader();
        const modelPath = '/yourewinner.glb';

        loader.load(
            modelPath,
            (gltf) => {
                const model = gltf.scene;

                model.traverse((node) => {
                    if (node.isMesh) {
                        node.material = new THREE.MeshStandardMaterial({
                            color: 0xaa1122,
                            metalness: 1,
                            roughness: 0.15,
                            emissive: 0x220000,
                            emissiveIntensity: 0.2
                        });
                    }
                });

                // Auto-scale and center
                const box = new THREE.Box3().setFromObject(model);
                const size = box.getSize(new THREE.Vector3());
                const center = box.getCenter(new THREE.Vector3());

                model.position.x = -center.x;
                model.position.y = -center.y;
                model.position.z = -center.z;

                const maxDim = Math.max(size.x, size.y, size.z);
                const scale = 4 / maxDim; // Scaled down further for clarity
                trophyGroup.scale.set(scale, scale, scale);

                trophyGroup.add(model);
            },
            undefined,
            (error) => {
                console.warn("Using Fallback: Model not found at " + modelPath);

                // HIGH QUALITY FALLBACK SHARD
                const shardGeo = new THREE.OctahedronGeometry(2.5, 0);
                const shardMat = new THREE.MeshStandardMaterial({
                    color: 0xff4655,
                    metalness: 1,
                    roughness: 0.2,
                    wireframe: false,
                    transparent: true,
                    opacity: 0.8
                });
                const shard = new THREE.Mesh(shardGeo, shardMat);

                const wireGeo = new THREE.EdgesGeometry(shardGeo);
                const wireMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.5 });
                const wire = new THREE.LineSegments(wireGeo, wireMat);
                shard.add(wire);

                trophyGroup.add(shard);
            }
        );

        // Technical Environment (Rings)
        const ringMat = new THREE.MeshBasicMaterial({ color: 0xff4655, transparent: true, opacity: 0.15 });
        const ring1 = new THREE.Mesh(new THREE.TorusGeometry(6, 0.01, 8, 100), ringMat);
        ring1.rotation.x = Math.PI / 2;
        scene.add(ring1);

        const ring2 = new THREE.Mesh(new THREE.TorusGeometry(5, 0.005, 8, 4), ringMat);
        ring2.rotation.x = Math.PI / 2;
        scene.add(ring2);

        // ANIMATION LOOP
        const animate = () => {
            requestAnimationFrame(animate);
            const time = Date.now() * 0.001;

            trophyGroup.rotation.y += 0.004;
            trophyGroup.position.y = -0.5 + Math.sin(time * 0.8) * 0.1;

            // Dynamic Glow Pulsing
            backLight.intensity = 400 + Math.sin(time * 2) * 50;

            // Animate LED Details (Floating particles)
            detailGroup.children.forEach((led, i) => {
                led.position.y += Math.sin(time + i) * 0.002;
                led.material.opacity = 0.2 + Math.sin(time * led.userData.speed) * 0.3;
                if (Math.random() > 0.995) {
                    led.scale.set(1.8, 1.8, 1.8);
                } else {
                    led.scale.set(1, 1, 1);
                }
            });

            ring1.rotation.z += 0.001;
            ring2.rotation.z -= 0.002;

            renderer.render(scene, camera);
        };

        animate();

        window.addEventListener('resize', () => {
            camera.aspect = container.clientWidth / container.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(container.clientWidth, container.clientHeight);
        });
    });
</script>
