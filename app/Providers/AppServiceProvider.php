<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Driver "google" untuk penyimpanan Google Drive (Service Account).
        // Membutuhkan package: masbug/flysystem-google-drive-ext
        Storage::extend('google', function ($app, $config) {
            if (!class_exists(\Google\Client::class) || !class_exists(\Masbug\Flysystem\GoogleDriveAdapter::class)) {
                throw new \RuntimeException('Paket Google Drive belum terpasang. Jalankan: composer require masbug/flysystem-google-drive-ext google/apiclient');
            }

            $client = new \Google\Client();
            $client->setScopes([\Google\Service\Drive::DRIVE]);

            $refreshToken = $config['refresh_token'] ?? null;
            $clientId = $config['client_id'] ?? null;
            $clientSecret = $config['client_secret'] ?? null;

            if ($refreshToken && $clientId && $clientSecret) {
                // Mode OAuth (user/akun Google) - alternatif jika pembuatan key Service Account diblokir
                $client->setClientId($clientId);
                $client->setClientSecret($clientSecret);
                $client->fetchAccessTokenWithRefreshToken($refreshToken);
            } else {
                // Mode Service Account (JSON key)
                $credentialsPath = $config['service_account'] ?? null;
                if (is_string($credentialsPath) && $credentialsPath !== '' && !str_starts_with($credentialsPath, '{')) {
                    if (!str_starts_with($credentialsPath, DIRECTORY_SEPARATOR)) {
                        $credentialsPath = base_path($credentialsPath);
                    }
                }

                if (empty($credentialsPath) || (!is_string($credentialsPath) && !is_array($credentialsPath)) || (is_string($credentialsPath) && !str_starts_with($credentialsPath, '{') && !file_exists($credentialsPath))) {
                    throw new \RuntimeException('Kredensial Google Drive belum dikonfigurasi. Isi GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON (file JSON service account) atau GOOGLE_DRIVE_CLIENT_ID/SECRET/REFRESH_TOKEN (OAuth), serta GOOGLE_DRIVE_FOLDER_ID.');
                }

                $client->setAuthConfig($credentialsPath);
            }

            $service = new \Google\Service\Drive($client);
            $folderId = $config['folder_id'] ?? '/';

            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $folderId);
            $driver = new Filesystem($adapter);

            return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter, $config);
        });
    }
}
