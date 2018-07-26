'use strict';

// 获取全局应用程序实例对象
// const app = getApp()
var app = getApp();
// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'useroperation',
    userinfo_box: false,
    user_id: 0,
    address_page: 1,  //地址页数
    address: [],
    operation: null,
    numberList: {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '人马大饭堂',
      kind: '湘菜',
      average: 88,
      distance: 453,
      desk: 'C2',
      wait: 5
    },
    message: [{
      title: '三太子三汁',
      id: 'message1',
      content: '阿斯顿飞那是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就卡死的李开复',
      time: '2012-12-12'
    }, {
      title: '三太子三汁2',
      id: 'message2',
      content: '阿斯顿飞那是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就卡死的李开复',
      time: '2012-12-12'
    }],
    integral: [{
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      integralid: 'renma1',
      name: '人马大饭堂',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      integralid: 'renma2',
      name: '人马大饭堂',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '人马大饭堂',
      integralid: 'renma3',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }],
    currentCouponTab: 0,
    couponNumber: [{
      title: '未使用',
      count: 6
    }, {
      title: '使用记录',
      count: 0
    }, {
      title: '已过期',
      count: 0
    }],
    couponNoUseList: [{
      name: '人马科技大饭堂',
      id: 'shopId',
      delMoney: 100,
      useCondition: '消费即用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      delMoney: 100,
      useCondition: '满1000可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      discount: 5,
      useCondition: '满100可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }],
    couponUseList: [{
      name: '喜鹊楼',
      id: 'shopId',
      delMoney: 190,
      useCondition: '消费即用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '哈哈',
      id: 'shopId',
      delMoney: 100,
      useCondition: '满1000可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      discount: 5,
      useCondition: '满100可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }],
    orderNumber: ['待支付', '全部'],
    orderList: {
      pay: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238'
      }],
      finish: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238',
        delMoney: '23',
        actMoney: '215',
        restaurantId: 'No123123',
        waiterId: 'waiter123123'
      }],
      cancel: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238'
      }]
    },
    shopArray: ['请选择经营品类', '湘菜', '川菜', '粤菜', '沙县小吃', '徽菜', '茶点'],
    index: 0,
    showMessage: null
  },
  /**
   * 输入店名保存
   * @param e
   */
  shopNameInput: function shopNameInput(e) {
    this.setData({
      shopName: e.detail.value
    });
  },

  /**
   * 选择消息显示
   */
  chooseMessage: function chooseMessage(e) {
    this.setData({
      showMessage: e.currentTarget.dataset.message
    });
  },

  /**
   * 设置couponTab
   * @param e
   */
  chooseCouponTab: function chooseCouponTab(e) {
    this.setData({
      currentCouponTab: e.currentTarget.dataset.tabid
    });
  },

  /**
   * 去支付
   * @param e
   */
  goPay: function goPay(e) {
    wx.navigateTo({
      url: '../payorder/payorder?id=' + e.currentTarget.dataset.id
    });
  },

  /**
   * 去打分或者打赏
   * @param e
   */
  goGratuity: function goGratuity(e) {
    var restaurantId = e.currentTarget.dataset.restaurantid;
    var waiterId = e.currentTarget.dataset.waiterid;
    var kind = e.currentTarget.dataset.kind;
    var url = '';
    if (kind === 'shop') {
      url = '../grade/grade?restaurantId=' + restaurantId;
    } else {
      url = '../gratuity/gratuity?waiterId=' + waiterId;
    }
    wx.navigateTo({
      url: url
    });
  },

  /**
   * 选择经营品类
   */
  chooseShopKind: function chooseShopKind(e) {
    this.setData({
      index: e.detail.value
    });
  },

  /**
   * 开始上传商家入驻相关信息
   */
  startShop: function startShop() {
    // todo 入驻信息添加到缓存中
    if (!this.data.shopName || this.data.index === 0) {
      return wx.showModal({
        title: '信息不完整',
        content: '请补充信息完整',
        showCancel: false
      });
    }
    wx.redirectTo({
      url: '../businessCooperation/businessCooperation?shopName=' + this.data.shopName + '&shopKind=' + this.data.shopArray[this.data.index]
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(params) {
    console.log(params)
    var that = this;
    // 由跳转链接设置标题
    var operation = params.operation;
    var pay_type = typeof (params.pay_type) == 'undefined' ? '' : params.pay_type
    // 设置operation
    that.setData({
      operation: params.operation
    });

    //用户id
    if (wx.getStorageSync('user_id')) {
      that.setData({
        user_id: wx.getStorageSync('user_id')
      })
    }
    if (wx.getStorageSync('openId') == undefined || wx.getStorageSync('openId') == '') {
      that.setData({
        userinfo_box: true,
      })
      return;
    }

    // 判断传入类型
    if (operation === 'number') {
      operation = '我的排单号';
    } else if (operation === 'message') {
      operation = '消息';
    } else if (operation === 'integral') {
      operation = '积分兑换';
    } else if (operation === 'order') {
      operation = '我的订单';
    } else if (operation === 'merchant') {
      operation = '商家入驻';
    } else if (operation === 'address') {
      operation = '我的地址';

      that.get_my_address();
    } else {
      operation = '优惠券';
    }
    // 设置导航栏标题
    wx.setNavigationBarTitle({
      title: operation
    });
  },
  /**
   * 查看更多
   */
  scroll: function (e) {
    var that = this;

    var address_page = that.data.address_page + 1;

    that.setData({
      address_page: address_page
    });
    that.get_my_address();
  },
  /**
   * 获取我的地址
   */
  get_my_address: function () {
    var that = this;

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getUserInfo/getAddress',
      data: {
        admin_id: app.globalData.admin_id,
        user_id: that.data.user_id,
        page: that.data.address_page
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.data.errcode > 0) {
          var address = that.data.address;
          address = address.concat(res.data.data)
          that.setData({
            address: address
          });
        }
      }
    })
  },
  /**
 * 新增地址
 */
  add_address: function (e) {
    var address_id = typeof (e.currentTarget.dataset.id) == 'undefined' ? '' : e.currentTarget.dataset.id;

    if (address_id != "") {
      var url = '../address/index?address_id=' + address_id;
    } else {
      var url = '../address/index';
    }
    wx.navigateTo({
      url: url,
    })
  },

  /**
   * 新增我的地址(微信)
   */
  add_wx_address: function () {
    var that = this;

    wx.authorize({
      scope: 'scope.address',
      success(res) {
        wx.chooseAddress({
          success: function (res) {
            wx.request({
              url: app.globalData.appUrl + '/api/cater/getUserInfo/addAddress',
              data: {
                admin_id: app.globalData.admin_id,
                user_id: that.data.user_id,
                province: res.provinceName,
                city: res.cityName,
                country: res.countyName,
                address: res.detailInfo,
                user_name: res.userName,
                phone: res.telNumber
              },
              header: {
                'content-type': 'application/json'
              },
              success: function (res) {
                if (res.data.errcode == 1) { //成功
                  //重置数据
                  that.setData({
                    address_page: 1,
                    address: []
                  })
                  that.get_my_address();
                }
              }
            })
          },
          fail: function (err) {

          }
        })
      },
      fail(res) {
        //用户拒绝授权后执行
        wx.openSetting({})
      }
    })
  },
  /**
 * 删除我的地址
 */
  del_address: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;

    if (id > 0) {
      wx.showModal({
        title: '提示',
        content: '确定要删除此地址吗',
        success: function (res) {
          if (res.confirm) {
            wx.request({
              url: app.globalData.appUrl + '/api/cater/getUserInfo/delAddress',
              data: {
                address_id: id
              },
              header: {
                'content-type': 'application/json'
              },
              success: function (res) {
                if (res.data.errcode > 0) {
                  wx.showToast({
                    title: '删除成功',
                    icon: 'success',
                    duration: 2000,
                    success: function () {
                      //重载数据
                      that.setData({
                        address_page: 1,
                        address: []
                      })
                      that.get_my_address();
                    }
                  })
                }
              }
            })
          }
        }
      })
    }
  },

  // 授权提示
  UserInfo_click: function (e) {
    var that = this;
    if (e.currentTarget.dataset.name == '不允许授权') {
      wx.showToast({
        title: '授权失败',
        icon: 'loading',
        duration: 1200,
        success: () => {
          that.setData({ userinfo_box: false });
        },
      })
      return;
    }
    app.getUserInfo(e, that.onLoad, this);
  },
});
