// pages/index/signUP/signUpList/addInfo/index.js
let config = require("../../../../../config.js");
let common = require("../../../../common.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    source_url: '',
    meEducation: '大专',
    meProfess:'请选择',
    meProfessList:[],
    selectEducation: false,
    profess:false,
    _sex: '',
    edit_id: '',
    error: "",
    otherFormat: {
      "contract_no": ""
    },
    train_id: '',
    type_unit: '',
    unit_num: '',
    user_id_detail: '',
    order_id: '',
    isAjax: false,
    enpty: 0,
    hasgo:0,
    healthNum: 0,
    train_set:{
      is_health:0,
      is_card:0,
      is_labor:0,
      is_learnership:0,
      is_idcard:0,
      is_school:0,
      is_education:0,
      is_profession:0
    },
    userId:'',
    change:''
  },
  getUserDeail(id) {
    let _that = this;
    wx.request({
      url: `${config.nursery_students_edit}/${id}`,
      data:{
        client:1
      },
      method: "GET",
      success: function(res) {
        let dataOne = res.data;
        if (dataOne.code == "200") {
          let datas = dataOne.data;
          if (datas.card_f) {
            _that.data.otherFormat.card_f = datas.card_f;
          }
          if (datas.card_z) {
            _that.data.otherFormat.card_z = datas.card_z;
          }
          if (datas.health_1) {
            _that.data.otherFormat.health_1 = datas.health_1;
          }
          if (datas.health_2) {
            _that.data.otherFormat.health_2 = datas.health_2;
          }
          if (datas.health_3) {
            _that.data.otherFormat.health_3 = datas.health_3;
          }
          if (datas.labor_1) {
            _that.data.otherFormat.labor_1 = datas.labor_1;
          }
          if (datas.labor_2) {
            _that.data.otherFormat.labor_2 = datas.labor_2;
          }
          if (datas.learnership) {
            _that.data.otherFormat.learnership = datas.learnership;
          }
          _that.setData({
            user_id_detail: datas,
            _sex: datas.student_sex,
            meProfess: datas.student_position,
            meEducation: datas.education,
            otherFormat: _that.data.otherFormat
          });
        } else {
          common.progressTips("获取用户资料失败！");
        }
      },
      fail: function() {
        common.progressTips("获取用户资料失败！");
      }
    })
  },
  selectEducation(e) {
    let me = e.currentTarget.dataset.me;
    if (me == "choose") {
      if (this.data.selectEducation) {
        this.setData({
          selectEducation: false
        });
      } else {
        this.setData({
          selectEducation: true
        });
      }
    } else {
      this.setData({
        meEducation: me,
        selectEducation: false
      });
    }
  },
  selectProfess(e) {
    let me = e.currentTarget.dataset.pro;
    if (me == "choose") {
      if (this.data.selectProfess) {
        this.setData({
          selectProfess: false
        });
      } else {
        this.setData({
          selectProfess: true
        });
      }
    } else {
      this.setData({
        meProfess: me,
        selectProfess: false
      });
    }
  },
  chooseSex(e) {
    let sex = e.currentTarget.dataset.sex;
    this.setData({
      _sex: sex
    });
  },
  del_img(e) {
    const that = this;
    let index = parseInt(e.currentTarget.dataset.index);
    let secondIndex = parseInt(e.currentTarget.dataset.secondindex);
    let fz = index.toString() + secondIndex;
    let formatN = '';
    switch (fz) {
      case '00':
        formatN = 'card_z';
        break;
      case '01':
        formatN = 'card_f';
        break;
      case '10':
        formatN = 'health_1';
        break;
      case '11':
        formatN = 'health_2';
        break;
      case '12':
        formatN = 'health_3';
        break;
      case '20':
        formatN = 'labor_1';
        break;
      case '21':
        formatN = 'labor_2';
        break;
      case '30':
        formatN = 'learnership';
        break;
    }
    that.data.otherFormat[formatN] = "";
    that.setData({
      otherFormat: that.data.otherFormat
    })
    let healthst = that.data.otherFormat.health_1;
    let healthnd = that.data.otherFormat.health_2;
    let healthrd = that.data.otherFormat.health_3;
    if (!healthst || !healthnd || !healthrd) {
      that.setData({
        healthNum: 2
      })
    };
    if (!healthst && !healthnd || !healthrd && !healthnd || !healthst && !healthrd) {
      that.setData({
        healthNum: 1
      })
    };
    if (!healthst && !healthnd && !healthrd) {
      that.setData({
        healthNum: 0
      })
    };
  },
  getImage(e) {
    const that = this;
    let index = parseInt(e.currentTarget.dataset.index);
    let secondIndex = parseInt(e.currentTarget.dataset.secondindex);
    let fz = index.toString() + secondIndex;
    let formatN = '';
    switch (fz) {
      case '00':
        formatN = 'card_z';
        break;
      case '01':
        formatN = 'card_f';
        break;
      case '10':
        formatN = 'health_1';
        break;
      case '11':
        formatN = 'health_2';
        break;
      case '12':
        formatN = 'health_3';
        break;
      case '20':
        formatN = 'labor_1';
        break;
      case '21':
        formatN = 'labor_2';
        break;
      case '30':
        formatN = 'learnership';
        break;
    }
    wx.chooseImage({
      count: 1,
      success: function(res) {
        let tempFiles = res.tempFiles;
        let uploadTask = wx.uploadFile({
          url: config.upload_image,
          filePath: tempFiles[0].path,
          name: "file",
          header: {
            "Content-Type": "multipart/form-data"
          },
          success: function(res) {
            let data_img_d = JSON.parse(res.data);
            if (data_img_d.code == 200) {
              that.data.otherFormat[formatN] = data_img_d.data;
              that.setData({
                otherFormat: that.data.otherFormat
              })
            } else {
              common.progressTips(data_img_d.msg);
            }
            let healthst = that.data.otherFormat.health_1;
            let healthnd = that.data.otherFormat.health_2;
            let healthrd = that.data.otherFormat.health_3;
            if (healthst || healthnd || healthrd){
              that.setData({
                healthNum:1
              })
            };
            if (healthst&&healthnd || healthst&&healthrd || healthrd&&healthnd) {
              that.setData({
                healthNum: 2
              })
            };
            if (healthst && healthnd && healthrd) {
              that.setData({
                healthNum: 3
              })
            };
          },
          fail: function() {
            common.progressTips("上传失败！");
          }
        });
        uploadTask.onProgressUpdate((res) => {
          wx.showLoading({
            title: '上传中',
            icon: 'loading',
            mask: true,
            success: function() {
              if (res.progress == 100) {
                wx.hideLoading();
              }
            },
            fail: function() {
              wx.hideLoading();
            }
          })
        })
      },
    })
  },
  validation(subFormata) {
    for (let ob in subFormata) {
      if (!subFormata[ob] || subFormata[ob]=='请选择') {
        common.progressTips("您有未填写项！");
        return false;
      } else {
        let phoneRge = /^1\d{10}$/;
        let cordReg = /(^\d{8}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if (ob == 'student_phone' && !phoneRge.test(subFormata[ob])) {
          //手机号
          common.progressTips("请输入11位合法手机号！");
          return false;
        }
        if (ob == 'school' && this.data.train_set.is_school && !subFormata[ob]){
          //毕业院校
          common.progressTips("请输入毕业院校！");
          return false;
        }
        if (ob == 'profession' && this.data.train_set.is_profession && !subFormata[ob]) {
          //毕业院校
          common.progressTips("请输入教师专业！");
          return false;
        }
        if (ob == 'idcard' && this.data.train_set.is_idcard && !cordReg.test(subFormata[ob])) {
          //身份证号
          common.progressTips("请输入合法身份证号！");
          return false;
        }
      }
    }
    let num = 0;
    if ((!this.data.otherFormat.card_z || !this.data.otherFormat.card_f) && this.data.train_set.is_card) {
      //身份证
      common.progressTips("请上传完整身份证照片！");
      return false;
    }
    if (this.data.otherFormat.health_1) {
      //健康证
      num++;
    }
    if (this.data.otherFormat.health_2) {
      //健康证
      num++;
    }
    if (this.data.otherFormat.health_3) {
      //健康证
      num++;
    }
    if (num < 1 && this.data.train_set.is_health) {
      common.progressTips("请上传完整健康证照片！");
      return false;
    }
    if ((!this.data.otherFormat.labor_1 || !this.data.otherFormat.labor_2) && this.data.train_set.is_labor) {
      //劳动合同
      common.progressTips("请上传完整劳动合同照片！");
      return false;
    }
    if (!this.data.otherFormat.learnership && this.data.train_set.is_learnership) {
      //培训协议
      common.progressTips("请上传培训协议照片！");
      return false;
    }
    return true;
  },

  formSubmit: function(e) {
    let _that = this;
    let subFormata = e.detail.value;
    if (!this.validation(subFormata)) {
      return false;
    }
    if (this.data.isAjax) {
      return false;
    }
    let subFormataAll = {
      ...subFormata,
      ...this.data.otherFormat
    };
    if (subFormataAll) {
      //保存用户资料
      let urls = `${config.save_nursery_students}`;
      subFormataAll.apply_user = wx.getStorageSync('user_save_infos').userid
      if (this.data.edit_id) {
        //更新用户资料
        subFormataAll.id = this.data.edit_id;
        urls = `${config.nursery_students_update}`;
      }

      this.setData({
        isAjax: true
      });
      subFormataAll.client = 1;
      wx.request({
        url: urls,
        data: subFormataAll,
        method: 'POST',
        success: function(res) {
          let datas = res.data;
          if (datas.code == "200") {
            let url_to = '';
            if (_that.data.source_url.indexOf('allInfo/index') > -1) {
                //返回订单详情
                url_to = '/pages/mine/my/allInfo/index?&source_url=' + _that.data.source_url + '&contract_no=' + _that.data.otherFormat.contract_no + '&train_id=' + _that.data.train_id + '&order_id=' + _that.data.order_id + '&type_unit=' + _that.data.type_unit + '&unit_num=' + _that.data.unit_num + '&empty=' + _that.data.enpty;
            } else {
              if(_that.data.userId){
                url_to = '/pages/index/signUP/signUpList/editInfo/index?change=true&source_url=' + _that.data.source_url + '&contract_no=' + _that.data.otherFormat.contract_no + '&train_id=' + _that.data.train_id + '&userId=' + _that.data.userId +'&order_id=' + _that.data.order_id ;
              }
              //返回学员列表
              else if (_that.data.edit_id || _that.data.hasgo) {
                url_to = '/pages/index/signUP/signUpList/editInfo/index?source_url=' + _that.data.source_url + '&contract_no=' + _that.data.otherFormat.contract_no + '&train_id=' + _that.data.train_id + '&type_unit=' + _that.data.type_unit + '&unit_num=' + _that.data.unit_num + '&hasgo=1';
              } else {
                url_to = '/pages/index/signUP/signUpList/editInfo/index?source_url=' + _that.data.source_url + '&contract_no=' + _that.data.otherFormat.contract_no + '&train_id=' + _that.data.train_id + '&type_unit=' + _that.data.type_unit + '&unit_num=' + _that.data.unit_num;
              }
            }
            common.progressTips("操作成功！");
            setTimeout(function() {
              wx.navigateTo({
                url: url_to
              })
            }, 2000);
          } else {
            common.progressTips(datas.msg);
          }
        },
        fail: function() {
          common.progressTips("出错了!");
        },
        complete: function() {
          _that.setData({
            isAjax: false
          });
        }
      })
    }
  },
  formReset() {

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    if (options.enpty) {
      this.setData({
        enpty: options.enpty
      })
    }
    if (options.edit_id) {
      this.setData({
        edit_id: options.edit_id
      })
      this.getUserDeail(options.edit_id);
    }
    if (options.order_id) {
      this.setData({
        order_id: options.order_id
      })
    }
    if (options.contract_no) {
      let otherFormats = {
        contract_no: options.contract_no
      };
      this.setData({
        otherFormat: otherFormats
      })
    }
    if (options.type_unit) {
      this.setData({
        type_unit: options.type_unit
      })
    }
    if (options.hasgo) {
      this.setData({
        hasgo: options.hasgo
      })
    }
    
    if (options.unit_num) {
      this.setData({
        unit_num: options.unit_num
      })
    }

    if (options.userId) {
      this.setData({
        userId: options.userId,
        change:options.change
      })
    }
    this.setData({
      source_url: options.source_url,
      train_id: options.train_id
    });
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {
    let that=this;
    wx.request({
      url: `${config.profess}`,
      data: { client: 1},
      method: 'GET',
      success: function (res) {
        that.setData({
          meProfessList: res.data.data
        });
      }
    });
    wx.request({
      url: `${config.train_setting}/${that.data.train_id}`,
      data: { client: 1},
      method: 'GET',
      success: function (res) {
        that.setData({
          train_set: res.data.data
        });
      }
    });
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
    wx.showNavigationBarLoading()
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