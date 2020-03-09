### 注册 /register
method: post 

params: 
* name: 昵称
* email: 邮箱
* password: 密码    

response: 

* code: int
* message: string
* token: string 登录成功的口令

### 登录 /login
method: post

params: 
* email: 邮箱
* password: 密码

response:
* code: int
* message: string
* token: string 登录成功的口令

### 登出 /logout
method: post

params:
 * 无

headers:
 * Authorization: Bearer +登录或注册接口返回的token(注意:Bearer和token之间有个空格)
 
response:
 * code: int
 * message: string
 
### 用户资料 /user
method: get

params:
 * 无
 
headers:
 * Authorization: Bearer +登录或注册接口返回的token(注意:Bearer和token之间有个空格)

response:
 * code: int
 * data: object
