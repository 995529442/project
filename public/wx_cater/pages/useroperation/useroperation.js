var app = getApp();
// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'useroperation',
    userinfo_box: false,
    url: app.globalData.appUrl,
    user_id: 0,
    page: 1,          //页数
    address: [],
    orders: [],
    currency: [],
    operation: null,
    pay_type: 0,
    currentCouponTab: 0,
    orderNumber: ['点餐', '外卖']
  },

  /**
   * 设置couponTab
   * @param e
   */
  chooseCouponTab: function chooseCouponTab(e) {
    var that = this;
    var tabid = e.currentTarget.dataset.tabid;
    that.setData({
      currentCouponTab: tabid,
      orders: [],
      page: 1
    });

    that.get_my_orders();
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
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(params) {
    console.log(params)
    var that = this;
    // 由跳转链接设置标题
    var operation = params.operation;
    var pay_type = typeof (params.pay_type) == 'undefined' ? '' : params.pay_type
    var currentCouponTab = typeof (params.currentCouponTab) == 'undefined' ? '' : params.currentCouponTab

    if (currentCouponTab != "") {
      that.setData({
        currentCouponTab: currentCouponTab - 1
      })
    }
    // 设置operation
    that.setData({
      operation: params.operation,
      pay_type: pay_type
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
    } else if (operation === 'currency') {
      operation = '我的购物币';

      that.get_my_currency();
    } else if (operation === 'integral') {
      operation = '积分兑换';
    } else if (operation === 'order') {
      operation = '我的订单';

      that.get_my_orders();
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
    var operation = that.data.operation;

    var page = that.data.page + 1;

    that.setData({
      page: page
    });

    if (operation == "order") {
      that.get_my_orders();
    } else if (operation == "address") {
      that.get_my_address();
    }
  },
  /**
   * 获取我的地址
   */
  get_my_address: function () {
    var that = this;

    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: app.globalData.appUrl + '/api/cater/getUserInfo/getAddress',
      data: {
        admin_id: app.globalData.admin_id,
        user_id: that.data.user_id,
        pay_type: that.data.pay_type,
        page: that.data.page
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        wx.hideLoading()
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
      var url = '../address/index?pay_type=1';
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
                    page: 1,
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
                        page: 1,
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
  /**
   * 选取地址
   */
  select_address: function (e) {
    var that = this;
    var address_id = e.currentTarget.dataset.address_id;
    var is_out = e.currentTarget.dataset.is_out;

    if (that.data.pay_type == 1 && is_out == 0) { //订单过来,并且在配送范围内
      wx.setStorageSync('address_id', address_id);

      wx.navigateBack({
        delta: 1
      })
    }
  },
  /**
   * 获取我的订单
   */
  get_my_orders: function get_my_orders() {
    var that = this;

    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: app.globalData.appUrl + '/api/cater/order/getOrders',
      data: {
        admin_id: app.globalData.admin_id,
        user_id: that.data.user_id,
        page: that.data.page,
        type: that.data.currentCouponTab
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log(res)
        wx.hideLoading()
        if (res.data.errcode > 0) {
          var orders = that.data.orders;
          orders = orders.concat(res.data.data)
          that.setData({
            orders: orders
          });
        }
      }
    })
  },

  /**
 * 获取我的购物币
 */
  get_my_currency: function () {
    var that = this;

    wx.showLoading({
      title: '加载中',
    })
    wx.request({
      url: app.globalData.appUrl + '/api/cater/getUserInfo/getCurrency',
      data: {
        admin_id: app.globalData.admin_id,
        user_id: that.data.user_id,
        page: that.data.page
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log(res)
        wx.hideLoading()
        if (res.data.errcode > 0) {
          var currency = that.data.currency;
          currency = currency.concat(res.data.data)
          that.setData({
            currency: currency
          });
        }
      }
    })
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
