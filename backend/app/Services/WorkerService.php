<?php

namespace App\Services;

use App\Models\Worker;

/**
 * Thin Worker CRUD service. Owns audit logging for create/update/delete so
 * controllers stay free of workflow logic (SKILL.md §4).
 */
class WorkerService
{
    public function __construct(private AuditLogService $auditLog) {}

    public function create(array $data): Worker
    {
        $worker = Worker::create($data);
        $this->auditLog->log('create', Worker::class, $worker->id, [], $worker->toArray());
        return $worker;
    }

    public function update(Worker $worker, array $data): Worker
    {
        $before = $worker->toArray();
        $worker->update($data);
        $this->auditLog->log('update', Worker::class, $worker->id, $before, $worker->fresh()->toArray());
        return $worker->fresh();
    }

    public function delete(Worker $worker): void
    {
        $worker->delete();
        $this->auditLog->log('delete', Worker::class, $worker->id, [], []);
    }
}
