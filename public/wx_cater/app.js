//app.js
App({
  globalData: {
    appUrl: "http://www.project.com",  //根路径
    admin_id:1
  },
  onLaunch: function () {
    var that = this;
  },
  //获取用户信息加登陆
  getUserInfo: function (cb, fund, child_this) {
    var that = this;
    child_this.setData({ userinfo_box: false, l_kefu_lon: true, });
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        if (res.code) {
          //发起网络请求
          wx.request({
            url: 'http://www.project.com/api/cater/getUserInfo/getUsers',
            data: {
              code: res.code,
              iv: cb.detail.iv,
              encrypted_data: cb.detail.encryptedData,
              admin_id: 1,
            },
            header: {
              'content-type': 'application/json'
            },
            success: function (res) {
              console.log(res)
              if (res.data) {
                //赋值给全局
                wx.setStorageSync('openId', res.data.openId);
                wx.setStorageSync('avatarurl', res.data.avatarUrl);
                wx.setStorageSync('nickname', res.data.nickName);
              }
              wx.showToast({
                title: '登录中...',
                icon: 'loading',
                mask: true,
                success: function () {
                  // 登录成功后，执行自身函数
                  setTimeout(() => {
                    fund(cb);
                  }, 500);
                },
              })
            }
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    });
    return;
  },
})