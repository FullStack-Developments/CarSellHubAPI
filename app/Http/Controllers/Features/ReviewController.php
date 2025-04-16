<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\StoreReviewRequest;
use App\Services\Features\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(private readonly ReviewService $reviewService) {}

    public function indexPublicReviews():JsonResponse{
        $response = $this->reviewService->showPublicReviews();
        return $this->sendSuccess($response['review'], $response['message'],$response['code']);
    }
    public function store(StoreReviewRequest $request):JsonResponse
    {
        $response = $this->reviewService->createReview($request);
        return $this->sendSuccess($response['review'], $response['message']);
    }
    public function indexReviewsByCarId($carId):JsonResponse{
        $response = $this->reviewService->showReviewsByCarId($carId);
        return $this->sendSuccess($response['review'], $response['message'], $response['code']);
    }
}
