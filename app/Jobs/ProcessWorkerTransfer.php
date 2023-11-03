<?php
namespace App\Jobs;

use App\Models\Worker;
use App\YourTransferLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class ProcessWorkerTransfer implements ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $workers;

    public function __construct(Collection $workers)
    {
        $this->workers = $workers;
    }

    public function handle()
    {
        // foreach ($this->workers as $worker) {
        //     Log::info('Transferring worker ' . $worker->id);
        //     echo json_encode($worker) . PHP_EOL;
        //     // You can also call YourTransferLogic here if needed
        // }
        Log::info('Test job executed');
        echo 'Test job executed' . PHP_EOL;
        // try {
        //     $transferSuccessful = YourTransferLogic::performTransfer($this->worker, $this->worker->unpaid_earning);

        //     if ($transferSuccessful) {
        //         Log::info('Transfer successful for worker ' . $this->worker->id);
        //     } else {
        //         Log::warning('Transfer failed for worker ' . $this->worker->id);
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Error processing worker ' . $this->worker->id . ': ' . $e->getMessage());
        // }
    }
}
