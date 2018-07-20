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
    title: 'detail',
    detailRules: '听到叫号请到迎宾台，过号不作废，延三桌安排',
    imgUrls: ['http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg'],
    // waitInfo: [{
    //   kind: '餐桌类型',
    //   desk: '等待桌数',
    //   time: '预估时间'
    // }, {
    //   kind: '小桌（1-2人）',
    //   desk: '桌',
    //   number: '1',
    //   time: '--分钟'
    // }, {
    //   kind: '中桌（3-4人）',
    //   desk: '桌',
    //   number: '1',
    //   time: '--分钟'
    // }, {
    //   kind: '大桌（5人以上）',
    //   desk: '桌',
    //   time: '--分钟'
    // }],
    url: app.globalData.appUrl,
    restaurant: {}
  },
  /**
   * 拨打电话
   */
  callPhone: function callPhone() {
    var that=this;
    wx.makePhoneCall({
      phoneNumber: that.data.restaurant.phone
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    var that=this;

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getShop/getShopInfo',
      data: {
        admin_id: app.globalData.admin_id
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log(res)
        if(res.data){
          that.setData({
            restaurant:res.data
          })
        }
      }
    })   
  },
});

