<?php

if (! function_exists('storage_asset')) {
    /**
     * URL untuk file di storage/app/public.
     *
     * Memakai file statis bila symlink public/storage tersedia (dicek apakah
     * file yang diakses benar-benar file yang sama), jika tidak dialihkan ke
     * route penyaji file agar tetap berfungsi di komputer lain.
     */
    function storage_asset(?string $path): string
    {
        $path = trim((string) $path, '/');

        if ($path === '') {
            return asset('storage');
        }

        $publicFile = public_path('storage/' . $path);
        $realFile = storage_path('app/public/' . $path);
        $isServedByWebServer = is_link(public_path('storage'))
            || (is_file($publicFile) && realpath($publicFile) === realpath($realFile));

        return $isServedByWebServer
            ? asset('storage/' . $path)
            : route('storage.file', ['path' => $path]);
    }
}