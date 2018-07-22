// 获取全局应用程序实例对象
var app = getApp();

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    userinfo_box: false,
    url: app.globalData.appUrl,
    navList: [
      {
        navTitle: '商家',
        navIcon: 'iconfont icon-552cc4077f2ed:before'
      },
      {
        navTitle: '点餐',
        navIcon: 'iconfont icon-canshi:before'
      }, {
        navTitle: '外卖',
        navIcon: 'iconfont icon-mifen2:before'
      }],
    goods: [],//显示菜品
    active_index:1,
    imgUrls: ['http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg']
  },
  /**
   * 用户搜索
   */
  goSearch: function goSearch() {
    wx.navigateTo({
      url: '../search/search'
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad() {
    var that = this;

    if (wx.getStorageSync('openId') == undefined || wx.getStorageSync('openId') == '') {
      that.setData({
        userinfo_box: true,
      })
      return;
    }
    that.getGoods("hot");

    wx.getLocation({
      type: 'wgs84',
      success: function (res) {
        var latitude = res.latitude
        var longitude = res.longitude
        
        wx.setStorageSync('latitude', latitude);
        wx.setStorageSync('longitude', longitude);
      }
    })
  },
  //选取表头,获取数据
  selectGoods:function(e){
    var that=this;
    var select_type = e.currentTarget.dataset.type;
    var active_index = that.data.active_index;

    if (select_type == "hot"){
      active_index=1;
    } else if (select_type == "rec") {
      active_index=2;
    } else if (select_type == "new"){
      active_index=3;
    }

    that.setData({
      active_index: active_index
    })  
    that.getGoods(select_type);
  },
  //获取首页菜品
  getGoods:function(select_type){
    var that=this;

    wx.request({
      url: app.globalData.appUrl + '/api/cater/getGoods/getHotRecGoods',
      data: {
        admin_id: app.globalData.admin_id,
        type: select_type
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        if (res.data) {
          that.setData({
            goods: res.data
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
