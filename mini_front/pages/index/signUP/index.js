// pages/index/signUP/index.js
let config = require("../../../config.js");
let WxParse = require('./../../../wxParse/wxParse.js');
let common = require("./../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    inittraninBmBefor:true,
    traninBmBefor: true,
    showBmPop: false,
    messageButton:"报名",
    showBmPop_train: false,
    readStatement: true, //阅读培训声明判断
    readStatementC:false,
    course_one_detail:'',
    time: '获取验证码', //倒计时
    showBtn: true,
    phone: '',
    train_id: '',
    interval: "",
    disPhone: false,
    isAjax:false,
    user_openid:'',
    title:'', //详情标题
    banner:'', // 详情图片
    shengming:'', //培训声明内容
    hasUserInfo:false ,//用户信息授权
    allowPhone:false //用户手机授权
  },
  getIdDetail(id) {
    let _that = this;
    wx.showLoading({
      title: '加载中',
      mask: true,
      success: function () {
        wx.request({
          url: config.trains_show + "/" + id,
          data: {
            r: Math.random(),
            client:1
          },
          method: 'GET',
          success: function (res) {
            let datas = res.data;
            if (datas.code == "200") {
              _that.data.course_one_detail = wx.getStorageSync('course_one_detail')||datas.data;
              wx.setStorage({
                key: 'course_one_detail',
                data: _that.data.course_one_detail,
                success: function (res) {
                  if (datas.data.state == "报名中"){
                    _that.setData({ traninBmBefor: false});
                  }
                  if (datas.data.state == "报名已结束") {
                    _that.setData({ messageButton: '报名已结束', traninBmBefor: true, readStatement: true,  readStatementC: false });
                  }
                  if (datas.data.state == "报名未开始") {
                    _that.setData({ messageButton: '报名未开始', traninBmBefor: true, readStatement: true,  readStatementC: false });
                  }
                  if (datas.data.state == "培训结束" || datas.data.state == "培训中" || datas.data.state == "培训已结束") {
                    _that.setData({ messageButton: '报名已结束', traninBmBefor: true, readStatement: true, readStatementC: false });
                  }
                  if (wx.getStorageSync('read_allow_lists').includes(id)) {
                    _that.setData({ inittraninBmBefor: false, traninBmBefor: false, readStatement: false, readStatementC: true, hasUserInfo: true});
                  }

                  /**
                   * html解析示例
                   */
                 
                  _that.setData({ title: datas.data.title, banner: datas.data.banner, shengming: datas.data.shengming});
                  if (datas.data.desc) {
                    WxParse.wxParse('article', 'html', datas.data.desc, _that);
                  }
                  if (datas.data.shengming) {
                    WxParse.wxParse('article2', 'html', datas.data.shengming, _that);
                  }
                  setTimeout(function () {
                    wx.hideLoading();
                  }, 1000)
                }
              })
            }
          }
        })
      },
      fail:function(){
        setTimeout(function () {
          wx.hideLoading();
        }, 1000)
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(option) {
    let _that = this;
    wx.showShareMenu({
      withShareTicket: true,
      success:function(ers){
        if (option.train_id) {
          _that.data.train_id = option.train_id;
          _that.getIdDetail(option.train_id);
        } else {
          common.progressTips('活动参数错误')
        }
        if (wx.getStorageSync('user_save_infos') && wx.getStorageSync('user_save_infos').openid){
          _that.setData({ user_openid: wx.getStorageSync('user_save_infos').openid });
        }
        if (wx.getStorageSync('user_save_phone')){
          _that.setData({ allowPhone:true });
        }
      }
    });
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              _that.setData({ hasUserInfo: true });
            }
          })
        }
      }
    })
    if (wx.getStorageSync('user_save_infos').mobile) {
      this.setData({ allowPhone: true });
    }
  },

  showContent: function() {
    if(this.data.messageButton=="报名"){
      this.setData({
        readStatement: false
      });
    }
    this.setData({
      showBmPop_train: true
    })
  },
  closePhone() {
    clearInterval(this.data.interval);
    this.setData({
      showBmPop: false,
      showBtn: false,
      time: '获取验证码',
      disPhone: false
    });
  },
  closePop() {
    this.setData({
      showBmPop_train: false
    });
  },
  bindGetUserInfo: function(e) {
    let _that=this;
    if (e.detail.userInfo) {
      if (this.data.user_openid){ 
        if (_that.data.course_one_detail.state =="报名已结束"){
          common.progressTips('报名已结束！')
            return false;
          }
        if (_that.data.course_one_detail.state == "报名未开始") {
          common.progressTips('报名未开始！')
          return false;
        }
        if (_that.data.course_one_detail.state == "培训结束") {
          common.progressTips('培训结束！')
          return false;
        }
        if (!this.data.inittraninBmBefor) {
            wx.navigateTo({
              url: '/pages/index/signUP/signUpList/index?train_id=' + this.data.train_id
            })
        } else {
          if (!wx.getStorageSync('read_allow_lists').includes(this.data.train_id)) {
             //没有阅读过 弹出层
            wx.showModal({
              title: "培训声明",
              content: this.data.shengming,
              showCancel: false,
              confirmText: "我已阅读",
              success: function () {
                _that.setData({ inittraninBmBefor: false, traninBmBefor: false, readStatement: false, readStatementC: true, hasUserInfo: true});
                let readAllow = wx.getStorageSync('read_allow_lists') ? wx.getStorageSync('read_allow_lists') + _that.data.train_id : _that.data.train_id;
                wx.setStorage({
                  key: 'read_allow_lists',
                  data: readAllow
                })
              }
            })

          }
        }
      }else{
        wx.login({
          success: function (res) {
            if (res.code) {
              //发起网络请求
              wx.request({
                url: `${config.get_session_key}`,
                header: {
                  'content-type': 'application/x-www-form-urlencoded',
                  'Accept': 'application/json'
                },

                data: {
                  code: res.code,
                  client:1
                },
                success: function (res) {
                  _that.setData({  traninBmBefor: true });
                  let user_save_infos = {};
                  let session_key = res.data.data.session_key;
                  wx.getUserInfo({
                    success: function (resL) {
                      _that.setData({ hasUserInfo: true })
                      wx.request({
                        url: `${config.auth_login}`,
                        method: 'POST',
                        data: {
                          encryptedData: resL.encryptedData,
                          iv: resL.iv,
                          sessionKey: session_key,
                          client:1
                        },
                        success: function (resK) {
                          user_save_infos.openid = resK.data.data.openId;
                          user_save_infos.userid = resK.data.data.user_id;
                          user_save_infos.avatarUrl = resK.data.data.avatarUrl;
                          user_save_infos.nickName = resK.data.data.nickName;
                          user_save_infos.mobile = resK.data.data.mobile;
                          user_save_infos.session_key = session_key;
                          _that.setData({ user_openid: resK.data.data.openId })
                          wx.setStorage({
                            key: 'user_save_infos',
                            data: user_save_infos,
                            success: function (res) {
                              if (!_that.data.inittraninBmBefor) {
                                wx.navigateTo({
                                  url: '/pages/index/signUP/signUpList/index?train_id=' + _that.data.train_id
                                })
                              }else{
                                wx.showModal({
                                  title: "培训声明",
                                  content: _that.data.shengming,
                                  showCancel: false,
                                  confirmText: "我已阅读",
                                  success: function () {
                                    _that.setData({ inittraninBmBefor: false, traninBmBefor: false, readStatement: false, readStatementC: true });
                                    let readAllow = wx.getStorageSync('read_allow_lists') ? wx.getStorageSync('read_allow_lists') + _that.data.train_id : _that.data.train_id;
                                    wx.setStorage({
                                      key: 'read_allow_lists',
                                      data: readAllow
                                    })
                                  }
                                })
                              } 
                              console.log("...");
                            }
                          })
                        }
                      })
                    }
                  })
                },
                fail: function () {
                  common.progressTips('信息获取失败！')
                }
              })
            } else {
              common.progressTips('登录失败！' + res.errMsg)
            }
          }
        });
      }
    }
  },

  getPhoneNumber: function (e) {
    let _that=this
    let user = wx.getStorageSync('user_save_infos');
    if (e.detail.iv){ //手机授权过
      wx.request({
        url: config.user_bind_mobile,
        method: 'POST',
        data: {
          encryptedData: e.detail.encryptedData,
          iv: e.detail.iv,
          sessionKey: user.session_key,
          open_id: user.openid,
          client:1
        },
        success: (res) => {
          let datas = res.data;
          if (datas.code == "200") {
             let phone=datas.bindPhone;
            _that.setData({ allowPhone: true })
            wx.navigateTo({
              url: '/pages/index/signUP/signUpList/index?train_id=' + _that.data.train_id
            })
           // console.log('手机号授权后台记录成功');
          } else {
            common.progressTips('手机号授权后台记录失败');
          }
        },
        fail: function () {
          common.progressTips('出错了！');
        }
      })
    
   
    }
  },
  getInfo(){
    let _that=this;
    if (_that.data.course_one_detail.pre_num<=0){
      common.progressTips('报名人数已满!');
      return false;
    }
    if (!this.data.inittraninBmBefor) {
      wx.navigateTo({
        url: '/pages/index/signUP/signUpList/index?train_id=' + this.data.train_id
      })
    }else{
      if (!wx.getStorageSync('read_allow_lists').includes(this.data.train_id)) {
        //没有阅读过 弹出层
        wx.showModal({
          title: "培训声明",
          content: this.data.shengming,
          showCancel: false,
          confirmText: "我已阅读",
          success: function () {
            _that.setData({ inittraninBmBefor: false, traninBmBefor: false, readStatement: false, readStatementC: true, hasUserInfo: true });
            let readAllow = wx.getStorageSync('read_allow_lists') ? wx.getStorageSync('read_allow_lists') + _that.data.train_id : _that.data.train_id;
            wx.setStorage({
              key: 'read_allow_lists',
              data: readAllow,
              success:function(){
                wx.navigateTo({
                  url: '/pages/index/signUP/signUpList/index?train_id=' + _that.data.train_id
                })
              }
            })
          }
        })

      }
    }
  },
  lookSM(){
    let _that=this;
    wx.showModal({
      title: "培训声明",
      content: _that.data.shengming ? _that.data.shengming : '暂无声明',
      showCancel: false,
      confirmText: "关闭"
    })
  },
  showBmPop() {
    this.setData({
      showBmPop: true
    });
  },
  setHide() {
    this.setData({
      showBmPop: false
    });
  },
  agree() {
  },
  testPhone(e) {
    let phoneReg = /^1\d{10}$/;
    this.setData({
      phone: e.detail.value
    });
    if (phoneReg.test(e.detail.value)) {
      this.setData({
        showBtn: false
      });
    } else {
      this.setData({
        showBtn: true
      });
    }
  },
  timeSE(timeM) {
    // 60s倒计时
    const that = this;
    let currentTime = timeM ? timeM : 60;
    that.data.interval = setInterval(function() {
      currentTime--;
      that.setData({
        time: currentTime + "s",
        disPhone: true
      })
      if (currentTime <= 0) {
        clearInterval(that.data.interval);
        that.setData({
          time: '获取验证码',
          currentTime: 60,
          showBtn: false,
          disPhone: false
        })
      }
    }, 1000);
  },
  getTextNum() {
    const that = this;
    that.setData({
      showBtn: true
    })
    wx.request({
      url: config.send_code,
      data: {
        r: Math.random(),
        apply_phone: that.data.phone,
        client:1
      },
      success: (res) => {
        let datas = res.data;
        if (datas.code == "200") {
          that.timeSE(60);
        }else if (datas.code == "1008") {
          that.timeSE(datas.data.exipre);
        }else{
          that.setData({
            showBtn: false,
            disPhone: false
          });
          common.progressTips(datas.msg);
        }
      },
      fail: (res) => {
        that.setData({
          showBtn: false,
          disPhone: false
        })
      }
    })
  },
  formSubmit: function(e) {
    const _that = this;
    let eV = e.detail.value;
    eV.client=1;
    let codeReg = /\d{6}/;
    let phoneReg = /^1\d{10}$/;
    if (!phoneReg.test(eV.apply_phone)) {
      common.progressTips("请输入合法手机号！");
      return false;
    }
    if (!codeReg.test(eV.code)) {
      common.progressTips("请输入正确短信验证码！");
      return false;
    }
    this.setData({
      showBtn: true
    });
    if (this.data.isAjax){
      return false;
    }
    this.setData({
      isAjax: true
    });
    wx.request({
      url: config.check_code,
      method: 'POST',
      data: eV,
      success: (res) => {
        let datas = res.data;
        if (datas.code == "200") {
          _that.closePhone();
          let readAllow = wx.getStorageSync('read_allow_lists') ? wx.getStorageSync('read_allow_lists') + _that.data.train_id : _that.data.train_id;
          wx.setStorage({
            key: 'read_allow_lists',
            data: readAllow,
            success:function(){
              wx.navigateTo({
                url: '/pages/index/signUP/signUpList/index?train_id=' + _that.data.train_id,
              });
            }
          })
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail:function(){
        common.progressTips('出错了！');
      },
      complete:function(){
        _that.setData({
          isAjax: false,
          showBtn:false
        });
      }
    })
  },
  formReset: function() {
    console.log('form发生了reset事件')
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function(options) {
    wx.showShareMenu({
      withShareTicket: true
    });
    let _that = this;
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              _that.setData({ hasUserInfo: true });
            }
          })
        }
      }
    })
    if (wx.getStorageSync('user_save_infos').mobile) {
      this.setData({ allowPhone: true });
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {
    return {
      title: this.data.title,
      imageUrl:this.data.banner,
      path: '/pages/index/signUP/index?train_id=' + this.data.train_id,
      success: function (e) {
        wx.showShareMenu({
          // 要求小程序返回分享目标信息
          withShareTicket: true
        });
      },
      fail: function (e) {
        // 转发失败
      }
    }
  }
})