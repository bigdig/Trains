<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use App\Models\WxUser;
use Log;

class LoginController extends Controller
{
    private $config;

    function __construct()
    {
        $this->config=config('wechat.mini');
    }

    //
    public function get_session_key(Request $request){
		$client = $request->get('client',1);
        $miniProgram = Factory::miniProgram($this->config[$client]);

        $code =$request->get('code');
        $session = $miniProgram->auth->session($code);
        return response()->json(['code'=>'200','msg'=>'ok','data'=>$session]);
    }
    public function auth_login(Request $request){
        $encryptedData = $request->get('encryptedData','');
        $iv            = $request->get('iv','');
        $sessionKey    = $request->get('sessionKey','');
		$client        = $request->get('client',1);
        $miniProgram   = Factory::miniProgram($this->config[$client]);
        $data =$miniProgram->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        $id = WxUser::updateOrCreate(
            ['open_id'=>$data['openId']],
            ['nick_name'=>$this->removeEmoji($data['nickName']),'avatar_url'=>$data['avatarUrl'],'city'=>$data['city'],'country'=>$data['country'],'province'=>$data['province'],'gender'=>$data['gender'],'app_id'=>$this->config[$client]['app_id']]
        )->id;
		//Log::error('mobile:'.$data['openId']);
		$mobile = WxUser::where('open_id',$data['openId'])->value('mobile');
		//Log::error('mobile:'.$mobile);
        return response()->json([
            'code'=>'200',
            'msg'=>'ok',
            'data'=>['openId'=>$data['openId'],'nickName'=>$data['nickName'],'avatarUrl'=>$data['avatarUrl'],'user_id'=>$id,'mobile'=>$mobile]
        ]);
    }
    
    public function bind_phone(Request $request){
        $encryptedData = $request->get('encryptedData','');
        $iv            = $request->get('iv','');
        $sessionKey    = $request->get('sessionKey','');
        $open_id       = $request->get('open_id','');

        $client = $request->get('client',1);
        $miniProgram = Factory::miniProgram($this->config[$client]);
        $data =$miniProgram->encryptor->decryptData($sessionKey, $iv, $encryptedData);
		Log::error('bind phone:'.json_encode($data));
		if(isset($data['phoneNumber']) && WxUser::where('open_id',$open_id)->update(['mobile'=>$data['phoneNumber']]) ){
            return ['code'=>200,'msg'=>'ok','isBindPhone'=>1,'bindPhone'=>$data['phoneNumber']];
        }else{
            return ['code'=>0,'msg'=>'绑定失败','isBindPhone'=>0];
        }

    }
	/*
    public function bind_contract_no(Request $request){
        $open_id    = $request->get('open_id','');
        $contract_no= $request->get('contract_no','');

        if( WxUser::where('open_id',$open_id)->update(['contract_no'=>$contract_no]) ){
            return ['code'=>200,'msg'=>'ok'];
        }
    }
    */
	//处理微信昵称表情符号
	private function removeEmoji($nickname) {
		$clean_text = "";
		// Match Emoticons
		$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clean_text = preg_replace($regexEmoticons, '', $nickname);

		// Match Miscellaneous Symbols and Pictographs
		$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clean_text = preg_replace($regexSymbols, '', $clean_text);

		// Match Transport And Map Symbols
		$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clean_text = preg_replace($regexTransport, '', $clean_text);

		// Match Miscellaneous Symbols
		$regexMisc = '/[\x{2600}-\x{26FF}]/u';
		$clean_text = preg_replace($regexMisc, '', $clean_text);

		// Match Dingbats
		$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
		$clean_text = preg_replace($regexDingbats, '', $clean_text);

		return $clean_text;
	}
}
