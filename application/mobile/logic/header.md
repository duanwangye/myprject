***
>## 介绍
* 介绍

***
#
>## <font color=#0088cc size=5>请求规则</font>
* 测试根地址http://wxapp.dahengdian.com/mobile/
* 生产根地址 ?????
* 根地址请移动端配置好，方便后期发展其他版本
* 接口完成地址拼接方法，http://wxapp.dahengdian.com/mobile/ + cang/getCangList，既为http://wxapp.dahengdian.com/mobile/cang/getCangList，其中http://wxapp.dahengdian.com/mobile/为根地址，<font color=#FF0000 size=3>cang/getCangList为接口标志</font>
* post请求，json数据


#
>## <font color=#0088cc size=5>请求参数</font>
#### 公共参数
字段 | 类型 | 描述
----|------|----
token | String  | 登录凭证，32位MD5加密字符串，保质期7 * 86400（七天）
time | String  | 请求时间，格式为2017-01-01 12:12:13
appV | String  | APP版本
apiV | String  | API版本
osV | String  | 操作系统版本
osType | Number | 1为ios，2为android，3为pc
ip | String | ipv4
channel | String | 渠道标志符
deviceID | String | 宿主app的唯一标识，长度不限
sign | String  | 32位MD5加密字符串


###
#### 应用参数
字段 | 类型 | 描述
----|------|----
app | String  | 应用数据
```json
{
    "token": "3e9183e7caa30617c833f06ca86d4134", 
    "time": "2017-12-20 12:52:12", 
    "appV": "1.0.2",
    "apiV": "1.0.2",
    "osV": "Android 6.02",
    "osType": 2,
    "ip": "192.168.0.1",
    "deviceID": "ioajdjfalkslkdiapaa65a4ds54fa5sd1f",
    "channel": "asdfasdfasdf",
    "sign": "9ca2d3bf6ce8936c1e8ecab9ae40c6bf",
    "app": {
      "status": 1
    }
}
```
#
>## <font color=#0088cc size=5>返回参数</font>
#### 公共参数
字段 | 类型 | 描述
----|------|----
time | String  | 请求时间，格式为2017-01-01 12:12:13
sign | String  | 签名，32位MD5加密字符串

###
#### 应用参数  
字段 | 类型 | 描述
----|------|----
app | String  | 应用数据json格式化字符串，app为json对象，包含code，msg，content三个字段，其中code为错误标识，1为操作成功，负数代表错误；msg错误说明；content为业务数据
```json
{
    "app": {
        "code": 1,
        "msg": "操作成功",
        "content": {
            "cangID": "6"
        }
    },
    "time": "2017-12-20 12:52:12",
    "sign": "4297f44b13955235245b2497399d7a93"
}
``` 
#
>## <font color=#0088cc size=5>sign签名规则</font>
* 请求sign = md5(key + token + time + appV +apiV + osV + osType + deviceID + ip + channel + app)，其中登录前状态所有与服务器交互，token为空字符串
* 返回sign = md5(key + time + app)
* sign 签名为小写

