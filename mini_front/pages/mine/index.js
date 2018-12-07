// pages/mine/index.js
let config = require("../../config.js");
Page({
  data: {
    signUp: '我的报名',
    certificates: '培训证书',
    userInfo: '',
    hasUserInfo: false,
    allowPhone: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },
  //事件处理函数
  onLoad: function () {
    let _that=this;
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              _that.setData({ hasUserInfo: true,userInfo:res.userInfo });
            }
          })
        }
      }
    })
    if (wx.getStorageSync('user_save_infos').mobile) {
      this.setData({ allowPhone: true });
    }
  },
  onShow: function () {
    let _that = this;
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          wx.getUserInfo({
            success: function (res) {
              _that.setData({ hasUserInfo: true, userInfo: res.userInfo });
            }
          })
        }
      }
    })
    if (wx.getStorageSync('user_save_infos').mobile) {
      this.setData({ allowPhone: true });
    }
  },
  bindGetUserInfo: function (e) {
    let _that = this;
    let cert = e.currentTarget.dataset.cert;
    if (e.detail.userInfo){
      wx.login({
        success: function (res) {
          let user_save_infos = {};
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
                client: 1
              },
              success: function (res) {
                let user_save_infos = {};
                let session_key = res.data.data.session_key;
                wx.getUserInfo({
                  success: function (resL) {
                    wx.request({
                      url: `${config.auth_login}`,
                      method: 'POST',
                      data: {
                        encryptedData: resL.encryptedData,
                        iv: resL.iv,
                        sessionKey: session_key,
                        client: 1
                      },
                      success: function (resK) {
                        user_save_infos.openid = resK.data.data.openId;
                        user_save_infos.userid = resK.data.data.user_id;
                        user_save_infos.avatarUrl = resK.data.data.avatarUrl;
                        user_save_infos.nickName = resK.data.data.nickName;
                        user_save_infos.mobile = resK.data.data.mobile;
                        user_save_infos.session_key = session_key;
                        _that.setData({
                          userInfo: user_save_infos,
                          hasUserInfo: true
                        })
                        wx.setStorage({
                          key: 'user_save_infos',
                          data: user_save_infos,
                          success: function (res) {
                            if (cert!='false'){
                              _that.goMyIndex()
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
                console.log('信息获取失败！')
              }
            })
          } else {
            console.log('登录失败！' + res.errMsg)
          }
        }
      });
    }


  },
  getPhoneNumber: function (e) {
    let _that = this;
    if (e.detail.iv) { //手机授权过
      wx.login({
        success: function (res) {
          let user_save_infos = {};
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
                client: 1
              },
              success: function (res) {
                let user_save_infos = {};
                let session_key = res.data.data.session_key;
                wx.getUserInfo({
                  success: function (resL) {
                    wx.request({
                      url: `${config.auth_login}`,
                      method: 'POST',
                      data: {
                        encryptedData: resL.encryptedData,
                        iv: resL.iv,
                        sessionKey: session_key,
                        client: 1
                      },
                      success: function (resK) {
                        user_save_infos.openid = resK.data.data.openId;
                        user_save_infos.userid = resK.data.data.user_id;
                        user_save_infos.avatarUrl = resK.data.data.avatarUrl;
                        user_save_infos.nickName = resK.data.data.nickName;
                        user_save_infos.mobile = resK.data.data.mobile;
                        user_save_infos.session_key = session_key;
                        _that.setData({
                          userInfo: user_save_infos,
                          hasUserInfo: true
                        })
                        wx.setStorage({
                          key: 'user_save_infos',
                          data: user_save_infos,
                          success: function (res) {
                            wx.request({
                              url: config.user_bind_mobile,
                              method: 'POST',
                              data: {
                                encryptedData: e.detail.encryptedData,
                                iv: e.detail.iv,
                                sessionKey: user_save_infos.session_key,
                                open_id: user_save_infos.openid,
                                client: 1
                              },
                              success: (res) => {
                                let datas = res.data;
                                if (datas.code == "200") {
                                  let phone = datas.bindPhone;
                                  _that.setData({ allowPhone: true })
                                  _that.goCertificate();
                                } else {
                                  common.progressTips('手机号授权后台记录失败');
                                }
                              },
                              fail: function () {
                                common.progressTips('出错了！');
                              }
                            })
                          }
                        })
                      }
                    })
                  }
                })
              },
              fail: function () {
                console.log('信息获取失败！')
              }
            })
          } else {
            console.log('登录失败！' + res.errMsg)
          }
        }
      });
    }
  },
  goMyIndex(e) {
    wx.navigateTo({
      url: '/pages/mine/my/index'
    })
  },
  goCertificate(e) {
    wx.navigateTo({
      url: '/pages/mine/certificate/index'
    })
  }
})
