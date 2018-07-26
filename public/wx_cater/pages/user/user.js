'use strict';

// 获取全局应用程序实例对象
var app = getApp();

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    userinfo_box: false,
    motto: 'Hello World',
    avatarurl: '',
    nickname: '',
    title: 'user',
    userDetail: [{
      title: '正在排队',
      number: 1
    }, {
      title: '优惠券',
      number: 4
    }, {
      title: '积分',
      number: 20
    }],
    userList: [{
      icon: 'iconfont icon-dingdan',
      title: '我的订单',
      id: 'order'
    }, {
        icon: 'iconfont icon-lingdang:before',
      title: '我的地址',
      id: 'address'
    }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    var that = this;

    //用户昵称
    if (wx.getStorageSync('nickname')) {
      that.setData({
        nickname: wx.getStorageSync('nickname')
      })

      console.log("66" + that.data.nickname)
    }
    //用户头像
    if (wx.getStorageSync('avatarurl')) {
      that.setData({
        avatarurl: wx.getStorageSync('avatarurl')
      })
    }
    if (wx.getStorageSync('openId') == undefined || wx.getStorageSync('openId') == '') {
      that.setData({
        userinfo_box: true,
      })
      return;
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
})
