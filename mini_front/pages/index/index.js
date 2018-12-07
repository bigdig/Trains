// pages/index/index.js
let config = require("../../config.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    message: 'Hello MINA!',
    list: [],
    pay_type_list: [],
    hasMoreTrain:false,
    perPage:5,
    page:1,
    hasmoreData: true,
    hiddenloading: true

  },
  compare(property) {
    return function(obj1, obj2) {
      var value1 = obj1[property];
      var value2 = obj2[property];
      return value1 - value2; // 升序
    }
  },
  /*获取首页列表数据*/
  getList(v) {
    let _that = this;
    if(v){
      _that.data.page++;
    }
    wx.showLoading({
      title: '加载中',
      mask: true,
      success: function () {
        wx.request({
          url: config.trains,
          data: {
            perPage:_that.data.perPage,
            page: _that.data.page,
            r: Math.random(),
            client:1
          },
          method: 'GET',
          success: function (res) {
            let datas = res.data
            if (datas.code == "200") {
            let datas_list_deil = datas.data.data; //新版
              let last_page = datas.data.last_page;
              if(last_page>1 && _that.data.page<last_page){
               // _that.setData({ hiddenloading: false });//隐藏显示更多
              }else{
                _that.setData({ hasmoreData: false, hiddenloading: true })
              }
              let new_datas = [];
              for (let y = 0; y < datas_list_deil.length; y++) {
                let thatOne = datas_list_deil[y];
                let thsiOnePrics = [];
                if (thatOne.is_free) {
                  const course_detail = thatOne.get_charge;
                  for (let i = 1; i < 4; i++) {
                    let arN = "attr" + i + "_name";
                    let prC = "attr" + i + "_price";
                    if (course_detail[arN]) {
                      thsiOnePrics.push({
                        "attr_name": course_detail[arN],
                        "attr_price": course_detail[prC]
                      });
                    }
                  }
                  //重新排序
                  thsiOnePrics = thsiOnePrics.sort(_that.compare("attr_price"));
                  thatOne.getPrices = thsiOnePrics;
                }
                new_datas.push(thatOne);
              }
              _that.setData({
                list: [..._that.data.list,...new_datas]
              });

              setTimeout(function () {
                wx.hideLoading();
                wx.stopPullDownRefresh();
              }, 1000)

            }
          },
          fail:function(){
            setTimeout(function () {
              wx.hideLoading();
              wx.stopPullDownRefresh();
            }, 1000)
          }
        })
      }
    })
  },
  //加载更多活动
  getMoreTrain(){
    this.getList(1);
  },
  goUpIndex(e) {
    let id = e.currentTarget.dataset.id;
    let index = e.currentTarget.dataset.index;
    let course_one_detail = this.data.list[index] || '';
    wx.setStorage({
      key: 'course_one_detail',
      data: course_one_detail,
      success: function(res) {
        let url = '/pages/index/signUP/index?train_id=' + id;
        wx.navigateTo({
          url: url
        })
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    //授权

    this.getList();
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

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
    this.setData({ page: 1, list: [], hasmoreData: true, hiddenloading: true })
    this.getList();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {
    // this.setData({ hiddenloading: false })
    if (this.data.hasmoreData){
      this.getList(1);
    }
   
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})