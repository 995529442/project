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
        user_id: 0,
        motto: 'Hello World',
        avatarurl: '',
        nickname: '',
        title: 'user',
        userDetail: [{
            title: '购物币',
            number: 0
        }, {
            title: '优惠券',
            number: '暂无'
        }, {
            title: '积分',
            number: '暂无'
        }],
        userList: [{
            icon: 'iconfont icon-dingdan',
            title: '我的订单',
            id: 'order'
        }, {
            icon: 'iconfont icon-lingdang:before',
            title: '我的地址',
            id: 'address'
        }, {
            icon: 'iconfont icon-lingdang:before',
            title: '支付密码',
            id: 'password'
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
        }
        //用户头像
        if (wx.getStorageSync('avatarurl')) {
            that.setData({
                avatarurl: wx.getStorageSync('avatarurl')
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

        that.get_my_currency(); // 获取我的购物币
    },
    // 我的购物币
    get_my_currency: function () {
        var that = this;

        wx.request({
            url: app.globalData.appUrl + '/api/cater/getUserInfo/getMyCurrency',
            data: {
                user_id: that.data.user_id,
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                var userDetail = that.data.userDetail;

                userDetail[0].number = res.data.currency_money;
                if (res.data) {
                    that.setData({
                        userDetail: userDetail
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
