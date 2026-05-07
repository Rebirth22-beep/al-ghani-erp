<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreWorkerRequest;
use App\Http\Requests\Worker\UpdateWorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use App\Services\WorkerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function __construct(private WorkerService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Worker::class);

        $workers = Worker::when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->paginate($request->integer('per_page', 20));

        return $this->paginatedResponse($workers, WorkerResource::class);
    }

    public function store(StoreWorkerRequest $request): JsonResponse
    {
        $this->authorize('create', Worker::class);

        $worker = $this->service->create($request->validated());

        return $this->successResponse(new WorkerResource($worker), 'Worker created.', 201);
    }

    public function show(Worker $worker): JsonResponse
    {
        $this->authorize('view', $worker);
        return $this->successResponse(new WorkerResource($worker));
    }

    public function update(UpdateWorkerRequest $request, Worker $worker): JsonResponse
    {
        $this->authorize('update', $worker);

        $worker = $this->service->update($worker, $request->validated());

        return $this->successResponse(new WorkerResource($worker), 'Worker updated.');
    }

    public function destroy(Worker $worker): JsonResponse
    {
        $this->authorize('delete', $worker);

        $this->service->delete($worker);
        return $this->successResponse(message: 'Worker deleted.');
    }
}
