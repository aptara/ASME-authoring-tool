<?php

namespace App\Console\Commands;

use App\Modules\Lock\Repositories\LockRepository;
use Illuminate\Console\Command;

class LockExpiry extends Command
{
    protected $signature = 'expire:lock';

    protected $description = 'Check if lock is expired';

    /**
     * @var LockExpiry
     */
    private $lockrepo;

    public function __construct(LockRepository $lockRepository)
    {
        parent::__construct();
        $this->lockrepo = $lockRepository;
    }

    public function handle()
    {
        $this->lockrepo->lockExpired();
    }


}
