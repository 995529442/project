'use strict';

// 获取全局应用程序实例对象
// const app = getApp()
var tcity = require('../../utils/city');
// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    // 地区选择相关
    provinces: [],
    province: '',
    citys: [],
    city: '',
    countys: [],
    county: '',
    value: [0, 0, 0],
    values: [0, 0, 0],
    condition: false,
    // 地区选择相关
    shopAddress: '添加地图标记',
    showMain: true,
    allHidden: false,
    hiddenMain: false,
  },
  /**
   * 信息录入
   * @param e
   */
  inputMessage: function inputMessage(e) {
    var obj = {};
    obj[e.currentTarget.dataset.type] = e.detail.value;
    this.setData(obj);
  },
  /**
   * 选择地区
   * @param e
   */
  bindChange: function bindChange(e) {
    // console.log(e);
    var val = e.detail.value;
    var t = this.data.values;
    var cityData = this.data.cityData;

    if (val[0] !== t[0]) {
      console.log('province no ');
      var citys = [];
      var countys = [];

      for (var i = 0; i < cityData[val[0]].sub.length; i++) {
        citys.push(cityData[val[0]].sub[i].name);
      }
      for (var _i = 0; _i < cityData[val[0]].sub[0].sub.length; _i++) {
        countys.push(cityData[val[0]].sub[0].sub[_i].name);
      }
      this.setData({
        province: this.data.provinces[val[0]],
        city: cityData[val[0]].sub[0].name,
        citys: citys,
        county: cityData[val[0]].sub[0].sub[0].name,
        countys: countys,
        values: val,
        value: [val[0], 0, 0]
      });
      return;
    }
    if (val[1] !== t[1]) {
      console.log('city no');
      var _countys = [];
      for (var _i2 = 0; _i2 < cityData[val[0]].sub[val[1]].sub.length; _i2++) {
        _countys.push(cityData[val[0]].sub[val[1]].sub[_i2].name);
      }
      this.setData({
        city: this.data.citys[val[1]],
        county: cityData[val[0]].sub[val[1]].sub[0].name,
        countys: _countys,
        values: val,
        value: [val[0], val[1], 0]
      });
      return;
    }
    if (val[2] !== t[2]) {
      // console.log('county no')
      this.setData({
        county: this.data.countys[val[2]],
        values: val,
        value: [val[0], val[1], val[2]]
      });
      return;
    }
  },
  /**
   * 地区显示开关
   */
  open: function open() {
    this.setData({
      condition: !this.data.condition
    });
  },

  /**
   * 选择地址
   */
  addMapSite: function addMapSite() {
    var that = this;
    wx.chooseLocation({
      success: function success(res) {
        // console.log(res)
        that.setData({
          shopAddress: res.name || res.address || '添加地图标记'
        });
      }
    });
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(e) {
    // TODO: onLoad
    var that = this;
    tcity.init(that);
    var cityData = that.data.cityData;
    var provinces = [];
    var citys = [];
    var countys = [];
    for (var i = 0; i < cityData.length; i++) {
      provinces.push(cityData[i].name);
    }
    // console.log('省份完成')
    for (var _i3 = 0; _i3 < cityData[0].sub.length; _i3++) {
      citys.push(cityData[0].sub[_i3].name);
    }
    // console.log('city完成')
    for (var _i4 = 0; _i4 < cityData[0].sub[0].sub.length; _i4++) {
      countys.push(cityData[0].sub[0].sub[_i4].name);
    }
    that.setData({
      'provinces': provinces,
      'citys': citys,
      'countys': countys,
      'province': cityData[0].name,
      'city': cityData[0].sub[0].name,
      'county': cityData[0].sub[0].sub[0].name
    });
    // console.log('初始化完成')
    console.log(e);
  }
})
