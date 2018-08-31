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
        orderNumber: ['点餐', '外卖'],
        is_set_password: 0,  //是否已设置密码
        code_phone: "",   //验证手机号码
        code_text: "验证码",
        disabled: false,
        currentTime: 181,
        change_type: 2  //修改密码方式 1密码修改，2验证码修改
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
            operation = '购物币消费记录';

            that.get_my_currency();
        } else if (operation === 'integral') {
            operation = '积分兑换';
        } else if (operation === 'order') {
            operation = '我的订单';

            that.get_my_orders();
        } else if (operation === 'password') {
            operation = '修改支付密码';

            that.get_users_info();
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
        } else if (operation == "currency") {
            that.get_my_currency();
        } else if (operation == "password") {
            that.get_currency();
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
    /**
     * 获取用户信息
     */
    get_users_info: function () {
        var that = this;

        wx.showLoading({
            title: '加载中',
        })
        wx.request({
            url: app.globalData.appUrl + '/api/cater/getUserInfo/getOneUsersSetPassword',
            data: {
                admin_id: app.globalData.admin_id,
                user_id: that.data.user_id,
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                wx.hideLoading()
                if (res.data.is_set_password) {
                    that.setData({
                        is_set_password: 1
                    })
                }
            }
        })
    },
    //验证手机号码
    setPhone: function (e) {
        var code_phone = e.detail.value;
        this.setData({
            code_phone: code_phone
        })
    },
    // 获取验证码
    getCode: function () {
        var that = this;
        var code_phone = that.data.code_phone;
        var currentTime = that.data.currentTime;

        if (code_phone == "") {
            wx.showToast({
                title: '手机号码不能为空',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
        if (!myreg.test(code_phone)) {
            wx.showToast({
                title: '请输入正确的手机号码',
                icon: 'none',
                duration: 2000
            })
            return;
        }

        wx.showLoading({
            title: '获取中',
        })
        wx.request({
            url: app.globalData.appUrl + '/api/cater/getShop/getCode',
            data: {
                admin_id: app.globalData.admin_id,
                user_id: that.data.user_id,
                phone: code_phone
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                wx.hideLoading()
                if (res.data.errcode > 0) {
                    that.setData({
                        disabled: true
                    })
                    var interval = setInterval(function () {
                        currentTime--;
                        that.setData({
                            code_text: currentTime + '秒'
                        })
                        if (currentTime <= 0) {
                            clearInterval(interval)
                            that.setData({
                                code_text: '重新发送',
                                currentTime: 181,
                                disabled: false
                            })
                        }
                    }, 1000)
                    wx.showToast({
                        title: '发送成功',
                        icon: 'success',
                        duration: 2000
                    })
                } else {
                    wx.showToast({
                        title: '发送失败',
                        icon: 'none',
                        duration: 2000
                    })
                }
            }
        })
    },
    /**
     * 保存密码设置信息
     */
    formSubmit: function (e) {
        console.log(e)
        var that = this;
        var old_currency_password = typeof (e.detail.value.old_currency_password) == 'undefined' ? '' : e.detail.value.old_currency_password;
        var code = e.detail.value.code;
        var currency_password = e.detail.value.currency_password;
        var phone = e.detail.value.phone;
        var re_currency_password = e.detail.value.re_currency_password;
        var is_set_password = that.data.is_set_password;
        var pay_type = that.data.pay_type;
        var change_type = that.data.change_type;

        if (is_set_password == 1 && old_currency_password == "" && change_type == 1) {
            wx.showToast({
                title: '请输入原密码',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (currency_password == "") {
            wx.showToast({
                title: '请输入密码',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (re_currency_password == "") {
            wx.showToast({
                title: '请输入确认密码',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (currency_password != re_currency_password) {
            wx.showToast({
                title: '两次密码输入不一致',
                icon: 'none',
                duration: 2000
            })
            return;
        }
        if (phone == "" && change_type == 2) {
            wx.showToast({
                title: '请输入手机号码',
                icon: 'none',
                duration: 2000
            })
            return;
        }

        var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
        if (!myreg.test(phone) && change_type == 2) {
            wx.showToast({
                title: '请输入正确的手机号码',
                icon: 'none',
                duration: 2000
            })
            return;
        }

        if (code == "" && change_type == 2) {
            wx.showToast({
                title: '请输入验证码',
                icon: 'none',
                duration: 2000
            })
            return;
        }

        wx.showLoading({
            title: '处理中',
        })
        wx.request({
            url: app.globalData.appUrl + '/api/cater/getShop/savePassword',
            data: {
                user_id: that.data.user_id,
                code: code,
                currency_password: currency_password,
                re_currency_password: re_currency_password,
                phone: phone,
                old_currency_password: old_currency_password,
                change_type: change_type
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                wx.hideLoading()
                if (res.data.errcode > 0) {
                    wx.showModal({
                        title: '提示',
                        content: '设置成功',
                        success: function (res) {
                            if (res.confirm) {
                                wx.navigateBack({
                                    delta: 1
                                })
                            } else if (res.cancel) {
                                wx.navigateBack({
                                    delta: 1
                                })
                            }
                        }
                    })
                } else {
                    wx.showModal({
                        title: '提示',
                        content: res.data.errmsg,
                        success: function (res) {
                            if (res.confirm) {

                            } else if (res.cancel) {

                            }
                        }
                    })
                }
            }
        })
    },
    /**
     * 订单操作
     */
    operate: function (e) {
        var that = this;
        var order_id = e.currentTarget.dataset.order_id;
        var type = e.currentTarget.dataset.type;

        var msg = "";
        var success_msg = "";
        if (type == 'refund') {
            msg = "确定要申请退款吗";
            success_msg = "成功,请等待商家处理";
        } else if (type == 'done') {
            msg = "确定要完成该订单吗";
            success_msg = "成功,该订单已完成";
        } else if (type == 'cancel') {
            msg = "确定要取消该订单吗";
            success_msg = "成功,该订单已取消";
        }

        wx.showModal({
            title: '提示',
            content: msg,
            success: function (res) {
                if (res.confirm) {
                    wx.request({
                        url: app.globalData.appUrl + '/api/cater/order/operate',
                        data: {
                            order_id: order_id,
                            type: type
                        },
                        header: {
                            'content-type': 'application/json'
                        },
                        success: function (res) {
                            wx.hideLoading()
                            if (res.data.errcode) {
                                wx.showModal({
                                    title: '提示',
                                    content: success_msg,
                                    success: function (res) {
                                        if (res.confirm) {
                                            wx.redirectTo({
                                                url: '../useroperation/useroperation?operation=order&currentCouponTab=' + (that.data.currentCouponTab + 1),
                                            })
                                        } else if (res.cancel) {
                                            wx.redirectTo({
                                                url: '../useroperation/useroperation?operation=order$currentCouponTab=' + (that.data.currentCouponTab + 1),
                                            })
                                        }
                                    }
                                })
                            }
                        }
                    })
                } else if (res.cancel) {
                    console.log('用户点击取消')
                }
            }
        })

    },
    /**
     * 选取修改密码方式
     */
    radioChange: function (e) {
        var that = this;
        var change_type = e.detail.value;

        that.setData({
            change_type: change_type
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
