<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface ReviewServiceInterface
{
    public function modelQuery():Builder;
    public function showPublicReviews():array;
    public function createReview($request):array;
    public function showReviewsByCarId($carId):array;
    public function showReviewsByCarSeller():array;
    public function updateReview($request, $id):array;
    public function deleteReview($id):array;
}
