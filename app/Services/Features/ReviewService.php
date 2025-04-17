<?php

namespace App\Services\Features;

use App\Http\Resources\ReviewResource;
use App\Interfaces\ReviewServiceInterface;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewService implements ReviewServiceInterface
{
    /**
     * @return Builder
     */
    public function modelQuery(): Builder
    {
        return Review::query();
    }

    /**
     * @return array
     */
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

    /**
     * @param $request
     * @return array
     */
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

    /**
     * @param $carId
     * @return array
     */
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

    /**
     * @param $request
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function updateReview($request, $id): array
    {
        $review = $this->modelQuery()->where('id', $id)->first();
        if(!is_null($review)){
            $review->is_public = $request['is_public'] ?? $review->is_public;
            $review->status = $request['status'] ?? $review->status;
            $review->save();
            return ['review' => $review, 'message' => 'Review updated successfully!'];
        }else{
            throw new NotFoundHttpException('The review for id ('.$id.') is not found ');
        }
    }


    /**
     * @param $id
     * @return array
     * @throws AuthorizationException
     * @throws NotFoundHttpException
     */
    public function deleteReview($id): array
    {
        $review = $this->modelQuery()
            ->where('id', $id)
            ->first();
        if(!is_null($review)){
            if (Auth::user()->hasRole('admin')){
                $review->delete();
                $message = 'Review deleted successfully!';
                return ['review' => [], 'message' => $message];
            }
            else{
                throw new AuthorizationException();
            }
        }
        else{
            throw new NotFoundHttpException('The review for id ('.$id.') is not found ');
        }
    }
}
