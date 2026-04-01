<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RandomUserService
{
    protected static ?array $cache = null;

    /**
     * Fetch random users from the API.
     */
    public static function fetch(int $count = 10): array
    {
        $key = "users_{$count}";
        if (isset(static::$cache[$key])) {
            return static::$cache[$key];
        }

        $response = Http::get('https://randomuser.me/api/', [
            'results' => min($count, 5000),
            'noinfo' => true,
        ]);

        if (! $response->successful()) {
            return static::fallbackUsers($count);
        }

        $data = $response->json();
        $results = $data['results'] ?? [];
        static::$cache[$key] = $results;

        return $results;
    }

    /**
     * Get a random image URL (large size).
     */
    public static function imageUrl(): string
    {
        $gender = ['men', 'women'][random_int(0, 1)];
        $num = random_int(1, 99);

        return "https://randomuser.me/api/portraits/{$gender}/{$num}.jpg";
    }

    /**
     * Download image from URL and store in public disk, return the storage path.
     */
    public static function downloadAndStore(string $url, string $directory, string $prefix = 'img'): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);
            if (! $response->successful()) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "{$prefix}-".uniqid().'.'.$extension;
            $path = "{$directory}/{$filename}";

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Fallback when API is unavailable - use direct portrait URLs.
     */
    protected static function fallbackUsers(int $count): array
    {
        $users = [];
        for ($i = 0; $i < $count; $i++) {
            $gender = ['male', 'female'][$i % 2];
            $g = $gender === 'male' ? 'men' : 'women';
            $num = ($i % 99) + 1;
            $users[] = [
                'name' => ['first' => 'User', 'last' => (string) ($i + 1)],
                'email' => 'user'.($i + 1).'@example.com',
                'phone' => '+234'.rand(7000000000, 7999999999),
                'picture' => [
                    'large' => "https://randomuser.me/api/portraits/{$g}/{$num}.jpg",
                ],
            ];
        }

        return $users;
    }
}
