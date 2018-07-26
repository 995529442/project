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
    userinfo_box: false,
    goods: [],
    allMoney: 0,
    cater_type: app.globalData.cater_type,
    shop_info: {},
    user_id: 0,
    default_address:{}
  },
  /**
   * 支付货款
   */
  payMoney: function payMoney() {
    // todo 付款流程
    // wx.requestPayment({
    //   'timeStamp': '',
    //   'nonceStr': '',
    //   'package': '',
    //   'signType': 'MD5',
    //   'paySign': '',
    //   'success':function(res){
    //   },
    //   'fail':function(res){
    //   }
    // })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(option) {
    var that = this;
    var goods_id_arr = option.goods_id_arr;
    var cater_type = that.data.cater_type

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

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getGoods/getSubmitGoods',
      data: {
        admin_id: app.globalData.admin_id,
        goods_id_arr: goods_id_arr,
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.data) {
          that.setData({
            goods: res.data.goods_id_arr,
            allMoney: res.data.total_money
          })
        }
      }
    })

    if (cater_type == 2) { //外卖。获取店铺信息，收货地址
      that.get_shop_info();
      that.get_default_address();
    }

  },
  /**
 * 获取店铺信息
 */
  get_shop_info: function (e) {
    var that = this;

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getShop/getShopInfo',
      data: {
        admin_id: app.globalData.admin_id,
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.data) {
          that.setData({
            shop_info: res.data
          })
        }
      }
    })
  },
  /**
   * 获取默认地址
   */
  get_default_address: function (e) {
    var that = this;

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getUserInfo/getDefaultAddress',
      data: {
        admin_id: app.globalData.admin_id,
        user_id: that.data.user_id,
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.data.errcode > 0){
           that.setData({
             default_address:res.data.data
           })
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

