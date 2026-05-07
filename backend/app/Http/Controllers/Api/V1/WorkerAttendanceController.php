<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\StoreAttendanceRequest;
use App\Http\Resources\WorkerAttendanceResource;
use App\Models\WorkerAttendance;
use App\Services\WorkerKhataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkerAttendanceController extends Controller
{
    public function __construct(private WorkerKhataService $service) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', WorkerAttendance::class);

        $query = WorkerAttendance::with('worker')->orderByDesc('date');

        if ($workerId = $request->integer('worker_id')) {
            $query->where('worker_id', $workerId);
        }
        if ($from = $request->date('from')) { $query->whereDate('date', '>=', $from); }
        if ($to   = $request->date('to'))   { $query->whereDate('date', '<=', $to); }

        $rows = $query->paginate($request->integer('per_page', 50));

        return $this->paginatedResponse($rows, WorkerAttendanceResource::class);
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $this->authorize('create', WorkerAttendance::class);

        $row = $this->service->markAttendance($request->validated());

        return $this->successResponse(new WorkerAttendanceResource($row->load('worker')), 'Attendance recorded.', 201);
    }
}
