// 获取全局应用程序实例对象
// const app = getApp()
var app = getApp();
var tcity = require('../../utils/city');
// 创建页面实例对象
Page({
    /**
     * 页面的初始数据
     */
    data: {
        // 地区选择相关
        userinfo_box: false,
        user_id: 0,
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
        shopAddress: '点击选择',
        showMain: true,
        allHidden: false,
        hiddenMain: false,
        user_shipping: {},
        is_checked: 0,  //是否默认
        pay_type: 0     //是否从订单列表过来
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
                    shopAddress: res.name || res.address || '点击选择'
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
        var pay_type = typeof (e.pay_type) == 'undefined' ? '' : e.pay_type;
        that.setData({
            pay_type: pay_type
        })
        //用户昵称
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
            'countys': countys
        });

        var address_id = typeof (e.address_id) == 'undefined' ? '' : e.address_id;

        if (address_id != '') {
            wx.request({
                url: app.globalData.appUrl + '/api/cater/getUserInfo/getOneAddress',
                data: {
                    address_id: address_id
                },
                header: {
                    'content-type': 'application/json'
                },
                success: function (res) {
                    console.log(res.data)
                    if (res.data) {
                        that.setData({
                            user_shipping: res.data,
                            province: res.data.province,
                            city: res.data.city,
                            county: res.data.country,
                            shopAddress: res.data.address,
                            shopAddress: res.data.address
                        })
                    }
                }
            })
        }
    },
    /**
     * 默认地址
     */
    default_address: function (e) {
        var that = this;
        var is_checked = e.detail.value

        if (is_checked) {
            is_checked = 1;
        } else {
            is_checked = 0;
        }
        that.setData({
            is_checked: is_checked
        })
    },
    /**
     * 提交表单
     */
    formSubmit: function (e) {
        var that = this;

        var address_id = e.detail.value.address_id;
        var user_name = e.detail.value.user_name;
        var phone = e.detail.value.phone;
        var addressDetail = e.detail.value.addressDetail;
        var province = that.data.province;
        var city = that.data.city;
        var county = that.data.county;
        var shopAddress = that.data.shopAddress;

        if (user_name == "" || user_name == null) {
            wx.showToast({
                title: '联系人不能为空',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (phone == "" || phone == null) {
            wx.showToast({
                title: '请输入收货手机号码，以便配送员联系您',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (!(/(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/.test(phone)) && !(/^0\d{2,3}-?\d{7,8}$/.test(phone))) {
            wx.showToast({
                title: '请输入正确的手机号码',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (province == "" || city == "" || county == "") {
            wx.showToast({
                title: '请选择省市区',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (shopAddress == "点击选择") {
            wx.showToast({
                title: '请选择收货地址',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (addressDetail == "" || addressDetail == null) {
            wx.showToast({
                title: '门牌号不能为空',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (user_name.length > 50) {
            wx.showToast({
                title: '联系人姓名不能超过50个字符',
                icon: 'none',
                duration: 2000
            })
            return;
        }

        wx.request({
            url: app.globalData.appUrl + '/api/cater/getUserInfo/addAddress',
            data: {
                admin_id: app.globalData.admin_id,
                user_id: that.data.user_id,
                address_id: address_id,
                province: province,
                city: city,
                country: county,
                address: shopAddress,
                house_number: addressDetail,
                user_name: user_name,
                phone: phone,
                is_default: that.data.is_checked
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                console.log(res)
                if (res.data.errcode > 0) {
                    wx.showToast({
                        title: '成功',
                        icon: 'success',
                        duration: 5000,
                        success: function () {
                            console.log(that.data.pay_type)
                            if (that.data.pay_type == 1) {
                                wx.setStorageSync('address_id', res.data.data);

                                wx.navigateBack({
                                    delta: 2
                                })
                            } else {
                                wx.redirectTo({
                                    url: '../useroperation/useroperation?operation=address',
                                })
                            }
                        }
                    })
                } else {
                    wx.showToast({
                        title: '您还没修改任何数据',
                        icon: 'none',
                        duration: 3000,
                        success: function () {
                        }
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
                    that.setData({userinfo_box: false});
                },
            })
            return;
        }
        app.getUserInfo(e, that.onLoad, this);
    },
})
