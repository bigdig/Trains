let config = require("../../../../config.js");
let common = require("../../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    train_id:'',
    cert_id:'',
    student_ids:'',
    arZs: []
  },
  //订单详情
  getOrderDetail(id) {
    const _that = this;
    wx.request({
      url: `${config.get_cert_detail}`,
      data:{
        student_ids: _that.data.student_ids,
        train_id: _that.data.train_id
      },
      method: 'post',
      success: function (res) {
        const datas = res.data;
        if (datas.code == "200") {
          _that.setData({
            arZs: datas.data
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
    if (options.train_id) {
      this.setData({
        train_id: options.train_id,
        cert_id: options.id,
        student_ids: options.students_id,
      });

      
    }
     this.getOrderDetail();
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