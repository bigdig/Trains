// pages/mine/certificate/index.js

let config = require("../../../config.js");
let common = require("../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    userid: '',
    dataList: [],
    studentsId:''
  },
  getList() {
    wx.showLoading({
      title: '加载中',
      mask: true,
      icon: 'loading',
    })
    let _that = this;
    let user = wx.getStorageSync('user_save_infos').mobile;
    let parmas = {
      "mobile": user
    };
    wx.request({
      url: `${config.get_cert_by_phone}`,
      method: 'post',
      data: parmas,
      success: function (res) {
        if(res.data.code==200){
          _that.setData({
            dataList: [...res.data.data.apply_lists, ...res.data.data.student_lists]
          });
        }else{
          common.progressTips(res.data.msg);
        }
        setTimeout(function () {
          wx.hideLoading();
        }, 500)
      },
      fail: function () {
        common.progressTips("出错了！");
      }
    })
  },
  bindGetUserInfo: function (e) {
    let _that = this;
    if (e.detail.userInfo) {
      this.setData({
        canIUses: false
      });
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
                code: res.code
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
                        sessionKey: session_key
                      },
                      success: function (resK) {
                        user_save_infos.openid = resK.data.data.openId;
                        user_save_infos.userid = resK.data.data.user_id;
                        _that.setData({ userid: resK.data.data.user_id })
                        wx.setStorage({
                          key: 'user_save_infos',
                          data: user_save_infos,
                          success: function (res) {
                            _that.getMyorder();
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

    } else {
      this.setData({
        canIUses: true
      });
    }
  },
  goTrainDetail:function(e){ //培训证书详情
    let _that = this;
    let cert_id = e.currentTarget.dataset.id;
    let train_id = e.currentTarget.dataset.trainid;
    let students_id = e.currentTarget.dataset.studentsid;
    let studentid =[];
    for (let y=0;y<students_id.length;y++){
      studentid.push(students_id[y].student_id);
    }
    let url = '/pages/mine/certificate/detailsInfo/index?train_id=' + train_id + '&id=' + cert_id + '&students_id=' + studentid.join(",");
    wx.navigateTo({
        url: url,
    })
    

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _that = this;
    wx.getSetting({
      success: function (res) {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          _that.data.userid = wx.getStorageSync('user_save_infos').userid;
          _that.getList();
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})