<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UmsApi;
use App\Models\Entry;
use Log;

class UmsPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umspost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $umsApi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UmsApi $umsApi)
    {
        parent::__construct();
        $this->umsApi = $umsApi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lists = Entry::groupBy('contract_no')->select('contract_no')->get();
        foreach($lists as $list){
            $order = Entry::where('contract_no',$list->contract_no)
                ->where('is_paid',1)
                ->with(['get_train','get_students'])
                ->whereHas('get_students',function ($query){
                    $query->where('status',4);
                })
                ->orderBy('created_at','asc')
                ->first();
            if( empty($order) ){
                continue;
            }
            //Log::error('order info: '.json_encode($order));
            $this->umsApi->postTrain([
                'contractNum'=>$list->contract_no,
                'paramJson' =>json_encode([
                    'firstTrainBeginTime'=>$order->get_train->train_start,
                    'firstTrainEndTime'=>$order->get_train->train_end,
                    'firstCheckInTime'=>$order->get_students['0']['sign_time'],
                    'firstCertificateIssuingTime'=>$order->get_students['0']['cert_time'],
                ])
            ]);
        }
    }
}
