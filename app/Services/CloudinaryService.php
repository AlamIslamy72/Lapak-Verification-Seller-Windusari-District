<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CloudinaryService
{
    /**
     * Upload sebuah file ke Cloudinary dan kembalikan secure_url-nya.
     *
     * @param  UploadedFile  $file
     * @param  string  $folder  Nama folder di Cloudinary, mis. 'product-photos'
     * @return string  URL lengkap (secure_url) file yang sudah di-upload
     */
    public function upload(UploadedFile $file, string $folder): string
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            throw new RuntimeException('Konfigurasi Cloudinary belum lengkap. Cek CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET di .env');
        }

        $timestamp = time();

        // Parameter yang dikirim & ikut ditandatangani (harus urut alfabetis by key)
        $paramsToSign = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];

        $signature = $this->generateSignature($paramsToSign, $apiSecret);

        $response = Http::attach(
                'file',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()
            )
            ->asMultipart()
            ->post("https://api.cloudinary.com/v1_1/{$cloudName}/auto/upload", [
                'api_key' => $apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder' => $folder,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Upload ke Cloudinary gagal: ' . $response->body());
        }

        return $response->json('secure_url');
    }

    protected function generateSignature(array $params, string $apiSecret): string
    {
        ksort($params);

        $pairs = [];
        foreach ($params as $key => $value) {
            $pairs[] = "{$key}={$value}";
        }

        $stringToSign = implode('&', $pairs) . $apiSecret;

        return sha1($stringToSign);
    }
}
