<?php

namespace App\Services\Features;

use App\Http\Resources\ReviewResource;
use App\Interfaces\ReviewServiceInterface;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewService implements ReviewServiceInterface
{
    public function modelQuery(): Builder
    {
        return Review::query();
    }
    public function showPublicReviews(): array
    {
        $review = $this->modelQuery()
            ->isApproved()
            ->isPublic()
            ->selectColumns()
            ->withCarInfos()
            ->latest()
            ->paginate(10);

        if($review->count() == 0) {
            $message = 'There is no reviews yet.';
            $review = [];
            $code = 404;
        }
        else{
            $message = 'Reviews indexes successfully!';
            $code = 200;
        }
        return ['review' => $review, 'message' => $message, 'code' => $code];
    }
    public function createReview($request): array
    {
        $review = $this->modelQuery()
            ->create([
                'car_id' => $request['car_id'] ?? null,
                'full_name' => $request['full_name'],
                'phone_number' => $request['phone_number'],
                'email' => $request['email'],
                'subject' => $request['subject'],
                'comment' => $request['comment'],
            ]);
        $review->refresh();
        $review = new ReviewResource($review);
        $message = 'Review created successfully!';
        return ['review' => $review, 'message' => $message];
    }
    public function showReviewsByCarId($carId):array{
        $car = Car::query()->find($carId);
        if(!is_null($car)){
            $review = $this->modelQuery()
                ->where('car_id', $carId)
                ->isApproved()
                ->isPublic()
                ->withCarInfos()
                ->selectColumns()
                ->latest()
                ->paginate();
            if($review->count() == 0) {
                $message = 'There is no reviews for this car yet.';
                $review = [];
                $code = 404;
            }
            else{
                $message = 'Reviews indexes successfully!';
                $code = 200;
            }
            return ['review' => $review, 'message' => $message, 'code' => $code];
        }
        else{
            throw new NotFoundHttpException('The car for id ('.$carId.') is not found ');
        }
    }

    /**
     * @return array
     */
    public function showReviewsByCarSeller(): array
    {
        $review = $this->modelQuery()
            ->reviewsByUser()
            ->isApproved()
            ->withCarInfos()
            ->paginate(10);

        if($review->count() == 0) {
            $message = 'There is no reviews for your cars.';
            $review = [];
            $code = 404;
        }else {
            $code = 200;
            $message = 'Reviews indexes successfully!';
        }
        return ['review' => $review, 'message' => $message, 'code' => $code];
    }
}
