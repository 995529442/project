// 获取全局应用程序实例对象
var app = getApp();

// 创建页面实例对象
Page({
    /**
     * 页面的初始数据
     */
    data: {
        userinfo_box: false,
        // navList: [
        //   {
        //     navTitle: '点餐',
        //     navIcon: 'iconfont icon-canshi:before'
        //   }, {
        //     navTitle: '外卖',
        //     navIcon: 'iconfont icon-mifen2:before'
        //   }],
        goods: [],//显示菜品
        active_index: 1,
        imgUrls: [],
        url: app.globalData.appUrl,
        shop_info: {}
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

        wx.getLocation({
            type: 'wgs84',
            success: function (res) {
                var latitude = res.latitude
                var longitude = res.longitude

                wx.setStorageSync('latitude', latitude);
                wx.setStorageSync('longitude', longitude);
            }
        })

        wx.showLoading({
            title: '加载中',
        })

        that.getHomeImg();//首页轮播图
        that.getShop();//商家信息
        that.getGoods("hot");//首页菜品
    },
    //选取表头,获取数据
    selectGoods: function (e) {
        var that = this;
        var select_type = e.currentTarget.dataset.type;
        var active_index = that.data.active_index;

        if (select_type == "hot") {
            active_index = 1;
        } else if (select_type == "rec") {
            active_index = 2;
        } else if (select_type == "new") {
            active_index = 3;
        }

        that.setData({
            active_index: active_index
        })

        that.getGoods(select_type);
    },
    getHomeImg: function () {
        var that = this;

        wx.request({
            url: app.globalData.appUrl + '/api/cater/getShop/getHomeImg',
            data: {
                admin_id: app.globalData.admin_id,
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                if (res.data) {
                    var home_img = res.data;
                    var imgUrls = new Array();
                    for (var k = 0; k < home_img.length; k++) {
                        imgUrls.push(that.data.url + home_img[k]['img_path']);
                    }
                    that.setData({
                        imgUrls: imgUrls
                    })
                }
            }
        })
    },
    //获取首页菜品
    getGoods: function (select_type) {
        var that = this;

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
                wx.hideLoading();
                if (res.data) {
                    that.setData({
                        goods: res.data
                    })
                }
            }
        })
    },
    /**
     * 获取商家信息
     */
    getShop: function getShop() {
        var that = this;
        wx.request({
            url: app.globalData.appUrl + '/api/cater/getShop/getShopInfo',
            data: {
                admin_id: app.globalData.admin_id
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                if (res.data) {
                    var shop_info = res.data;

                    if (shop_info.introduce.length > 30) {
                        shop_info.introduce = shop_info.introduce.substring(0, 30) + "...";
                    }
                    that.setData({
                        shop_info: shop_info
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
    /**
     * 打开位置
     */
    openLocation: function openLocation(e) {
        var latitude = parseInt(e.currentTarget.dataset.latitude);
        var longitude = parseInt(e.currentTarget.dataset.longitude);
        wx.openLocation({
            latitude: latitude,
            longitude: longitude,
            scale: 28
        })
    },
    /**
     * 拨打电话
     */
    callPhone: function callPhone(e) {
        var phone = e.currentTarget.dataset.phone;

        wx.makePhoneCall({
            phoneNumber: phone
        });
    }
});
