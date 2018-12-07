// pages/index/signUP/signUpList/editInfo/index.js
let config = require("../../../../../config.js");
let common = require("../../../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    studentList: [],
    selectEditArr: [],
    source_url: '',
    contract_no: '',
    train_id: '',
    type_unit: '',
    unit_num: '',
    isAjax:false,
    empty:0,
    hasgo:"",
    order_id:'',
    change:'',
    userId:'',
    orderStudentList:[], //订单学员列表
    animationData: {},
    chooseChange: false
  },

  removeArrIndex(arr, val) {
    var indexA = arr.indexOf(val);
    if (indexA > -1) {
      arr.splice(indexA, 1);
    }
  },
  //获取列表数据
  getList() {
    let _that = this;
    let url="";
    let data={};
    let method ="GET";
    if(_that.data.change){
      url = config.get_not_order_students;
      method="POST";
      data={
        order_id: _that.data.order_id,
        apply_user: wx.getStorageSync('user_save_infos').userid,
        contract_no: _that.data.contract_no,
        client: 2
      }
    }else{
      url = config.nursery_students;
      data = {
        r: Math.random(),
        contract_no: _that.data.contract_no,
        train_id: _that.data.train_id,
        apply_user: wx.getStorageSync('user_save_infos').userid,
        client:1
      }
    }
    wx.showLoading({
      title: '加载中',
      mask:true,
      success: function () {
        wx.request({
          url: url,
          method: method,
          data: data,
          success: function (res) {
            let datas = res.data;
            if (datas.code == "200") {
              for (let y = 0; y < datas.data.length; y++) {
                if (datas.data[y].is_apply) {
                  _that.data.selectEditArr.push(datas.data[y].id);
                }
              }
                _that.setData({
                  studentList: datas.data
                });
              
            } else {
              common.progressTips(datas.msg);
            }
          },
          fail: function () {
            common.progressTips('出错了！');
          },
          complete:function(){
            setTimeout(function () {
              wx.hideLoading();
            }, 500)
          }
        })
      }
    })
 
  },
  checkEdit(e) {
    let arrCheckIndex = this.data.selectEditArr;
    let index = e.currentTarget.dataset.index;
    let id = e.currentTarget.dataset.id;
    let datas = this.data.studentList;
    let thisD = datas[index];
    let isEdit = thisD.card_yn == 1 || thisD.education_yn == 1 || thisD.health_yn == 1 || thisD.idcard_yn == 1 || thisD.learnership_yn == 1 || thisD.profession_yn == 1 || thisD.school_yn == 1;
    if (isEdit){
      common.progressTips('需补充信息，请前往查看并补充相关信息！');
      return false;
    }
      
    if (this.data.type_unit == 2) {
      //园所类型
      if (!arrCheckIndex.includes(id)&&arrCheckIndex.length >= this.data.unit_num) {
        common.progressTips("该课程限制人数为" + this.data.unit_num + "人");
        return false;
      }
    }
    if (arrCheckIndex.indexOf(id) > -1) {
      this.removeArrIndex(arrCheckIndex, id)
      datas[index].is_apply = 0;
    } else {
      if (this.data.change) {
        if (this.data.selectEditArr.length >= 1) {
          common.progressTips("只能选择其中一人");
          return false;
        }
      }
      arrCheckIndex.push(id);
      datas[index].is_apply = 1;
    }
    this.setData({
      selectEditArr: arrCheckIndex,
      studentList: datas
    })
  },
  goSignUpListIndex(e) {
    let _that = this;
    if (_that.data.isAjax) {
      return false;
    }
    _that.setData({ isAjax: true });
    let index = parseInt(e.currentTarget.dataset.index);
    let id = parseInt(e.currentTarget.dataset.id);
    let goUrl = "/pages/index/signUP/signUpList/index?source_url=/pages/index/signUP/signUpList/editInfo/index&contract_no=" + _that.data.contract_no + "&edit_id=" + id + "&train_id=" + _that.data.train_id + "&type_unit=" + _that.data.type_unit + "&unit_num=" + _that.data.unit_num;
    if (index != 10000000) {
      if(_that.data.change){
        goUrl = '/pages/index/signUP/signUpList/addInfo/index?change=true&source_url=/pages/index/ signUP/signUpList/editInfo/index&contract_no=' + _that.data.contract_no + '&train_id=' + _that.data.train_id + '&userId=' + _that.data.userId + '&order_id=' + _that.data.order_id+'&edit_id='+id;
      }else{
        goUrl = "/pages/index/signUP/signUpList/addInfo/index?source_url=/pages/index/signUP/signUpList/editInfo/index&contract_no=" + _that.data.contract_no + "&edit_id=" + id + "&train_id=" + _that.data.train_id + "&type_unit=" + _that.data.type_unit + "&unit_num=" + _that.data.unit_num;
      }
      wx.navigateTo({
        url: goUrl
      });
      _that.setData({ isAjax: false });
    } else {
      wx.request({
        url: config.save_apply_students,
        method: "POST",
        data: {
          contract_no: _that.data.contract_no,
          train_id: _that.data.train_id,
          student_id: _that.data.selectEditArr.toString(),
          apply_user: wx.getStorageSync('user_save_infos').userid,
          client:1
        },
        success: function(res) {
          let datas = res.data;
          if (datas.code == "200") {
            if (_that.data.hasgo){
              wx.navigateTo({
                url: goUrl
              });
            }else{
              wx.navigateBack({
                delta: 1
              })
            }
          
          } else {
            common.progressTips(datas.msg);
          }
        },
        fail: function() {
          common.progressTips('出错了！');
        },
        complete:function(){
          _that.setData({ isAjax: false });
        }
      })

    }
  },

  //更换培训人员 提交再次审核
  goChangePeople(e){
    let _that=this;
    if (_that.data.selectEditArr.length==0){
      common.progressTips('请选择一名学员！');
      return false;
    }
    wx.request({
      url: config.update_order_students,
      method: "POST",
      data: {
        order_id: _that.data.order_id,
        old_student_id: _that.data.userId,
        new_student_id: _that.data.selectEditArr.toString(),
        client:1
      },
      success: function (res) {
        let datas = res.data;
        if (datas.code == "200") {
          common.progressTips("提交成功");
          setTimeout(function () {
            wx.navigateTo({
              url: '/pages/mine/my/allInfo/index?order_id=' + _that.data.order_id + '&train_id=' + _that.data.train_id
            })
          }, 2000);
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function () {
        common.progressTips('出错了！');
      }
    })

  },
  //更换培训人员 提交再次审核 继续更改
  goChangePeopleTwo(e) {
    let _that = this;
    if (_that.data.selectEditArr.length == 0) {
      common.progressTips('请选择一名学员！');
      return false;
    }
    if (_that.data.userId){
      wx.request({
        url: config.update_order_students,
        method: "POST",
        data: {
          order_id: _that.data.order_id,
          old_student_id: _that.data.userId,
          new_student_id: _that.data.selectEditArr.toString(),
          client:1
        },
        success: function (res) {
          let datas = res.data;
          if (datas.code == "200") {
            let arr=_that.data.orderStudentList.filter(v => v.id != _that.data.userId);
            let arr1 = _that.data.studentList.filter(v => v.id != _that.data.selectEditArr[0])
            _that.setData({
              orderStudentList: arr,
              studentList:arr1,
              selectEditArr:[]
            })
            _that.getAction();
          } else {
            common.progressTips(datas.msg);
          }
        },
        fail: function () {
          common.progressTips('出错了！');
        }
      })
    }else{
      _that.getAction();
    }

  },
  getAction(){  //底部弹框动画
    let _that=this;
    // 创建一个动画实例
    var animation = wx.createAnimation({
      // 动画持续时间
      duration: 500,
      // 定义动画效果，当前是匀速
      timingFunction: 'linear'
    })
    // 将该变量赋值给当前动画
    _that.animation = animation
    // 先在y轴偏移，然后用step()完成一个动画
    animation.translateY(200).step()
    // 用setData改变当前动画

    _that.setData({
      // 通过export()方法导出数据
      animationData: animation.export(),
      // 改变view里面的Wx：if
      chooseChange: true
    })
    // 设置setTimeout来改变y轴偏移量，实现有感觉的滑动
    setTimeout(function () {
      animation.translateY(0).step()
      _that.setData({
        animationData: animation.export()
      })
    }, 200)
  },
  getOrderInfo(){ //更改培训人员  查询订单培训人员列表
    var that = this;
    wx.request({
      url: config.order_students + '/' + that.data.order_id,
      data:{
        client:1
      },
      method: "GET",
      success: function (res) {
        let datas = res.data;
        if (datas.code == "200") {
          let arr = [];
          Object.keys(datas.data).forEach(v => {
            arr.push(datas.data[v].get_nursery_user);
          })
          that.setData({
            orderStudentList: arr
          });
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function () {
        common.progressTips('出错了！');
      }
    })
  },
  hideModal: function (e) { //隐藏更换培训人员底部框
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
  gochangeSing(e){ //底部弹框
    this.setData({
      userId: e.currentTarget.dataset.id.toString()
    });
    this.hideModal();
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    if (options.empty){
      this.setData({
        empty: options.empty
      });
    }
    if (options.hasgo){
      this.setData({ hasgo: options.hasgo});
    }
    if(options.change){ //更换培训人员
      this.setData({
       order_id:options.order_id,
       change:options.change,
       userId: options.userId,
       train_id: options.train_id,
       contract_no: options.contract_no
      });
      this.getOrderInfo();
      wx.setNavigationBarTitle({
        title: '更换培训人员',
      })
    }else{
      this.setData({
        source_url: options.source_url,
        train_id: options.train_id,
        type_unit: options.type_unit,
        unit_num: options.unit_num,
        contract_no: options.contract_no
      });
    }
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

  }
})