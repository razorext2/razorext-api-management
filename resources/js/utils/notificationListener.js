import { showToast } from "./alert";

export function handleNotification(data) {
    let message =
        data.message.split(".").slice(0, 2).join(". ") +
        (data.message.split(".").length > 2 ? "..." : "");
    showToast("info", message);

    // Trigger pembaruan UI notifikasi melalui Livewire
    if (typeof Livewire !== "undefined") {
        Livewire.dispatch("notification-received");
    }
}
