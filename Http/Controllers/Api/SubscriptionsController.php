<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Modules\Laralite\Http\Requests\SaveSubscription;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\SubscriptionService;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionsController extends Controller
{
    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    public function __construct(SettingsService $settingsService, SubscriptionService $subscriptionService)
    {
        $this->settingsService = $settingsService;
        $this->subscriptionService = $subscriptionService;
    }

    public function get(Request $request)
    {
        $subscriptions = Subscription::query();
        $perPage = $request->get('perPage', 10);

        if ($request->get('all') === 'true') {
            return $subscriptions->get();
        }

        if ($request->input('filter') !== 'null') {
            $subscriptions
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $subscriptions->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $subscriptions->with('prices')->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Subscription::where('id', '=', $id)->firstOrFail()->with(['prices']);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get subscription',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function save(SaveSubscription $request, $id = null): JsonResponse
    {
        $subscriptionRequest = [
            'name' => $request->input('name'),
            'price' =>  $request->input('price'),
            'description' =>  $request->input('description'),
            'default_credit_amount' => $request->input('default_credit_amount'),
            'default_initial_credit_amount' => $request->input('default_initial_credit_amount'),
            'image' => '',
        ];

        if ($id) {
            $subscriptionRequest['id'] = $id;
        }

        try {
            $subscription = $this->subscriptionService->save($subscriptionRequest);

            Log::info('Saved subscription', [
                'request' => $request->all(),
                'subscription' => $subscription,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new subscription',
                'data' => [
                    'permission' => $subscription,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create subscription', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create subscription',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $subscription = Subscription::where('id', '=', $id)->firstOrFail();
            $this->subscriptionService->deleteSubscription($subscription);

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete subscription',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
