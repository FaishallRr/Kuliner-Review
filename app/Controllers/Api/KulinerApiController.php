<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PlaceModel;

/**
 * KulinerApiController: API endpoint publik untuk data kuliner.
 * GET /api/kuliner?lat=x&lng=y&radius=km
 */
class KulinerApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Mengambil daftar tempat kuliner terdekat berdasarkan koordinat dan radius.
     * Menggunakan rumus Haversine untuk menghitung jarak.
     */
    public function index()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $radius = $this->request->getGet('radius') ?? 10;

        if ($lat === null || $lng === null) {
            return $this->fail('Parameter lat dan lng wajib diisi.', 400);
        }

        $lat = (float) $lat;
        $lng = (float) $lng;
        $radius = max(1, min((float) $radius, 100));

        $placeModel = new PlaceModel();
        $places = $placeModel->select('places.*, categories.name AS category_name, users.full_name AS contributor_name')
            ->join('categories', 'categories.id = places.category_id')
            ->join('users', 'users.id = places.user_id')
            ->where('places.status', 'approved')
            ->where('places.latitude IS NOT', null)
            ->where('places.longitude IS NOT', null)
            ->findAll();

        $results = [];
        foreach ($places as $place) {
            $distance = $this->haversine($lat, $lng, (float) $place['latitude'], (float) $place['longitude']);
            if ($distance <= $radius) {
                $place['distance_km'] = round($distance, 2);
                $place['image_url'] = ! empty($place['image']) ? base_url('uploads/' . $place['image']) : null;
                $results[] = $place;
            }
        }

        usort($results, function ($a, $b) {
            return $a['distance_km'] <=> $b['distance_km'];
        });

        return $this->respond([
            'success' => true,
            'count'   => count($results),
            'radius_km' => $radius,
            'data'    => $results,
        ]);
    }

    /**
     * Rumus Haversine untuk menghitung jarak antara dua titik koordinat (km).
     */
    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}