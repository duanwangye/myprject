***
>## 介绍
* 介绍

***
#
>## <font color=#0088cc size=5>请求规则</font>
* 测试根地址http://wxapp.dahengdian.com/master/
* 生产根地址 ?????
* 根地址请移动端配置好，方便后期发展其他版本
* 接口完成地址拼接方法，http://wxapp.dahengdian.com/master/ + cang/getCangList，既为http://wxapp.dahengdian.com/master/cang/getCangList，其中http://wxapp.dahengdian.com/master/为根地址，<font color=#FF0000 size=3>cang/getCangList为接口标志</font>
* post请求，json数据


#
>## <font color=#0088cc size=5>请求参数</font>
#### 公共参数
字段 | 类型 | 描述
----|------|----
token | String  | 登录凭证，32位MD5加密字符串，保质期7 * 86400（七天），登录时token为空字符串


###
#### 应用参数
字段 | 类型 | 描述
----|------|----
app | String  | 应用数据
```json
{
    "token": "3e9183e7caa30617c833f06ca86d4134", 
    "app": {
      "status": 1
    }
}
```
#
>## <font color=#0088cc size=5>返回参数</font>
#### 公共参数（无）


###
#### 应用参数  
字段 | 类型 | 描述
----|------|----
code | int | 标识符
msg | String  | 操作结果提示
content | Object | 应用数据包
```json
{
    "code": 1,
    "msg": "操作成功",
    "content": {
        "cangID": "6"
    }
}
``` 


#
>## <font color=#0088cc size=5>Subject模块产品流程</font>
* A/B/C...为流程操作，比如A操作，B操作....，圆圈表示每个操作后的状态，每个操作对应Subject模块的一个接口
![avatar](http://wxapp.dahengdian.com/upload/liucheng.png)