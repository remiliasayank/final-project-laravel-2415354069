<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query("status");
        $query = Subscription::query()->with(["customer", "service"]);

        if ($status !== null) {
            $query->where("status", $status);
        }

        $subscriptions = $query->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "exists:customers,id"],
            "service_id" => ["required", "exists:services,id"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "status" => ["nullable", "string", Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])],
        ]);

        $data["status"] = $data["status"] ?? "trial";
        $subscription = Subscription::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription created successfully",
            "data" => $subscription->load(["customer", "service"]),
        ], 201);
    }

    public function show(int $subscription): JsonResponse
    {
        $subscriptionModel = Subscription::query()->with(["customer", "service"])->find($subscription);

        if (!$subscriptionModel) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Subscription retrieved successfully",
            "data" => $subscriptionModel,
        ]);
    }

    public function update(Request $request, int $subscription): JsonResponse
    {
        $subscriptionModel = Subscription::query()->find($subscription);

        if (!$subscriptionModel) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        $data = $request->validate([
            "customer_id" => ["sometimes", "exists:customers,id"],
            "service_id" => ["sometimes", "exists:services,id"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "status" => ["sometimes", "string", Rule::in(['active', 'inactive', 'trial', 'isolir', 'dismantle'])],
        ]);

        $subscriptionModel->update($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription updated successfully",
            "data" => $subscriptionModel->load(["customer", "service"]),
        ]);
    }

    public function destroy(int $subscription): JsonResponse
    {
        $subscriptionModel = Subscription::query()->find($subscription);

        if (!$subscriptionModel) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
                "errors" => [],
            ], 404);
        }

        $subscriptionModel->delete();

        return response()->json([
            "success" => true,
            "message" => "Subscription deleted successfully",
            "data" => null,
        ]);
    }
}