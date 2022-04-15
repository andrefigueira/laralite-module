<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Illuminate\Validation\Rule;
use Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Services\Subscription as SubscriptionService;
use Modules\Laralite\Services\SettingsService;
use Stripe\StripeClient;
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
        $perPage = $request->get('perPage', 1);

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

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $subscriptionRequest = [
            'name' => $request->get('name'),
            'price' =>  $request->get('price'),
            'description' =>  $request->get('description'),
            'image' => '',
        ];

        if ($id = $request->get('id')) {
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

    public function save(Request $request, $id = null): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $subscriptionRequest = [
            'name' => $request->get('name'),
            'price' =>  $request->get('price'),
            'description' =>  $request->get('description'),
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

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {
            $subscription = Subscription::where('id', '=', $id)->firstOrFail();

            $subscription->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'price' => $request->get('price'),
                'image' => '',
            ]);

            Log::info('Updated subscription', [
                'request' => $request->all(),
                'subscription' => $subscription,
            ]);

            return $subscription;
        } catch (\Throwable $exception) {
            Log::error('Failed to update subscription', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update subscription',
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

            $subscription->delete();

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
