// ===================================================================
// PWA Install Prompt — harus di-register seawal mungkin (di luar
// livewire:navigated) karena browser bisa fire `beforeinstallprompt`
// kapan saja setelah manifest ter-parse.
// ===================================================================
let deferredInstallPrompt = null;

window.addEventListener("beforeinstallprompt", (e) => {
    // Cegah mini-infobar bawaan Chrome
    e.preventDefault();
    deferredInstallPrompt = e;

    // Jika tombol sudah ada di DOM, aktifkan
    const btn = document.getElementById("installApp");
    if (btn) {
        btn.disabled = false;
        btn.style.opacity = "1";
    }
});

window.addEventListener("appinstalled", () => {
    deferredInstallPrompt = null;

    const container = document.getElementById("installAppContainer");
    if (container) container.style.display = "none";

    showInstallHint("Aplikasi terpasang. Terima kasih! 🎉");
});

// ===================================================================
// Helper: toast kecil di pojok bawah
// ===================================================================
function showInstallHint(text, ms = 6500) {
    let box = document.getElementById("install-toast");
    if (!box) {
        box = document.createElement("div");
        box.id = "install-toast";
        box.setAttribute("role", "status");
        Object.assign(box.style, {
            position: "fixed",
            zIndex: 9999,
            right: "16px",
            bottom: "16px",
            maxWidth: "360px",
            padding: "12px 14px",
            borderRadius: "12px",
            background: "rgba(10, 20, 36, .92)",
            color: "#cfe0ff",
            font: "600 14px/1.4 Inter, system-ui, sans-serif",
            boxShadow: "0 10px 30px rgba(0,0,0,.35), inset 0 0 0 1px #23334f",
            transition: "opacity .3s ease",
        });
        document.body.appendChild(box);
    }
    box.textContent = text;
    box.style.opacity = "1";
    clearTimeout(box._t);
    box._t = setTimeout(() => {
        box.style.opacity = "0";
    }, ms);
}

// ===================================================================
// Deteksi lingkungan (untuk fallback hint)
// ===================================================================
const ua = navigator.userAgent || navigator.vendor || window.opera;
const isStandalone =
    window.matchMedia("(display-mode: standalone)").matches ||
    window.navigator.standalone === true;
const isIOS =
    /iPad|iPhone|iPod/i.test(ua) ||
    (navigator.platform === "MacIntel" && navigator.maxTouchPoints > 1);
const isAndroid = /Android/i.test(ua);
const isSafari =
    /^((?!chrome|android).)*safari/i.test(ua) && !/crios|fxios/i.test(ua);
const isMac = /Macintosh|Mac OS X/.test(ua);
const isFirefox = /firefox|fxios/i.test(ua);
const isInApp =
    /(FBAN|FBAV|Instagram|Line|WhatsApp|TikTok|Twitter|WeChat|Snapchat)/i.test(
        ua,
    );

function getFallbackInstallMsg() {
    if (isInApp) {
        return 'Buka halaman ini di browser sistem (Chrome/Safari), lalu pilih "Add to Home Screen / Install".';
    } else if (isIOS) {
        return 'iOS: tap ikon Share (⬆️) → "Tambahkan ke Layar Utama" untuk memasang aplikasi.';
    } else if (isSafari && isMac) {
        return 'Safari (macOS): menu "File" → "Add to Dock" untuk memasang PWA.';
    } else if (isFirefox && isAndroid) {
        return 'Firefox Android: menu ⋮ → "Tambahkan ke Layar Utama".';
    } else if (isFirefox) {
        return "Firefox belum mendukung prompt install. Gunakan Chrome/Edge untuk install PWA.";
    } else if (isSafari) {
        return "Safari belum mendukung prompt install. Gunakan Chrome/Edge untuk install PWA.";
    }
    return 'Gunakan menu browser → "Install app" / "Add to Home screen".';
}

// ===================================================================
// Main init — dipanggil setiap navigasi Livewire
// ===================================================================
document.addEventListener("livewire:navigated", function () {
    const themeToggleDarkBtn = document.getElementById("theme-toggle-dark");
    const themeToggleLightBtn = document.getElementById("theme-toggle-light");
    const installApp = document.getElementById("installApp");
    const installAppContainer = document.getElementById("installAppContainer");
    const preloader = document.getElementById("preloader");

    // preloader is now handled with smooth exit transition in resources/js/app.js

    // atur darkmode
    const isDarkMode =
        "dark" === localStorage.getItem("color-theme") ||
        (!("color-theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches);
    toggleTheme(isDarkMode);

    if (themeToggleDarkBtn) {
        themeToggleDarkBtn.addEventListener("click", () => toggleTheme(true));
    }
    if (themeToggleLightBtn) {
        themeToggleLightBtn.addEventListener("click", () => toggleTheme(false));
    }

    // Unregister any active service worker registrations
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.getRegistrations().then((registrations) => {
            for (let registration of registrations) {
                registration.unregister();
            }
        });
    }

    // ==== INSTALL APP HANDLER ====

    // Jika sudah terpasang sebagai PWA, sembunyikan tombol
    if (isStandalone && installAppContainer) {
        installAppContainer.style.display = "none";
    }

    if (installApp) {
        installApp.addEventListener("click", async (e) => {
            e.preventDefault();

            // Chromium: gunakan deferred prompt
            if (deferredInstallPrompt) {
                deferredInstallPrompt.prompt();
                const result = await deferredInstallPrompt.userChoice;
                if (result.outcome === "accepted") {
                    deferredInstallPrompt = null;
                }

                return;
            }

            // Fallback: tampilkan instruksi manual
            showInstallHint(getFallbackInstallMsg(), 8000);
        });
    }

    // toggle tema
    function toggleTheme(e) {
        document.documentElement.classList.toggle("dark", e);
        localStorage.setItem("color-theme", e ? "dark" : "light");
        // Set persistent cookie to prevent wire:navigate transition FOUC
        document.cookie = "color-theme=" + (e ? "dark" : "light") + "; path=/; max-age=31536000; SameSite=Lax";
        
        if (themeToggleDarkBtn) {
            themeToggleDarkBtn.classList.toggle("text-gray-300", e);
            themeToggleDarkBtn.classList.toggle("text-gray-200", !e);
        }
        if (themeToggleLightBtn) {
            themeToggleLightBtn.classList.toggle("text-gray-700", e);
            themeToggleLightBtn.classList.toggle("text-red-400", !e);
        }
    }


});
