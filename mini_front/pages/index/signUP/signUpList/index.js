// pages/index/signUp/signUpList/index.js
let config = require("../../../../config.js");
let common = require("../../../common.js");
Page({

  /**
   * 页面的初始数据
   */

  data: {
    studentList: [],
    train_id: '',
    add_edit_url: '',
    radioItems: [{
        name: '亲子园',
        value: 'Q',
        checked: true
    },{
        name: '幼儿园',
        value: 'Y',
        checked: false
      }
    ],
    pay_type_list: [],
    contract_no: '',
    student_count: '', //是否园所已经存在学员
    schName: '',
    QY: 'Q',
    course_one_detail: '',
    checkedOneId: '',
    type_unit: '',
    unit_num: '',
    init_one_price:0,
    one_prices: 0,
    isAjax: false,
    round:Math.random(),
    sumMoney:0.00
  },
  radioChange: function(e) {
    let checked = e.detail.value
    let changed = {}
    for (var i = 0; i < this.data.radioItems.length; i++) {
      if (checked.indexOf(this.data.radioItems[i].name) !== -1) {
        changed['radioItems[' + i + '].checked'] = true;
        this.setData({
          QY: this.data.radioItems[i].value,
          studentList: [],
          contract_no:'',
          schName: '',
          isAjax: false,
          checkedOneId: 0,
          one_prices: this.data.init_one_price
        })
      } else {
        changed['radioItems[' + i + '].checked'] = false
      }
    }
    this.setData(changed);
  },
  delItem(e) {
    let _that = this;
    let index = e.currentTarget.dataset.index;
    let id = e.currentTarget.dataset.id;
    let onStudentList = this.data.studentList;
    wx.request({
      url: `${config.apply_students_del}/${id}`,
      data:{
        client:1
      },
      method: 'GET',
      success: function(res) {
        let datas = res.data;
        if (datas.code == "200") {
          onStudentList.splice(index, 1);
          _that.setData({
            studentList: onStudentList
          });
          _that.getSchForNo();
        } else {
          common.progressTips(datas.msg);
        }
      },
      fail: function() {
        common.progressTips('出错了!');
      }
    })

  },

  toDecimal2(x) {
      var f = parseFloat(x);
      if(isNaN(f)) {
    return false;
  }
  var f = Math.round(x * 100) / 100;
  var s = f.toString();
  var rs = s.indexOf('.');
  if (rs < 0) {
    rs = s.length;
    s += '.';
  }
  while (s.length <= rs + 2) {
    s += '0';
  }
  return s;
  },
  //获取输入的合同号
  getNo(e) {
    let _that=this;
    this.setData({
      checkedOneId: 0,
      one_prices: _that.data.init_one_price,
      contract_no: e.detail.value
    })
    
  },
  //通过输入合同号获取园所名称
  getSchForNo(again) {
    let _that = this;
    if (_that.data.contract_no && !_that.data.contract_no.startsWith(_that.data.QY)) {
      wx.showLoading({
        title: '查询中',
        mask: true,
        success: function () {
          wx.request({
            url: config.check_contract,
            method: 'POST',
            data: {
              contract_no: _that.data.QY + _that.data.contract_no,
              train_id: _that.data.train_id,
              apply_user: wx.getStorageSync('user_save_infos').userid,
              client:1
            },
            success: function (res) {
              let datas = res.data;
              if (datas.code == "200") {
                let courDD = _that.data.course_one_detail;
                //付费方式
                if (courDD.is_free && again != "again") {
                  //购买单位园所
                  if (courDD.get_charge.unit != 1) {
                    _that.setData({
                      one_prices: _that.data.pay_type_list[_this.data.checkedOneId].attr_price
                    })
                    if (_that.data.init_one_price == 0) {
                      _that.setData({
                        init_one_price: _that.data.one_prices
                      })
                    }
                  }
                  //购买单位人
                  //只含一种售价
                  if (_that.data.pay_type_list.length == 1) {
                    _that.setData({
                      one_prices: _that.data.pay_type_list[_that.data.checkedOneId].attr_price
                    })
                    if (_that.data.init_one_price == 0) {
                      _that.setData({
                        init_one_price: _that.data.one_prices
                      })
                    }
                  }
                  //含多中售价且为销售1方式
                  if (_that.data.pay_type_list.length > 1 && courDD.get_charge.charge_way == 1) {
                    //团单方式
                    if (datas.data.apply_students.length >= courDD.get_charge.min_num) {
                      //团购价
                      _that.setData({
                        one_prices: _that.data.pay_type_list[_that.data.checkedOneId].attr_price
                      })
                      if (_that.data.init_one_price == 0) {
                        _that.setData({
                          init_one_price: _that.data.one_prices
                        })
                      }
                    } else {
                      //最高价
                      _that.setData({
                        one_prices: _that.data.pay_type_list[_that.data.pay_type_list.length - 1].attr_price
                      })
                      if (_that.data.init_one_price == 0) {
                        _that.setData({
                          init_one_price: _that.data.one_prices
                        })
                      }
                    }
                  }
                  //含多中售价且为销售2方式
                  if (_that.data.pay_type_list.length > 1 && courDD.get_charge.charge_way == 2) {
                    //最优价
                    _that.setData({
                      one_prices: _that.data.pay_type_list[_that.data.checkedOneId].attr_price
                    })
                    if (_that.data.init_one_price == 0) {
                      _that.setData({
                        init_one_price: _that.data.one_prices
                      })
                    }
                  }

                  //含多中售价且为销售3方式
                  if (_that.data.pay_type_list.length > 1 && courDD.get_charge.charge_way == 3) {
                    //默认最低价
                    _that.setData({
                      one_prices: _that.data.pay_type_list[_that.data.checkedOneId].attr_price
                    })
                    if (_that.data.init_one_price == 0) {
                      _that.setData({
                        init_one_price: _that.data.one_prices
                      })
                    }
                  }
                }
                _that.setData({
                  studentList: datas.data.apply_students,
                  schName: datas.data.contract.schName,
                  student_count: datas.data.student_count,
                  type_unit: courDD.get_charge.unit,
                  unit_num: courDD.get_charge.max_nursery_num
                });

              } else {
                _that.setData({
                  schName: '',
                  studentList: [],
                  student_count: ''
                });
                common.progressTips(datas.msg);
              }
              let sum = _that.toDecimal2(_that.data.course_one_detail.get_charge.unit == 1 ? _that.data.one_prices * _that.data.studentList.length : _that.data.one_prices)
              _that.setData({ sumMoney: sum})
              setTimeout(function () {
                wx.hideLoading();
              }, 1000)
            },
            fail: function () {
              _that.setData({
                QY: _that.data.radioItems[i].value,
                studentList: [],
                contract_no: '',
                schName: '',
                isAjax: false,
                checkedOneId: 0,
                one_prices: _that.data.init_one_price
              })
              common.progressTips("出错了");
              setTimeout(function () {
                wx.hideLoading();
              }, 1000)
            }
          });

        }
      })
    } else {
      common.progressTips("请输入" + _that.data.QY + '开头之后得合同号！');
    }
  },
  che_reaone(e) {
    let itid = e.currentTarget.dataset.itid;
    let prices = e.currentTarget.dataset.price;
    this.setData({
      checkedOneId: itid,
      one_prices: prices
    })
    let sum = this.toDecimal2(this.data.course_one_detail.get_charge.unit == 1 ? this.data.one_prices * this.data.studentList.length : this.data.one_prices);
    this.setData({
      sumMoney: sum
    })
  },
  // 指定排序的比较函数
  compare(property) {
    return function(obj1, obj2) {
      var value1 = obj1[property];
      var value2 = obj2[property];
      return value1 - value2; // 升序
    }
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
              method:"get",
              data:{
                order_id: order_id,
                prepay_id:order.prepay_id,
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
                url: `/pages/mine/my/allInfo/index?order_id=${order_id}&train_id=${train_id}&contract_no=${that.data.QY + that.data.contract_no}`,
              })
            }, 2000);
          }
        })
      }
    })
  },
  goPay() {
    let _that = this;
    let stu = this.data.studentList;
    if (!stu.length) {
      common.progressTips("还未添加学员！");
      return false;
    }

    if (!wx.getStorageSync('user_save_infos').userid) {
      common.progressTips("用户信息获取失败请尝试重新授权！");
      return false;
    }
    if (this.data.isAjax) {
      return false;
    }
    this.setData({
      isAjax: true
    });
    wx.request({
      url: `${config.save_order}`,
      method: 'POST',
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      data: {
        apply_user: wx.getStorageSync('user_save_infos').userid,
        contract_no: _that.data.QY + _that.data.contract_no,
        train_id: _that.data.train_id,
        price: _that.data.one_prices,
        park_name: _that.data.schName,
        client:1
      },
      success: function(res) {
        let datas = res.data;
        if (datas.code == "200") {
          let order_id = datas.data.order_id;
          if (!_that.data.course_one_detail.is_free) {
            //免费
            common.progressTips("提交成功！");
            setTimeout(function() {
              wx.navigateTo({
                url: `/pages/mine/my/allInfo/index?order_id=${order_id}&train_id=${_that.data.train_id}&contract_no=${_that.data.QY + _that.data.contract_no}`,
              })
            }, 2000)
          } else {
            //付费
            _that.toWechatPay(order_id, _that.data.train_id);
          }
        } else {
          common.progressTips(datas.msg);
        }

      },
      fail: function() {
        common.progressTips("出错了");
      },
      complete: function() {
        _that.setData({
          isAjax: false
        });
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(option) {
    console.log("学员报名加载")
    const thatOne = wx.getStorageSync('course_one_detail');
    if (thatOne.getPrices){
      this.setData({
        pay_type_list: thatOne.getPrices
      });
    }
    this.setData({
      course_one_detail: thatOne
    });
    if (option.train_id) {
      this.setData({
        train_id: option.train_id
      });
    }
    let setLocalData = wx.getStorageSync('init_out_data');
    if (setLocalData && option.train_id == setLocalData.train_id) {
      this.setData({
        schName: setLocalData.schName,
        contract_no: setLocalData.contract_no,
        QY: setLocalData.QY,
        checkedOneId: setLocalData.checkedOneId,
        add_edit_url: setLocalData.add_edit_url,
        student_count: setLocalData.student_count,
        type_unit: setLocalData.type_unit,
        unit_num: setLocalData.unit_num,
        radioItems: setLocalData.radioItems,
        init_one_price: setLocalData.init_one_price
      });
      if (setLocalData.one_prices){
        this.setData({
          one_prices: setLocalData.one_prices
        })
      }

      let sum = this.toDecimal2(this.data.course_one_detail.get_charge.unit == 1 ? this.data.one_prices * this.data.studentList.length : this.data.one_prices)
      this.setData({ sumMoney: sum })

      if (setLocalData.contract_no){
        this.getSchForNo('again');
      }
    }
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {
    console.log("学员报名加载")
    const thatOne = wx.getStorageSync('course_one_detail');
    if (thatOne.getPrices){
      this.setData({
        pay_type_list: thatOne.getPrices
      });
    }
    this.setData({
      course_one_detail: thatOne
    });
    let setLocalData = wx.getStorageSync('init_out_data');
    if (setLocalData && setLocalData.train_id) {
      this.setData({
        schName: setLocalData.schName,
        contract_no: setLocalData.contract_no,
        QY: setLocalData.QY,
        checkedOneId: setLocalData.checkedOneId,
        add_edit_url: setLocalData.add_edit_url,
        student_count: setLocalData.student_count,
        type_unit: setLocalData.type_unit,
        unit_num: setLocalData.unit_num,
        radioItems: setLocalData.radioItems,
        init_one_price: setLocalData.init_one_price
      });
      if (setLocalData.one_prices) {
        this.setData({
          one_prices: setLocalData.one_prices
        })
      }
      let sum = this.toDecimal2(this.data.course_one_detail.get_charge.unit == 1 ? this.data.one_prices * this.data.studentList.length : this.data.one_prices)
      this.setData({ sumMoney: sum })
      if (setLocalData.contract_no){
        this.getSchForNo();
      }
    }
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    this.setData({"round":Math.random()});
    console.log("学员报名加载222")
    const thatOne = wx.getStorageSync('course_one_detail');
    if (thatOne.getPrices) {
      this.setData({
        pay_type_list: thatOne.getPrices
      });
    }
    this.setData({
      course_one_detail: thatOne
    });
    let setLocalData = wx.getStorageSync('init_out_data');
    if (setLocalData.train_id) {
      this.setData({
        schName: setLocalData.schName,
        contract_no: setLocalData.contract_no,
        QY: setLocalData.QY,
        checkedOneId: setLocalData.checkedOneId,
        add_edit_url: setLocalData.add_edit_url,
        student_count: setLocalData.student_count,
        type_unit: setLocalData.type_unit,
        unit_num: setLocalData.unit_num,
        radioItems: setLocalData.radioItems,
        init_one_price: setLocalData.init_one_price
      });
      if (setLocalData.one_prices) {
        this.setData({
          one_prices: setLocalData.one_prices
        })
      }
      let sum = this.toDecimal2(this.data.course_one_detail.get_charge.unit == 1 ? this.data.one_prices * this.data.studentList.length : this.data.one_prices)
      this.setData({ sumMoney: sum })
      if (setLocalData.contract_no) {
        this.getSchForNo();
      }
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {
    let _that=this;
    wx.setStorage({
      key: 'init_out_data',
      data: _that.data
    });
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {
    let _that = this;
    wx.setStorage({
      key: 'init_out_data',
      data: _that.data
    });
    // wx.reLaunch({
    //   url: '/pages/index/index?goindex=1'
    // })
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