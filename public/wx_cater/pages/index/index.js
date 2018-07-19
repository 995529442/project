// 获取全局应用程序实例对象
var app = getApp();

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    userinfo_box: false,
    title: 'index',
    userInfo: null,
    userSite: '定位中',
    navList: [
    //   {
    //   navTitle: '排队取号',
    //   navIcon: 'iconfont icon-shalou'
    // }, 
    {
      navTitle: '预约订座',
      navIcon: 'iconfont icon-chuliyuyue'
    }, {
      navTitle: '扫描单号',
      navIcon: 'iconfont icon-erweima'
    }],
    hotShop: [{
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }, {
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }, {
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }, {
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }, {
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }, {
      shopImg: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      shopName: '青花椒砂锅鱼'
    }],
    nearShop: [{
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '30',
      kind: '中国菜',
      distance: '8.6km',
      status: '无需排队',
      grade: 'five-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '30',
      kind: '中国菜',
      status: '无需排队',
      grade: 'four-star'
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '青花椒砂锅鱼',
      price: '128',
      kind: '中国菜',
      status: '无需排队',
      grade: 'one-star'
    }],
    imgUrls: ['http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg', 'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg']
  },
  /**
   * 用户选择位置
   * @returns {boolean}
   */
  chooseLocation: function chooseLocation() {
    // console.log(1)
    var that = this;
    wx.chooseLocation({
      success: function success(res) {
        console.log(res);
        if (res.name.length <= 0) {
          return that.setData({
            userSite: res.address
          });
        }
        that.setData({
          userSite: res.name
        });
      }
    });
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
    app.getUserInfo(e,that.onLoad,this);
  },
});
