'use strict';
var app = getApp();
// 获取全局应用程序实例对象
// const app = getApp()

// 创建页面实例对象
Page({
    /**
     * 页面的初始数据
     */
    data: {
        //title: 'ordering',
        menuList: [],
        // 当前的tab
        //currentmenu: 1,
        // 当前的left栏
        currentleftmenu: 0,
        // 侧边栏联动当前值
        currentmenuid: 'list1',
        // 设置scroll-view的高度
        //scrollHeight: 880,
        //needDistance: 0,
        //scrollHeight2: 815,
        showShopCarContent: false,
        showMask: false,
        // menu1content: [{
        //   icon: 'iconfont icon-canshi',
        //   title: '催促上菜'
        // }, {
        //   icon: 'iconfont icon-lingdang-copy',
        //   title: '呼叫服务员'
        // }, {
        //   icon: 'iconfont icon-mifen2',
        //   title: '加米饭'
        // }, {
        //   icon: 'iconfont icon-jiubei',
        //   title: '加酒水'
        // }],
        // comment: [{
        //   username: '186****1234',
        //   img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        //   grade: 'five-star',
        //   time: '2016-5-5',
        //   userComment: ['一二三四', '一', '一二三四', '一二', '一二三', '一二三四']
        // }, {
        //   username: '186****1234',
        //   img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        //   grade: 'one-star',
        //   time: '2016-5-5',
        //   userComment: ['一', '一二', '一二三', '一二三四']
        // }, {
        //   username: '186****1234',
        //   img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        //   grade: 'two-star',
        //   time: '2016-5-5',
        //   userComment: ['一', '一二', '一二三', '一二三四']
        // }, {
        //   username: '186****1234',
        //   img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        //   grade: 'four-star',
        //   time: '2016-5-5',
        //   userComment: ['一二三四', '一', '一二三四', '一二', '一二三', '一二三四']
        // }, {
        //   username: '186****1234',
        //   img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        //   grade: 'three-star',
        //   time: '2016-5-5',
        //   userComment: ['一二三四', '一', '一二三四', '一二', '一二三', '一二三四']
        // }],
        chooseGoods: {
            // 饭店id
            //restaurant_id: 'renmaid',
            // 选择的商品数量
            goods: {},
            // 总金额
            money: 0,
            // 总数
            allCount: 0
        },
        shop_info: {},
        url: app.globalData.appUrl,
        goods_info: {},
        goodsinfo_box: false //是否显示介绍
    },
    /**
     * 确认订单
     */
    goCheckOrder: function goCheckOrder() {
        var that = this;
        if (that.data.chooseGoods.allCount <= 0) {
            return wx.showToast({
                title: '您还没有点餐',
                icon: 'success',
                mask: true
            });
        }

        var goods = that.data.chooseGoods.goods;
        var goods_id_arr = new Array();

        //获取购物车数据
        for (var key in goods) {
            if (goods[key] > 0) {
                var good_obj = new Object();
                good_obj.goods_id = key.split("_")[1];
                good_obj.number = goods[key];

                goods_id_arr.push(good_obj);

            }
        }

        //提交购物车
        console.log(JSON.stringify(goods_id_arr))

        wx.request({
            url: app.globalData.appUrl + '/api/cater/order/checkSubmit',
            data: {
                admin_id: app.globalData.admin_id,
                goods_id_arr: JSON.stringify(goods_id_arr),
                cater_type: app.globalData.cater_type
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                if (res.data.errcode > 0) {
                    wx.navigateTo({
                        url: '../payorder/payorder?goods_id_arr=' + JSON.stringify(goods_id_arr)
                    });
                } else {
                    wx.showToast({
                        title: res.data.errmsg,
                        icon: 'none',
                        duration: 2000
                    })
                    return;
                }
            }
        })
    },

    /**
     * 计算消费金额
     */
    calculateMoney: function calculateMoney() {
        var goods = this.data.chooseGoods.goods;
        var menuList = this.data.menuList;
        var money = 0;
        var singleMoney = 0;
        for (var goodsId in goods) {
            // console.log(goodsId)
            // console.log(goods[goodsId])
            var _iteratorNormalCompletion = true;
            var _didIteratorError = false;
            var _iteratorError = undefined;

            try {
                for (var _iterator = menuList[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                    var lists = _step.value;

                    // console.log(lists)
                    // 具体列表内的菜单
                    var list = lists.list;
                    // console.log(list)
                    var _iteratorNormalCompletion2 = true;
                    var _didIteratorError2 = false;
                    var _iteratorError2 = undefined;

                    try {
                        for (var _iterator2 = list[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
                            var goodsID = _step2.value;

                            if (goodsID.id === goodsId) {
                                // console.log(goodsID.price)
                                // console.log(goods[goodsId])
                                singleMoney = goodsID.price * goods[goodsId];
                                // console.log('success')
                            }
                            // return console.log(goodsID)
                        }
                    } catch (err) {
                        _didIteratorError2 = true;
                        _iteratorError2 = err;
                    } finally {
                        try {
                            if (!_iteratorNormalCompletion2 && _iterator2.return) {
                                _iterator2.return();
                            }
                        } finally {
                            if (_didIteratorError2) {
                                throw _iteratorError2;
                            }
                        }
                    }
                }
            } catch (err) {
                _didIteratorError = true;
                _iteratorError = err;
            } finally {
                try {
                    if (!_iteratorNormalCompletion && _iterator.return) {
                        _iterator.return();
                    }
                } finally {
                    if (_didIteratorError) {
                        throw _iteratorError;
                    }
                }
            }

            money += singleMoney;
        }
        return money;
    },

    /**
     * 显示购物车内容
     */
    showContent: function showContent() {
        if (this.data.chooseGoods.money <= 0) return;
        this.setData({
            showShopCarContent: !this.data.showShopCarContent,
            showMask: !this.data.showMask
        });
    },

    /**
     * 获取优惠券
     * @param e
     */
    getCoupon: function getCoupon(e) {
        wx.showToast({
            title: '领取优惠券',
            icon: 'success',
            duration: 2000,
            mask: true
        });
    },

    /**
     * 设置右侧滚动栏的位置
     */
    // setNeedDistance: function setNeedDistance() {
    //   if (!this.data.restaurant.coupon.id) return;
    //   this.setData({
    //     needDistance: 142
    //   });
    // },

    /**
     * 改变menu选择
     * @param e
     */
    choose: function choose(e) {
        // console.log(e)
        this.setData({
            currentmenu: e.currentTarget.dataset.tab
        });
    },

    /**
     * 改变left menu选择
     * @param e
     */
    leftChoose: function leftChoose(e) {
        this.setData({
            currentleftmenu: e.currentTarget.dataset.menu,
            currentmenuid: e.currentTarget.dataset.menulistid
        });
    },

    /**
     * 选择桌子取号
     */
    // getdesk: function getdesk(e) {
    //   var index = e.currentTarget.dataset.desk;
    //   var title = null;
    //   if (index === '0') {
    //     title = '小桌取号成功';
    //   } else if (index === '1') {
    //     title = '中桌取号成功';
    //   } else {
    //     title = '大桌取号成功';
    //   }
    //   wx.showToast({
    //     title: title,
    //     icon: 'success',
    //     duration: 2000
    //   });
    // },

    /**
     * 户呼叫服务
     * @param e
     */
    menu1choose: function menu1choose(e) {
        console.log(e.currentTarget.dataset.tabmenu);
    },

    /**
     * 拨打电话
     */
    // callPhone: function callPhone() {
    //   wx.makePhoneCall({
    //     phoneNumber: this.data.restaurant.tel
    //   });
    // },

    /**
     * 修改标题栏文字
     */
    // setNavigatorText: function setNavigatorText() {
    //   var that = this;
    //   wx.setNavigationBarTitle({
    //     title: that.data.restaurant.name
    //   });
    // },

    /**
     * 添加商品
     * @param e
     */
    addorder: function addorder(e) {
        var goodsId = e.currentTarget.dataset.goodsid;
        if (!goodsId) {
            return wx.showModal({
                title: '抱歉',
                content: '您选的菜品暂时无法提供',
                showCancel: false,
                confirmText: '我知道了'
            });
        }
        var chooseGoods = this.data.chooseGoods;
        var goods = chooseGoods.goods;
        var count = goods[goodsId];
        // 已有该商品
        if (count) {
            goods[goodsId] = ++count;
        } else {
            goods[goodsId] = 1;
        }
        chooseGoods.goods = goods;
        this.setData({
            chooseGoods: chooseGoods
        });
        var money = this.calculateMoney();
        chooseGoods.money = money;
        // 增加计数
        ++chooseGoods.allCount;
        this.setData({
            chooseGoods: chooseGoods
        });
        console.log(this.data.chooseGoods)
        wx.setStorageSync('chooseGoods', this.data.chooseGoods);
    },

    /**
     * 删除商品
     * @param e
     */
    delorder: function delorder(e) {
        var goodsId = e.currentTarget.dataset.goodsid;
        var chooseGoods = this.data.chooseGoods;
        var goods = chooseGoods.goods;
        var count = goods[goodsId];
        goods[goodsId] = --count;
        chooseGoods.goods = goods;
        this.setData({
            chooseGoods: chooseGoods
        });
        var money = this.calculateMoney();
        chooseGoods.money = money;
        // 减少计数
        --chooseGoods.allCount;
        if (chooseGoods.allCount <= 0) {
            this.setData({
                showMask: false,
                showShopCarContent: false
            });
        }
        this.setData({
            chooseGoods: chooseGoods
        });
        wx.setStorageSync('chooseGoods', this.data.chooseGoods);
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function onLoad(option) {
        var that = this;

        wx.showLoading({
            title: '加载中',
        })
        that.getShop();  //商家信息
        //获取分类菜品
        wx.request({
            url: app.globalData.appUrl + '/api/cater/getGoods/getCatGoods',
            data: {
                admin_id: app.globalData.admin_id,
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                wx.hideLoading();
                if (res) {
                    that.setData({
                        menuList: res.data
                    })
                }
            }
        })
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onShow: function onLoad(option) {
        var that = this;
        if (wx.getStorageSync('chooseGoods')) {
            that.setData({
                chooseGoods: wx.getStorageSync('chooseGoods')
            })
        } else {
            var chooseGoods = new Object();

            chooseGoods.goods = new Object();
            chooseGoods.money = 0;
            chooseGoods.allCount = 0;

            that.setData({
                chooseGoods: chooseGoods
            })
        }

    },
    /**
     * 获取商家信息
     */
    getShop: function getShop() {
        var that = this;
        //获取分类菜品
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
                if (res.data) {
                    that.setData({
                        shop_info: res.data
                    })
                }
            }
        })
    },
    /**
     * 跳转到商家详情
     */
    toShop: function toShop() {
        wx.navigateTo({
            url: '../detail/detail',
        })
    },

    /**
     * 展示商品介绍
     */
    show_intro: function show_intro(e) {
        var that = this;
        var id = e.currentTarget.dataset.id;
        var goods_id = id.split("_")[1];

        wx.request({
            url: app.globalData.appUrl + '/api/cater/getGoods/getOneGoods',
            data: {
                admin_id: app.globalData.admin_id,
                goods_id: goods_id
            },
            header: {
                'content-type': 'application/json'
            },
            success: function (res) {
                if (res.data) {
                    that.setData({
                        goodsinfo_box: !that.data.goodsinfo_box,
                        goods_info: res.data.data
                    });
                }
            }
        })
    },

    showInstro: function showInstro() {
        this.setData({
            goodsinfo_box: !this.data.goodsinfo_box
        });
    }
});
