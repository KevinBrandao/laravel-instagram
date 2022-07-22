<?php

namespace App\Jobs;

use App\Notifications\PrimeFound;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FindMaxPrime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $limit;
    public $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($limit, $userId)
    {
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $primo = 1;
        for ($num = 1; $num < $this->limit; $num++) {
            for ($div = 2; $div < $num; $div++) {
                if ($num % $div === 0) {
                    break;
                }
            }
            if ($div === $num) {
                $primo = $num;
            }
        }
        $title = 'Sucesso';
        $description = 'O maior Primo é:' . $primo;

        logger()->info('O maior primo é: ' . $primo);
        
        $user = User::find($this->userId);
        $user->notify(
            new PrimeFound($title, $description)
       );
    }
}
