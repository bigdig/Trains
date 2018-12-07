// config.js
/**
 * 小程序后端接口配置文件
 */
 var host = "";
// var host = "";
var config = {
  //获取sessionKey
  get_session_key: host + '/api/get_session_key',
  //登录
  auth_login: host + '/api/auth_login',
  //手机授权
  user_bind_mobile: host + '/api/user_bind_mobile',
  //首页
  trains: host + '/api/trains',
  //首页列表详情
  trains_show: host + '/api/trains_show',
  //报名培训声明，获取短信验证码
  send_code: host + '/api/send_code',
  //输入验证码，提交报名
  check_code: host + '/api/check_code',
  //通过合同号获取园所名称
  check_contract: host + '/api/check_contract',
  //添加学员
  save_nursery_students: host + '/api/save_nursery_students',
  //更换培训人员
  update_order_students: host + '/api/update_order_students',
  //学员编辑
  nursery_students_edit: host + '/api/nursery_students_edit',
  //学员更新
  nursery_students_update: host + '/api/nursery_students_update',
  //上传图片
  upload_image: host + '/api/upload_image',
  //编辑页获取培训学员列表
  nursery_students: host + '/api/nursery_students',
  //编辑页获取培训学员列表-新增学员确认
  save_apply_students: host + '/api/save_apply_students',
  //报名详情删除培训学员
  apply_students_del: host + '/api/apply_students_del',
  //提交课程报名学员
  save_order: host + '/api/save_order',
  //获取订单学员
  order_students: host + '/api/order_students',
  //获取报名人添加的非订单学员
  get_not_order_students: host + '/api/get_not_order_students',
  //订单详情
  order_detail: host + '/api/order_detail',
  //全部，待支付
  get_orders: host + '/api/get_order_by_phone',
  //取消报名
  cancel_order: host + '/api/cancel_order',
  //删除取消的报名
  del_order: host + '/api/del_order',
  //去支付
  go_pay: host + '/api/go_pay',
  //发送模板消息
  send_template:host+'/api/send_template',
  //订单重新审核
  activate_order:host+'/api/activate_order',
  //培训通知设置
  train_setting: host + '/api/train_setting',
  //培训证书
  cert:host+'/api/cert',
  //培训证书详情
  order_cert:host+'/api/order_cert',
  //培训证书详情综合版
  get_cert_by_phone: host + '/api/get_cert_by_phone',
  get_cert_detail: host + '/api/cert_detail',
  //职位查询
  profess:host+'/api/profess'
};
module.exports = config