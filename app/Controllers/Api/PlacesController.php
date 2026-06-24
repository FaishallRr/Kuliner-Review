<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PlaceModel;
use App\Models\ReviewModel;

class PlacesController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $q = $this->request->getGet('q');
        $category = $this->request->getGet('category');
        $tag = $this->request->getGet('tag');
        $page = max(1, (int)$this->request->getGet('page') ?: 1);
        $perPage = min(50, max(5, (int)$this->request->getGet('per_page') ?: 10));

        $model = new PlaceModel();

        $builder = $model->builder();
        $builder->where('places.status', 'approved');

        if ($q) {
            $builder->groupStart()
                ->like('places.name', $q)
                ->orLike('places.description', $q)
                ->groupEnd();
        }

        if ($category) {
            $builder->where('places.category_id', $category);
        }

        if ($tag) {
            $builder->join('place_tags', 'place_tags.place_id = places.id')
                ->where('place_tags.tag_id', $tag);
        }

        $total = $builder->countAllResults(false);

        $data = $builder->select('places.*')->limit($perPage, ($page -1)*$perPage)->get()->getResultArray();

        $reviewModel = new ReviewModel();
        foreach ($data as &$row) {
            $row['avg_rating'] = $reviewModel->getAverageRating((int)$row['id']);
            $row['review_count'] = $reviewModel->getReviewCount((int)$row['id']);
            if (! empty($row['image'])) {
                $row['image_url'] = base_url('uploads/' . $row['image']);
            } else {
                $row['image_url'] = null;
            }
        }

        return $this->respond([
            'success' => true,
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'data' => $data,
        ]);
    }

    public function show($id = null)
    {
        $model = new PlaceModel();
        $place = $model->where('status', 'approved')->find($id);

        if (! $place) {
            return $this->failNotFound('Place not found or not approved');
        }

        $reviewModel = new ReviewModel();
        $place['avg_rating'] = $reviewModel->getAverageRating((int)$place['id']);
        $place['review_count'] = $reviewModel->getReviewCount((int)$place['id']);
        $place['image_url'] = ! empty($place['image']) ? base_url('uploads/' . $place['image']) : null;

        // tags
        $place['tags'] = $model->getTags($place['id']);

        return $this->respond(['success' => true, 'data' => $place]);
    }

    /**
     * Create a new place via API (auth required).
     */
    public function create()
    {
        $input = $this->request->getJSON(true);

        $rules = [
            'name' => 'required|min_length[2]|max_length[150]',
            'address' => 'required|min_length[5]|max_length[255]',
            'category_id' => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userId = session()->get('api_user_id') ?: null;
        if (! $userId) {
            return $this->fail('Unauthorized', 401);
        }

        $model = new PlaceModel();

        $data = [
            'user_id' => $userId,
            'category_id' => (int)$input['category_id'],
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'address' => $input['address'],
            'latitude' => $input['latitude'] ?? null,
            'longitude' => $input['longitude'] ?? null,
            'status' => 'pending',
        ];

        $model->insert($data);
        $id = $model->getInsertID();

        // sync tags if provided
        if (! empty($input['tags']) && is_array($input['tags'])) {
            $placeTagModel = new \App\Models\PlaceTagModel();
            $placeTagModel->syncTags($id, $input['tags']);
        }

        return $this->respondCreated(['success' => true, 'id' => $id, 'message' => 'Place created (pending approval)']);
    }

    /**
     * Create review for a place via API (auth required).
     */
    public function createReview($placeId)
    {
        $input = $this->request->getJSON(true);

        $rules = [
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userId = session()->get('api_user_id') ?: null;
        if (! $userId) {
            return $this->fail('Unauthorized', 401);
        }

        $reviewModel = new ReviewModel();
        $data = [
            'user_id' => $userId,
            'place_id' => (int)$placeId,
            'rating' => (int)$input['rating'],
            'comment' => $input['comment'] ?? null,
        ];

        try {
            $reviewModel->insert($data);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }

        return $this->respondCreated(['success' => true, 'message' => 'Review submitted']);
    }
}
