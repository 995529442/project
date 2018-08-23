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
        currency_pay_box: false,  //购物币支付框
        goods: [],
        allMoney: 0,
        cater_type: app.globalData.cater_type,
        shop_info: {},
        user_id: 0,
        default_address: {},
        goods_id_arr: '',
        is_locked_pay: 0,
        pay_type: -1,      //支付方式 0微信支付 ，1购物币支付
        is_open_currency: 0  //是否开启购物币支付
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
        var goods_id_arr = typeof (option.goods_id_arr) == 'undefined' ? '' : option.goods_id_arr;
        var cater_type = that.data.cater_type

        if (goods_id_arr != "") {
            wx.setStorageSync('goods_id_arr', goods_id_arr)
            that.setData({
                goods_id_arr: goods_id_arr
            })
        } else if (wx.getStorageSync('goods_id_arr')) {
            goods_id_arr = wx.getStorageSync('goods_id_arr');
            that.setData({
                goods_id_arr: goods_id_arr
            })
        }

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
                        allMoney: parseFloat(res.data.total_money)
                    })
                }
            }
        })

        that.get_shop_info(); //获取店铺信息

        if (cater_type == 2) { //外卖。收货地址
            that.get_default_address();
        }
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onShow: function onShow() {
        var that = this;
        var address_id = wx.getStorageSync('address_id');

        if (address_id > 0) { //获取选取的地址
            wx.request({
                url: app.globalData.appUrl + '/api/cater/getUserInfo/getOneAddress',
                data: {
                    address_id: address_id,
                },
                header: {
                    'content-type': 'application/json'
                },
                success: function (res) {
                    console.log(res)
                    if (res.data) {
                        that.setData({
                            default_address: res.data
                        })
                    }
                }
            })
        }
    },
    /**
     * 卸载页面
     * 清除缓存
     */
    onUnload: function onUnload() {
        wx.removeStorageSync('address_id')
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
                    var is_open_currency = res.data.is_open_currency
                    that.setData({
                        shop_info: res.data,
                        is_open_currency: is_open_currency
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
                if (res.data.errcode > 0) {
                    that.setData({
                        default_address: res.data.data
                    })
                }
            }
        })
    },
    /**
     * 选择支付方式
     */
    select_pay_type: function (e) {
        var that = this;
        var is_open_currency = that.data.is_open_currency;

        if (is_open_currency == 1) {
            var show_pay_type = ['微信支付', '购物币支付'];
        } else {
            var show_pay_type = ['微信支付'];
        }
        wx.showActionSheet({
            itemList: show_pay_type,
            success: function (res) {
                that.setData({
                    pay_type: res.tapIndex
                })
            },
            fail: function (res) {
                console.log(res.errMsg)
            }
        })
    },
    /**
     * 关闭购物币支付
     */
    cancel_currency: function (e) {
        var that = this;
        that.setData({
            currency_pay_box: false,
            is_locked_pay: 0
        })
    },
    /**
     * 付款
     */
    formSubmit: function (e) {
        var that = this;
        var goods_id_arr = that.data.goods_id_arr;
        var default_address = that.data.default_address;
        var formId = e.detail.formId;
        var pay_type = that.data.pay_type;
        var currency_pay_box = that.data.currency_pay_box;
        var currency_password = typeof (e.detail.value.currency_password) == 'undefined' ? '' : e.detail.value.currency_password;
        var remark = typeof (e.detail.value.remark) == 'undefined' ? '' : e.detail.value.remark;

        if (pay_type == 1 && !currency_pay_box) {  //购物币支付
            that.setData({
                currency_pay_box: true
            });
            return;
        }
        if (pay_type == 1 && currency_pay_box && currency_password == "") {
            wx.showToast({
                title: '请输入支付密码',
                icon: 'none',
                duration: 3000
            })
            return;
        }
        if (default_address == '') {
            wx.showToast({
                title: '请选择收货信息',
                icon: 'none',
                duration: 3000
            })
            return;
        }
        if (pay_type == -1) {
            wx.showToast({
                title: '请选择支付方式',
                icon: 'none',
                duration: 3000
            })
            return;
        }
        if (pay_type == 0) {
            wx.showToast({
                title: '微信支付暂时未开通，请选择购物币支付',
                icon: 'none',
                duration: 3000
            })
            return;
        }
        that.setData({
            is_locked_pay: 1
        })
        wx.showLoading({
            title: '支付中',
        })
        wx.request({
            url: app.globalData.appUrl + '/api/cater/order/pay',
            data: {
                admin_id: app.globalData.admin_id,
                user_id: that.data.user_id,
                goods_id_arr: goods_id_arr,
                user_name: default_address.user_name,
                phone: default_address.phone,
                cater_type: app.globalData.cater_type,
                formId: formId,
                remark: remark,
                pay_type: pay_type,
                currency_password: currency_password
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                console.log(res)
                wx.hideLoading();
                if (res.data.errocde > 0) {
                    //删除购物车缓存
                    wx.showModal({
                        title: '提示',
                        content: '支付成功',
                        success: function (res) {
                            wx.removeStorageSync('goods_id_arr');
                            wx.removeStorageSync('chooseGoods');
                            if (res.confirm) {
                                wx.redirectTo({
                                    url: '../useroperation/useroperation?operation=order&currentCouponTab=' + app.globalData.cater_type,
                                })
                            } else if (res.cancel) {
                                wx.redirectTo({
                                    url: '../useroperation/useroperation?operation=order&currentCouponTab=' + app.globalData.cater_type,
                                })
                            }
                        }
                    })
                } else if (res.data.errcode == -2) {
                    wx.showModal({
                        title: '提示',
                        content: res.data.errmsg,
                        confirmText: '前往设置',
                        success: function (res) {
                            if (res.confirm) {
                                wx.navigateTo({
                                    url: '../useroperation/useroperation?operation=password&pay_type=1',
                                })
                            } else if (res.cancel) {

                            }
                        }
                    })
                } else {
                    wx.showToast({
                        title: res.data.errmsg,
                        icon: 'none',
                        duration: 2000
                    })
                }

                that.setData({
                    is_locked_pay: 0
                })
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
});

