<?php

/** Goal: Obfuskasi integer ID ke hash string pendek (tanpa library eksternal), Caller: TechnicianController & Livewire Show, Deps: config('app.key') */

namespace App\Support;

use InvalidArgumentException;

/**
 * ID Obfuscator menggunakan XOR cipher + base36 encoding.
 *
 * Teknik:
 *  1. Ambil 4 byte pertama dari APP_KEY sebagai XOR key integer.
 *  2. XOR id dengan key tersebut.
 *  3. Encode hasilnya ke base36 (a-z, 0-9) — URL-safe tanpa encoding tambahan.
 *
 * Keamanan:
 *  - Tidak bisa direverse tanpa APP_KEY (kunci aplikasi Laravel).
 *  - Tidak mengekspos urutan angka asli.
 *  - Nilai hash selalu konsisten untuk ID yang sama dalam satu instalasi.
 */
final class IdObfuscator
{
    /** Panjang minimum hash yang diterima untuk mencegah brute-force sederhana. */
    private const MIN_HASH_LENGTH = 2;

    /** XOR key yang diderive dari APP_KEY. Lazy-initialized. */
    private static ?int $xorKey = null;

    /**
     * Encode integer ID ke hash string.
     *
     * @throws InvalidArgumentException jika ID bukan integer positif.
     */
    public static function encode(int $id): string
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID harus integer positif, diterima: {$id}");
        }

        return base_convert((string) ($id ^ self::getXorKey()), 10, 36);
    }

    /**
     * Decode hash string ke integer ID.
     *
     * @throws InvalidArgumentException jika hash tidak valid.
     */
    public static function decode(string $hash): int
    {
        if (strlen($hash) < self::MIN_HASH_LENGTH || ! ctype_alnum($hash)) {
            throw new InvalidArgumentException('Hash tidak valid.');
        }

        $decoded = (int) base_convert($hash, 36, 10);
        $id = $decoded ^ self::getXorKey();

        if ($id <= 0) {
            throw new InvalidArgumentException('Hash tidak dapat didekode ke ID yang valid.');
        }

        return $id;
    }

    /**
     * Derive XOR key dari APP_KEY secara lazy.
     * Mengambil 4 byte pertama dari raw key (strip prefix "base64:").
     */
    private static function getXorKey(): int
    {
        if (self::$xorKey !== null) {
            return self::$xorKey;
        }

        $appKey = config('app.key');

        // Hapus prefix "base64:" jika ada, lalu decode
        if (str_starts_with($appKey, 'base64:')) {
            $rawKey = base64_decode(substr($appKey, 7));
        } else {
            $rawKey = $appKey;
        }

        // Ambil 4 byte pertama dan pack jadi unsigned 32-bit integer
        self::$xorKey = unpack('N', substr($rawKey, 0, 4))[1];

        return self::$xorKey;
    }
}
