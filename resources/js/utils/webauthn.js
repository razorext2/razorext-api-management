/** WebAuthn / Passkeys JS Helper for Dacin Dashboard **/

function bufferToBase64(buffer) {
    if (!buffer) return "";
    const bytes = new Uint8Array(buffer);
    let binary = "";
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary)
        .replace(/\+/g, "-")
        .replace(/\//g, "_")
        .replace(/=/g, "");
}

function base64ToBuffer(base64Input) {
    if (!base64Input) return new Uint8Array(0).buffer;

    if (typeof base64Input !== "string") {
        if (base64Input instanceof ArrayBuffer) return base64Input;
        if (ArrayBuffer.isView(base64Input)) return base64Input.buffer;
        return new Uint8Array(0).buffer;
    }

    let base64 = base64Input.trim();

    // Strip lbuchs/webauthn RFC 1342 binary wrapper if present
    if (base64.startsWith("=?BINARY?B?") && base64.endsWith("?=")) {
        base64 = base64.substring(11, base64.length - 2);
    }

    // Convert Base64URL to standard Base64
    base64 = base64.replace(/-/g, "+").replace(/_/g, "/");

    // Add padding if missing
    while (base64.length % 4 !== 0) {
        base64 += "=";
    }

    const binary = atob(base64);
    const buffer = new Uint8Array(binary.length);
    for (let i = 0; i < binary.length; i++) {
        buffer[i] = binary.charCodeAt(i);
    }
    return buffer.buffer;
}

function parseWebAuthnError(err) {
    if (!err) return "Terjadi kesalahan pada autentikasi biometrik.";

    const name = err.name || "";
    const msg = (err.message || "").toLowerCase();

    if (
        name === "NotAllowedError" ||
        name === "AbortError" ||
        msg.includes("not allowed") ||
        msg.includes("timed out") ||
        msg.includes("cancelled") ||
        msg.includes("canceled")
    ) {
        return "Pemindaian biometrik / Passkey dibatalkan oleh pengguna.";
    }
    if (name === "TimeoutError") {
        return "Waktu pemindaian Passkey telah habis. Silakan coba kembali.";
    }
    if (name === "InvalidStateError") {
        return "Perangkat atau Passkey ini sudah terdaftar sebelumnya.";
    }
    if (name === "NotSupportedError" || msg.includes("not supported")) {
        return "Browser atau perangkat Anda belum mendukung Passkey biometrik.";
    }

    return err.message || "Gagal melakukan pemindaian biometrik.";
}

export async function registerPasskey(options) {
    if (!window.PublicKeyCredential) {
        throw new Error("Browser Anda tidak mendukung Passkey / WebAuthn.");
    }

    try {
        // Handle both wrapped { publicKey: { ... } } and direct { challenge: ... }
        const rawOptions =
            options && options.publicKey ? options.publicKey : options;
        const publicKeyOptions = { ...rawOptions };

        if (publicKeyOptions.challenge) {
            publicKeyOptions.challenge = base64ToBuffer(
                publicKeyOptions.challenge
            );
        }

        if (publicKeyOptions.user && publicKeyOptions.user.id) {
            publicKeyOptions.user.id = base64ToBuffer(
                publicKeyOptions.user.id
            );
        }

        if (
            publicKeyOptions.excludeCredentials &&
            Array.isArray(publicKeyOptions.excludeCredentials)
        ) {
            publicKeyOptions.excludeCredentials =
                publicKeyOptions.excludeCredentials.map((c) => ({
                    ...c,
                    id: base64ToBuffer(typeof c === "object" ? c.id : c),
                }));
        }

        const credential = await navigator.credentials.create({
            publicKey: publicKeyOptions,
        });

        return {
            id: credential.id,
            rawId: bufferToBase64(credential.rawId),
            clientDataJSON: bufferToBase64(credential.response.clientDataJSON),
            attestationObject: bufferToBase64(
                credential.response.attestationObject
            ),
        };
    } catch (err) {
        throw new Error(parseWebAuthnError(err));
    }
}

export async function authenticatePasskey(options) {
    if (!window.PublicKeyCredential) {
        throw new Error("Browser Anda tidak mendukung Passkey / WebAuthn.");
    }

    try {
        // Handle both wrapped { publicKey: { ... } } and direct { challenge: ... }
        const rawOptions =
            options && options.publicKey ? options.publicKey : options;
        const publicKeyOptions = { ...rawOptions };

        if (publicKeyOptions.challenge) {
            publicKeyOptions.challenge = base64ToBuffer(
                publicKeyOptions.challenge
            );
        }

        if (
            publicKeyOptions.allowCredentials &&
            Array.isArray(publicKeyOptions.allowCredentials)
        ) {
            publicKeyOptions.allowCredentials =
                publicKeyOptions.allowCredentials.map((c) => ({
                    ...c,
                    id: base64ToBuffer(typeof c === "object" ? c.id : c),
                }));
        }

        const credential = await navigator.credentials.get({
            publicKey: publicKeyOptions,
        });

        return {
            id: credential.id,
            rawId: bufferToBase64(credential.rawId),
            clientDataJSON: bufferToBase64(credential.response.clientDataJSON),
            authenticatorData: bufferToBase64(
                credential.response.authenticatorData
            ),
            signature: bufferToBase64(credential.response.signature),
        };
    } catch (err) {
        throw new Error(parseWebAuthnError(err));
    }
}

window.WebAuthnHelper = {
    registerPasskey,
    authenticatePasskey,
};
