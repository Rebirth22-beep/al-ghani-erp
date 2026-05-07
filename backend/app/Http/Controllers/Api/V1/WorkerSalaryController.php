<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreSalaryRequest;
use App\Http\Resources\WorkerSalaryResource;
use App\Models\WorkerSalary;
use App\Services\WorkerKhataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerSalaryController extends Controller
{
    public function __construct(private WorkerKhataService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', WorkerSalary::class);

        $query = WorkerSalary::with('worker')->orderByDesc('paid_at');

        if ($workerId = $request->integer('worker_id')) {
            $query->where('worker_id', $workerId);
        }
        if ($month = $request->string('month')->toString()) {
            $query->where('month', $month);
        }

        $rows = $query->paginate($request->integer('per_page', 50));

        return $this->paginatedResponse($rows, WorkerSalaryResource::class);
    }

    public function store(StoreSalaryRequest $request): JsonResponse
    {
        $this->authorize('create', WorkerSalary::class);

        $row = $this->service->paySalary($request->validated());

        return $this->successResponse(new WorkerSalaryResource($row->load('worker')), 'Salary recorded.', 201);
    }
}
