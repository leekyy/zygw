// 接口部分
//基本的ajax访问后端接口类
function ajaxRequest(url, param, method, callBack) {
    console.log("url:" + url + " method:" + method + " param:" + JSON.stringify(param));
    $.ajax({
        type: method,  //提交方式
        url: url,//路径
        data: param,//数据，这里使用的是Json格式进行传输
        contentType: "application/json", //必须有
        dataType: "json",
        success: function (ret) {//返回数据根据结果进行相应的处理
            console.log("ret:" + JSON.stringify(ret));
            callBack(ret)
        },
        error: function (err) {
            console.log(JSON.stringify(err));
            console.log("responseText:" + err.responseText);
            callBack(err)
        }
    });
}

//根据id获取轮播图信息
function getADById(url, param, callBack) {
    ajaxRequest(url + "api/ad/getADById", param, "GET", callBack);
}

//编辑轮播图
function editAD(url, param, callBack) {
    ajaxRequest(url + "admin/ad/editAD", param, "post", callBack);
}

//根据id获取楼盘信息
function getHouseById(url, param, callBack) {
    ajaxRequest(url + "admin/house/getById", param, "GET", callBack);
}

//根据id获取房源信息
function getHuxingById(url, param, callBack) {
    ajaxRequest(url + "admin/huxing/getById", param, "GET", callBack);
}

//根据id获取户型样式详情
function getHuxingStyleById(url, param, callBack) {
    ajaxRequest(url + "admin/huxingStyle/getById", param, "GET", callBack);
}

//根据id获取房源信息
function getHouseDetailById(url, param, callBack) {
    ajaxRequest(url + "admin/house/getHouseDetailById", param, "GET", callBack);
}

//根据id获取顾问信息
function getZYGWById(url, param, callBack) {
    ajaxRequest(url + "admin/zygw/getById", param, "GET", callBack);
}

//根据id获取商品信息
function getGoodsById(url, param, callBack) {
    ajaxRequest(url + "admin/goods/getById", param, "GET", callBack);
}

//根据id获取订单信息
function getGoodsExchangeById(url, param, callBack) {
    ajaxRequest(url + "admin/goodsexchange/getById", param, "GET", callBack);
}
//根据id获取管理员信息
function getAdminById(url, param, callBack) {
    ajaxRequest(url + "admin/admin/getById", param, "GET", callBack);
}

//重置管理员密码
function resetPassword(url, param, callBack) {
    ajaxRequest(url + "admin/admin/resetPassword", param, "GET", callBack);
}

//测试接口
function test(url, param, callBack) {
    ajaxRequest(url + "api/test", param, "post", callBack);
}

//获取签到总体信息
function getQDRecentDatas(url, param, callBack) {
    ajaxRequest(url + "admin/userQD/getRecentDatas", param, "GET", callBack);
}

//获取签到总体信息
function getDDRecentDatas(url, param, callBack) {
    ajaxRequest(url + "admin/goodsexchange/getRecentDatas", param, "GET", callBack);
}
//获取楼盘信息
function getLPRecentDatas(url, param, callBack) {
    ajaxRequest(url + "admin/house/getRecentDatas", param, "GET", callBack);
}


//根据id获取图文
function getInfoById(url, param, callBack) {
    ajaxRequest(url + "api/tw/getInfoById", param, "get", callBack);
}


//编辑图文
function editTW(url, param, callBack) {
    ajaxRequest(url + "admin/tw/editTW", param, "post", callBack);
}

//根据id获取积分兑换信息
function getRuleById(url, param, callBack) {
    ajaxRequest(url + "admin/rule/getById", param, "GET", callBack);
}

//获取系统设置信息
function getSystemInfo(url, param, callBack) {
    ajaxRequest(url + "admin/system/edit", param, "GET", callBack);
}
//获取楼盘标签信息
function getHouseLabelInfo(url, param, callBack) {
    ajaxRequest(url + "admin/houseLabel/getById", param, "GET", callBack);
}

//获取楼盘类型信息
function getHouseTypeInfo(url, param, callBack) {
    ajaxRequest(url + "admin/houseType/getById", param, "GET", callBack);
}

//修改管理员密码
function changeAdminPasswordPost(url, param, callBack) {
    ajaxRequest(url + "admin/admin/changePassword", param, "POST", callBack);
}


/*
 * 校验手机号js
 *
 * By TerryQi
 */

function isPoneAvailable(phone_num) {
    var myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
    if (!myreg.test(phone_num)) {
        return false;
    } else {
        return true;
    }
}

// 判断参数是否为空
function judgeIsNullStr(val) {
    if (val == null || val == "" || val == undefined || val == "未设置") {
        return true
    }
    return false
}

// 判断参数是否为空
function judgeIsAnyNullStr() {
    if (arguments.length > 0) {
        for (var i = 0; i < arguments.length; i++) {
            if (!isArray(arguments[i])) {
                if (arguments[i] == null || arguments[i] == "" || arguments[i] == undefined || arguments[i] == "未设置" || arguments[i] == "undefined") {
                    return true
                }
            }
        }
    }
    return false
}

// 判断数组时候为空, 服务于 judgeIsAnyNullStr 方法
function isArray(object) {
    return Object.prototype.toString.call(object) == '[object Array]';
}


// 七牛云图片裁剪
function qiniuUrlTool(img_url, type) {
    //如果不是七牛的头像，则直接返回图片
    //consoledebug.log("img_url:" + img_url + " indexOf('isart.me'):" + img_url.indexOf('isart.me'));
    if (img_url.indexOf('7xku37.com') < 0 && img_url.indexOf('isart.me') < 0) {
        return img_url;
    }
    //七牛链接
    var qn_img_url;
    const size_w_500_h_200 = '?imageView2/2/w/500/h/200/interlace/1/q/75|imageslim'
    const size_w_200_h_200 = '?imageView2/2/w/200/h/200/interlace/1/q/75|imageslim'
    const size_w_500_h_300 = '?imageView2/2/w/500/h/300/interlace/1/q/75|imageslim'
    const size_w_500_h_250 = '?imageView2/2/w/500/h/250/interlace/1/q/75|imageslim'

    const size_w_500 = '?imageView1/1/w/500/interlace/1/q/75'

    //除去参数
    if (img_url.indexOf("?") >= 0) {
        img_url = img_url.split('?')[0]
    }
    //封装七牛链接
    switch (type) {
        case "ad":  //广告图片
            qn_img_url = img_url + size_w_500_h_300
            break
        case "folder_list":  //作品列表图片样式
            qn_img_url = img_url + size_w_500_h_200
            break
        case  'head_icon':      //头像信息
            qn_img_url = img_url + size_w_200_h_200
            break
        case  'work_detail':      //作品详情的图片信息
            qn_img_url = img_url + size_w_500
            break
        default:
            qn_img_url = img_url
            break
    }
    return qn_img_url
}


// 文字转html，主要是进行换行转换
function Text2Html(str) {
    if (str == null) {
        return "";
    } else if (str.length == 0) {
        return "";
    }
    str = str.replace(/\r\n/g, "<br>")
    str = str.replace(/\n/g, "<br>");
    return str;
}

//null变为空str
function nullToEmptyStr(str) {
    if (judgeIsNullStr(str)) {
        str = "";
    }
    return str;
}


/*
 * 用于对象克隆
 *
 * obj 对象，返回克隆对象
 *
 */
function clone(obj) {
    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        var copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        var copy = [];
        for (var i = 0, len = obj.length; i < len; ++i) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        var copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}


/*
 * 获取url中get的参数
 *
 * By TerryQi
 *
 * 2017-12-23
 *
 */
function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}


//获取时间基线类型的字符串
function getBtimeTypeStr(btime_type) {
    switch (btime_type) {
        case "0":
            return "手术后";
        case "1":
            return "首次弯腿后";
        case "2":
            return "指定日期";
    }
    return "";
}

//获取康复计划状态字符串
function getJHStatus(status) {
    switch (status) {
        case "0":
            return "计划执行";
        case "1":
            return "执行中";
        case "2":
            return "已执行";
    }
}

//获取时间基线单位
function getTimeUnitStr(unit) {
    switch (unit) {
        case "0":
            return "天";
        case "1":
            return "周";
        case "2":
            return "月";
    }
}

function regular(type, value) {
    switch (type) {
        // 手机号码
        case 'phone_num':
            var reg = new RegExp("^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\\d{8}$")
            return reg.test(value) ? true : '请填写正确的手机号码格式'
            break
        // 数字
        case 'number':
            var reg = new RegExp("^\\d+\.?\\d+$")
            return reg.test(value) ? true : '请填写正确的数字格式'
            break
        // 身份证号码
        case 'id_card':
            var reg = new RegExp("^(\\d{6})(\\d{4})(\\d{2})(\\d{2})(\\d{3})([0-9]|X)$")
            return reg.test(value) ? true : '请填写正确的身份证号码格式'
            break
        // 日期
        case 'date':
            var reg = new RegExp("^\\d{4}-\\d{1,2}-\\d{1,2}$")
            return reg.test(value) ? true : '请填写正确的日期格式'
            break
        // 时间
        case 'time':
            var reg = new RegExp("^(20|21|22|23|[0-1]\\d):[0-5]d:[0-5]\\d$")
            return reg.test(value) ? true : '请填写正确的时间格式'
            break
        // 日期时间
        case 'date_time':
            var reg = new RegExp("^[1-9]\\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])s+(20|21|22|23|[0-1]\\d):[0-5]d:[0-5]\\d$")
            return reg.test(value) ? true : '请填写正确的日期及时间格式'
            break
        // 验证码
        case 'verify_code':
            var reg = new RegExp("^\\d{4}$")
            return reg.test(value) ? true : '请填写正确的验证码格式'
            break
        // 邮政编码
        case 'post_code':
            var reg = new RegExp("^[1-9][0-9]{5}$")
            return reg.test(value) ? true : '请填写正确的验证码格式'
            break
        // 弱密码
        case 'weak_password':
            var reg = new RegExp("^\\w{6,15}$")
            return reg.test(value) ? true : '密码长度在6~15之间，只能包含字母、数字和下划线'
            break
        // 强密码
        case 'strong_password':
            var reg = new RegExp("^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).{8,10}$")
            return reg.test(value) ? true : '密码必须包含大小写字母和数字的组合，不能使用特殊字符，长度在8-10之间'
            break
        // 电子邮箱
        case 'e-mail':
            var reg = new RegExp("^\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$")
            return reg.test(value) ? true : '请填写正确的Email地址格式'
            break
        default:
            return true
    }
}