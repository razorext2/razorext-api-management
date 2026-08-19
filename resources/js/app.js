import $ from "jquery";
import "./bootstrap";
import "flowbite";
import Swal from "sweetalert2";
import flatpickr from "flatpickr";
import { initFlowbite } from "flowbite";
import { initEventListener } from "./utils/eventListener.js";
import { initWebSocketListener } from "./utils/webSocketListener";
import "./utils/webauthn.js";
import "./../../vendor/power-components/livewire-powergrid/dist/powergrid";
import "./components/dynamic-background.js";
import "./components/scroll-toggle.js";

window.flatpickr = flatpickr;
window.$ = window.jQuery = $;
window.Swal = Swal;

const triggerPreloaderExit = () => {
    const preloader = document.getElementById("preloader");
    if (preloader && !preloader.classList.contains("preloader-exit")) {
        preloader.classList.add("preloader-exit");
    }
};

window.addEventListener("load", () => {
    triggerPreloaderExit();
});

// Hard fallback (10s)
setTimeout(triggerPreloaderExit, 10000);

Livewire.hook("commit", ({ component, commit, respond, succeed, fail }) => {
    succeed(({ snapshot, effect }) => {
        queueMicrotask(() => {
            initFlowbite();
        });
    });
});

document.addEventListener("livewire:navigated", function () {
    // For SPA navigation
    triggerPreloaderExit();
    initFlowbite();
    initEventListener();
    initWebSocketListener();
});
