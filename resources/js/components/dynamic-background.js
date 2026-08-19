/** Goal: Alpine.js component for ultra-lightweight CSS Masking and Canvas 2D hybrid background, Caller: dynamic-background.blade.php, Deps: Alpine.js */
document.addEventListener("alpine:init", () => {
    Alpine.data("dynamicBackground", () => ({
        quality: "high",
        canvas2d: null,
        ctx: null,
        width: 0,
        height: 0,
        isTabVisible: true,
        _abortController: null,
        _resizeTimer: null,
        _bgCache: null,
        _themeObserver: null,

        init() {
            const prefersReducedMotion = window.matchMedia(
                "(prefers-reduced-motion: reduce)",
            ).matches;
            const isLowHardware =
                navigator.hardwareConcurrency &&
                navigator.hardwareConcurrency < 4;
            const isMobile =
                window.matchMedia("(pointer: coarse)").matches ||
                /Mobi|Android|iPhone|iPad/i.test(navigator.userAgent);

            if (prefersReducedMotion || isLowHardware) {
                this.quality = "low";
            } else if (isMobile) {
                this.quality = "medium";
            } else {
                this.quality = "high";
            }

            // Observe dark mode toggle to invalidate accent shape cache
            this._themeObserver = new MutationObserver(() => {
                this._bgCache = null;
                this.draw();
            });
            this._themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ["class"],
            });

            this.canvas2d = this.$refs.canvas2d;
            this.ctx = this.canvas2d.getContext("2d");

            // AbortController for centralized event listener cleanup
            this._abortController = new AbortController();
            const signal = this._abortController.signal;

            // Page Visibility API
            document.addEventListener(
                "visibilitychange",
                () => {
                    this.isTabVisible = !document.hidden;
                    if (this.isTabVisible) {
                        this.draw();
                    }
                },
                { signal },
            );

            // Debounced resize handler
            window.addEventListener(
                "resize",
                () => {
                    clearTimeout(this._resizeTimer);
                    this._resizeTimer = setTimeout(() => this.resize(), 150);
                },
                { signal },
            );

            // Light-weight mousemove handler to update CSS variables for CSS masking
            window.addEventListener(
                "mousemove",
                (e) => {
                    if (!this.isTabVisible || this.quality === "low" || !this.dynamicBg) return;

                    this.$el.style.setProperty("--mouse-x", e.clientX + "px");
                    this.$el.style.setProperty("--mouse-y", e.clientY + "px");
                },
                { passive: true, signal },
            );

            this.resize();
        },

        resize() {
            this.width = window.innerWidth;
            this.height = window.innerHeight;

            let dpr = window.devicePixelRatio || 1;
            if (this.quality === "medium") {
                dpr = Math.min(dpr, 1.25);
            } else if (this.quality === "low") {
                dpr = 1.0;
            }

            if (this.canvas2d) {
                this.canvas2d.width = this.width * dpr;
                this.canvas2d.height = this.height * dpr;
                this.ctx.setTransform(1, 0, 0, 1, 0, 0);
                this.ctx.scale(dpr, dpr);
            }

            this._bgCache = null;
            this.draw();
        },

        draw() {
            if (!this.isTabVisible) return;
            this.draw2D();
        },

        destroy() {
            if (this._abortController) {
                this._abortController.abort();
                this._abortController = null;
            }
            if (this._themeObserver) {
                this._themeObserver.disconnect();
                this._themeObserver = null;
            }
            clearTimeout(this._resizeTimer);
            this._bgCache = null;
        },

        _buildBgCache() {
            const W = this.width,
                H = this.height;
            if (!W || !H) return;

            const offscreen = document.createElement("canvas");
            offscreen.width = W;
            offscreen.height = H;
            const ox = offscreen.getContext("2d");

            const nSteps =
                this.quality === "low"
                    ? 15
                    : this.quality === "medium"
                      ? 20
                      : 60;

            const sampledBezier = (p0, p1, p2, p3, n = nSteps) => {
                let pts = [];
                for (let i = 0; i <= n; i++) {
                    let t = i / n,
                        mt = 1 - t;
                    pts.push([
                        mt * mt * mt * p0[0] +
                            3 * mt * mt * t * p1[0] +
                            3 * mt * t * t * p2[0] +
                            t * t * t * p3[0],
                        mt * mt * mt * p0[1] +
                            3 * mt * mt * t * p1[1] +
                            3 * mt * t * t * p2[1] +
                            t * t * t * p3[1],
                    ]);
                }
                return pts;
            };

            const fillShape = (pts, gradient) => {
                ox.beginPath();
                pts.forEach(([x, y], i) =>
                    i === 0 ? ox.moveTo(x, y) : ox.lineTo(x, y),
                );
                ox.closePath();
                ox.fillStyle = gradient;
                ox.fill();
            };

            const isDark = document.documentElement.classList.contains("dark");

            // --- Topographic Contour Lines ---
            const drawTopoLine = (startRatio, endRatio, control1Ratio, control2Ratio) => {
                let pts = sampledBezier(
                    [W * startRatio, 0],
                    [W * control1Ratio, 0],
                    [W, H * control2Ratio],
                    [W, H * endRatio],
                );
                ox.beginPath();
                pts.forEach(([x, y], i) => i === 0 ? ox.moveTo(x, y) : ox.lineTo(x, y));
                ox.strokeStyle = isDark ? "rgba(255,255,255,0.02)" : "rgba(120,113,108,0.08)";
                ox.lineWidth = 1;
                ox.stroke();
            };

            const drawTopoLineBL = (startRatio, endRatio, control1Ratio, control2Ratio) => {
                let pts = sampledBezier(
                    [W * startRatio, H],
                    [W * control1Ratio, H],
                    [0, H * control2Ratio],
                    [0, H * endRatio],
                );
                ox.beginPath();
                pts.forEach(([x, y], i) => i === 0 ? ox.moveTo(x, y) : ox.lineTo(x, y));
                ox.strokeStyle = isDark ? "rgba(255,255,255,0.015)" : "rgba(120,113,108,0.07)";
                ox.lineWidth = 1;
                ox.stroke();
            };

            // Draw 8 concentric topo lines at top-right
            for (let i = 1; i <= 8; i++) {
                let factor = 0.1 + i * 0.085;
                drawTopoLine(1 - factor, factor, 1 - factor * 0.7, factor * 0.7);
            }

            // Draw 6 concentric topo lines at bottom-left
            for (let i = 1; i <= 6; i++) {
                let factor = 0.08 + i * 0.07;
                drawTopoLineBL(factor, 1 - factor, factor * 0.7, 1 - factor * 0.7);
            }

            // --- Accent 1: Large radial sweep from top-right corner ---
            let arc1pts = [[W, 0]];
            arc1pts = arc1pts.concat(
                sampledBezier(
                    [W * 0.35, 0],
                    [W * 0.65, 0],
                    [W, H * 0.25],
                    [W, H * 0.65],
                ),
            );
            let g1 = ox.createRadialGradient(W, 0, W * 0.05, W, 0, W * 0.75);
            g1.addColorStop(
                0,
                isDark ? "rgba(35,5,5,1)" : "rgba(254,244,244,1)",
            );
            g1.addColorStop(
                0.45,
                isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)",
            );
            g1.addColorStop(1, isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)");
            fillShape(arc1pts, g1);

            // --- Accent 2: Sharper inner arc, top-right ---
            let arc2pts = [[W, 0]];
            arc2pts = arc2pts.concat(
                sampledBezier(
                    [W * 0.68, 0],
                    [W * 0.88, 0],
                    [W, H * 0.1],
                    [W, H * 0.32],
                ),
            );
            let g2 = ox.createRadialGradient(W, 0, 0, W, 0, W * 0.38);
            g2.addColorStop(
                0,
                isDark ? "rgba(60,8,8,1)" : "rgba(254,220,220,1)",
            );
            g2.addColorStop(
                0.55,
                isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)",
            );
            g2.addColorStop(1, isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)");
            fillShape(arc2pts, g2);

            // --- Accent 3: Subtle counter-arc from bottom-left ---
            let arc3pts = [[0, H]];
            arc3pts = arc3pts.concat(
                sampledBezier(
                    [W * 0.28, H],
                    [W * 0.1, H],
                    [0, H * 0.82],
                    [0, H * 0.55],
                ),
            );
            let g3 = ox.createRadialGradient(0, H, 0, 0, H, H * 0.48);
            g3.addColorStop(
                0,
                isDark ? "rgba(30,5,5,1)" : "rgba(254,244,244,1)",
            );
            g3.addColorStop(
                0.5,
                isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)",
            );
            g3.addColorStop(1, isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)");
            fillShape(arc3pts, g3);

            // --- Accent 4: New inner sharpest arc, top-right (on top of Accent 2) ---
            let arc4pts = [[W, 0]];
            arc4pts = arc4pts.concat(
                sampledBezier(
                    [W * 0.82, 0],
                    [W * 0.91, 0],
                    [W, H * 0.05],
                    [W, H * 0.18],
                ),
            );
            let g4 = ox.createRadialGradient(W, 0, 0, W, 0, W * 0.22);
            g4.addColorStop(
                0,
                isDark ? "rgba(90,12,12,1)" : "rgba(254,191,191,1)",
            );
            g4.addColorStop(
                0.6,
                isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)",
            );
            g4.addColorStop(1, isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)");
            fillShape(arc4pts, g4);

            // --- Accent 5: New inner sharpest counter-arc, bottom-left (on top of Accent 3) ---
            let arc5pts = [[0, H]];
            arc5pts = arc5pts.concat(
                sampledBezier(
                    [W * 0.15, H],
                    [W * 0.05, H],
                    [0, H * 0.85],
                    [0, H * 0.72],
                ),
            );
            let g5 = ox.createRadialGradient(0, H, 0, 0, H, H * 0.28);
            g5.addColorStop(
                0,
                isDark ? "rgba(70,10,10,1)" : "rgba(254,220,220,1)",
            );
            g5.addColorStop(
                0.6,
                isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)",
            );
            g5.addColorStop(1, isDark ? "rgba(9,9,11,1)" : "rgba(250,248,245,1)");
            fillShape(arc5pts, g5);

            this._bgCache = offscreen;
        },

        draw2D() {
            if (!this.ctx) return;
            this.ctx.clearRect(0, 0, this.width, this.height);

            if (!this._bgCache) {
                this._buildBgCache();
            }

            if (this._bgCache) {
                this.ctx.drawImage(this._bgCache, 0, 0);
            }
        },
    }));
});
