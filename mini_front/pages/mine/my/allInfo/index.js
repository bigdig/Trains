// pages/my/allInfo/index.js
let config = require("../../../../config.js");
let common = require("../../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    times: "",
    status: "",
    train_info: '',
    setIntvalTime: '',
    isShowPay: false,
    isOther:false,
    noPass: false,
    train_id: '',
    order_id: '',
    ico_status: 'wait',
    contract_no:'',
    isAjax: false,
    change:'', //是否更换培训人员
    animationData: {},
    chooseChange: false,
    studentList:[]
  },
  goIndex(){
    wx.switchTab({
      url: '/pages/index/index'
    })
  },

  getChangeInfo(e) { //更换培训人员跳转页面
  let _that=this;
  wx.showModal({
    title: "提示",
    content: "更新培训人员需重新审核，请你在培训开始前预留出一定时间",
    showCancel: false,
    confirmText: "知道了",
    success: function () {
        wx.navigateTo({
          url: '/pages/index/signUP/signUpList/editInfo/index?change=true&order_id=' + _that.data.order_id + '&userId=' + e.currentTarget.dataset.id + '&train_id=' + _that.data.train_id + '&contract_no=' + _that.data.contract_no
        })
    }
  })
},
  goChangePeople: function (e) { //更换培训人员底部弹出框
    // 用that取代this，防止不必要的情况发生
    var that = this;
    // 创建一个动画实例
    var animation = wx.createAnimation({
      // 动画持续时间
      duration: 500,
      // 定义动画效果，当前是匀速
      timingFunction: 'linear'
    })
    // 将该变量赋值给当前动画
    that.animation = animation
    // 先在y轴偏移，然后用step()完成一个动画
    animation.translateY(200).step()
    // 用setData改变当前动画
    wx.request({
      url: config.order_students + '/' + that.data.order_id,
      method: "GET",
      data:{
        client:1
      },
      success: function (res) {
        let datas = res.data;
        if (datas.code == "200") {
            let arr = [];
            Object.keys(datas.data).forEach(v => {
              arr.push(datas.data[v].get_nursery_user);
            })
            that.setData({
              studentList: arr
            });
          
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function () {
        common.progressTips('出错了！');
      }
    })



    that.setData({
      // 通过export()方法导出数据
      animationData: animation.export(),
      // 改变view里面的Wx：if
      chooseChange: true
    })
    // 设置setTimeout来改变y轴偏移量，实现有感觉的滑动
    setTimeout(function () {
      animation.translateY(0).step()
      that.setData({
        animationData: animation.export()
      })
    }, 200)
  },
  hideModal: function (e) {
    var that = this;
    var animation = wx.createAnimation({
      duration: 1000,
      timingFunction: 'linear'
    })
    that.animation = animation
    animation.translateY(200).step()
    that.setData({
      animationData: animation.export()

    })
    setTimeout(function () {
      animation.translateY(0).step()
      that.setData({
        animationData: animation.export(),
        chooseChange: false
      })
    }, 200)
  },

  //编辑
  goEdit(e) {
    let url = '/pages/index/signUP/signUpList/addInfo/index?edit_id=' + e.currentTarget.dataset.editid + "&source_url=/pages/mine/my/allInfo/index&train_id=" + this.data.train_id + '&order_id=' + this.data.order_id + '&contract_no=' + this.data.contract_no;
    wx.navigateTo({
      url: url
    })
  },
  toWechatPay: function (order_id, train_id) {
    var that = this
    wx.request({
      url: config.go_pay,
      method: "post",
      data: {
        order_id: order_id,
        client:1
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        let datas_d = res.data;
        let order = datas_d;
        wx.requestPayment({
          timeStamp: order.timeStamp,
          nonceStr: order.nonceStr,
          package: order.package,
          signType: 'MD5',
          paySign: order.paySign,
          success: function (res) {
            common.progressTips('支付成功！');
            wx.request({
              url: config.send_template,
              method: "get",
              data: {
                order_id: order_id,
                prepay_id: order.prepay_id,
                client:1
              }
            })
          },
          fail: function (res) {
            common.progressTips('支付失败！');
          },
          complete: function (res) {
            setTimeout(function () {
              wx.navigateTo({
                url: `/pages/mine/my/allInfo/index?order_id=${order_id}&train_id=${train_id}&contract_no=${that.data.contract_no}`,
              })
            }, 2000);
          }
        })
      }
    })
  },
  goPay() {
    if (this.data.isAjax) {
      return false;
    }
    this.data.isAjax = true;
    this.toWechatPay(this.data.order_id, this.data.train_id);
  },
  //订单状态
  orderStatus(o_s) {
    let _that = this;
    let s_n = '审核中';
    switch (o_s) {
      case 0:
        s_n = '已支付';
        _that.setData({
          noPass: false,
          ico_status: 'success'
        });
        break;
      case 1:
        s_n = '已退款';
        break;
      case 2:
        s_n = '已取消';
        _that.setData({
          noPass: false,
          ico_status: 'success'
        });
        break;
      case 3:
        s_n = '审核中';
        break;
      case 4:
        s_n = '审核未通过';
        _that.setData({
          noPass: true,
          ico_status: 'error'
        });
        break;
      case 5:
        s_n = '部分审核'
        break;
      case 6:
        s_n = '已审核';
        _that.setData({
          noPass: false,
          ico_status: 'success'
        });
        break;
      case 7:
        s_n = '已完成';
        _that.setData({
          noPass: false,
          ico_status: 'success'
        });
        break;
    }
    this.setData({
      status: s_n
    })
  },
  //提交再次审核
  goSubmit(){
    const _that = this;
    wx.request({
      url: `${config.activate_order}/${_that.data.order_id}`,
      method: 'GET',
      data:{
        client:1
      },
      success: function (res) {
        const datas = res.data;
        if (datas.code == "200") {
          common.progressTips("提交成功!");
          setTimeout(function () {
            wx.navigateTo({
              url: `/pages/mine/my/index`,
            })
          }, 2000);
         
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function () {
        common.progressTips("出错了！");
      }
    })
  },
  //倒计时转化
  timeF(times) {
    let _that = this;
    clearInterval(_that.data.setIntvalTime);
    _that.data.setIntvalTime = setInterval(function () {
      if (times > 0) {
        times--;
        let h = Number.parseInt(times / 3600);
        h = h >= 10 ? h : `0${h}`;
        let m = Number.parseInt(times / 60) - h * 60;
        console.log(h, m)
        m = m >= 10 ? m : `0${m}`;
        let s = times - h * 3600 - m * 60;
        s = s >= 10 ? s : `0${s}`;
        let time_of = `${h}:${m}:${s}`;
        _that.setData({
          times: time_of
        });
      } else {
        clearInterval(_that.data.setIntvalTime);
        _that.setData({
          times: '',
          isShowPay: false
        });
        _that.orderStatus(2);
      }
    }, 1000)
  },

  //订单详情
  getOrderDetail(order_id) {
    const _that = this;
    wx.request({
      url: `${config.order_detail}/${order_id}`,
      data:{
        client:1
      },
      method: 'GET',
      success: function (res) {
        const datas = res.data;
        if (datas.code == "200") {
          const dd = datas.data;
          _that.setData({
            contract_no: dd.contract_no
          });
          if (dd.is_paid) {
            //已支付
            _that.orderStatus(dd.status);
          } else {
            //未支付
            if (dd.status==2){
              _that.orderStatus(dd.status);
            }else{
              if (dd.surplus > 0) {
                //剩余支付倒计时  
                _that.setData({
                  isShowPay: true
                });
                _that.timeF(dd.surplus);
              }
            }
          }
          _that.setData({
            train_info: dd
          })
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function () {
        common.progressTips("出错了！");
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    if (options.order_id) {
      this.setData({
        train_id: options.train_id,
        order_id: options.order_id
      });
      if(options.change){
        this.setData({
          change: true
        });
      }
      if(options.other){
        this.setData({
          isOther: true
        });
      }
      this.getOrderDetail(options.order_id);
    }
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
    this.hideModal();
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