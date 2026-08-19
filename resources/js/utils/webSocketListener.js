import { showToast } from "./alert";
import { handleNotification } from "./notificationListener";

export async function initWebSocketListener() {
    // define userID, ambil dari metatag user-id
    const userId = document.querySelector('meta[name="user-id"]');

    if (userId) {
        // Listener notifikasi per-user
        window.Echo.private(`notifications.${userId.content}`)
            .listen(".exportCompleted", (data) => {
                handleNotification(data);
            })
            .listen(".backupReady", (data) => {
                showToast("success", data.message);
                Livewire.dispatch("pg:eventRefresh-BackupTable");
            });

        // Listen for generic PowerGrid table refreshes
        window.Echo.channel("powergrid-updates")
            .listen(".TableRefreshed", (data) => {
                if (typeof Livewire !== "undefined") {
                    Livewire.dispatch("pg:eventRefresh-" + data.tableName);
                }
            });
    }
}
