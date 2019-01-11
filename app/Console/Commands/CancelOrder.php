<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Models\Entry;
use App\Models\PayInfo;
use DB;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancelorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '超时未支付取消订单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lists = Entry::where('status',0)
            ->where('is_paid',0)
            ->where( DB::Raw("UNIX_TIMESTAMP(created_at)"),'<' ,time()-30*60)
            ->get();
        foreach($lists as $list){
            if( !empty($list)){
                Entry::where('id',$list->id)->update([
                    'status'=>2,
                    'remark'=>'30分钟未支付,系统取消订单'
                ]);
            }
        }
    }
}
