<?php

namespace App\Console\Commands;

use App\MyUnitEnum;
use App\Models\User;
use Illuminate\Console\Command;

class testBugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:bug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = new User();
        $model->status = MyUnitEnum::ACTIVE;

        dump('We stored: '.$model->getAttributes()['status']);

        dump($model->toArray());
        return 0;
    }
}
