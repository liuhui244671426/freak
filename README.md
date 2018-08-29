### 简介
--------
 Freak 是一个简单的 PHP 框架.
- - - - --
### 目录介绍
---------
-   config 配置目录
    - nginx.conf 安装的配置文件,安装后需要删除
-   fpm web业务接口
    - sso 单点登录服务版本
    - admin 后台
-   freak 框架目录,不建议修改
    - bootstrap.php 启动器
    - monitor.php 脚本守护进程
-   daemon 脚本
    -   workers 业务脚本
-   data 数据层
-   lib 第三方库
    - captcha.php 验证码
    - encrypt.php 隐写术
    - filter.php 参数过滤
    - gif.php gif 处理库
    - helper.php 小助手
    - lunar.php 阴历
    - openssl.php 加密
    - pinyin.php 中文转拼音
    - session.php session管理
    - ssoClient.php 单点登录客户版本
    - upload.php 文件上传
    - xss.php xss攻击检测
-   logs 日志
-   model 模型层
-   public 静态资源公开目录
-   views html模板目录
-   index.php web业务入口
-   debug.php web 业务入口的debug版本
-   README.md
-   composer.json
 - - - - --
### config介绍
1.config 配置环境均分为product和develop,如果需要切换环境,修改bootstrap.php文件
```php
$_SERVER['ENV_CONFIG'] = 'develop';//框架默认
or
$_SERVER['ENV_CONFIG'] = 'product';
```
即可
2.获取配置内容
```php
freak_config::get('文件名','字段名');//如果字段名是空,则会获取一个完整配置信息
```
例子:
```php
freak_config::get('pdo', 'read');//获取 config/pdo.php 下的 'read' 配置信息
```
### 入口文件
通过apache 或 nginx定义入口文件,nginx 可以参考 config/nginx.conf
### 路由
只支持一种路由
```php
rule : module->controller->action
```
例子:
```php    
m=index?c=hello&a=world
//fpm/index/hello.php 里面的 function world()
```
### fpm
所有 web 访问的接口或页面,都要放在 fpm 目录里

### 文件加载规则
框架已有自动加载功能,适用于所有文件,您在使用时无需担心文件加载问题.只需按照规则命名文件名及类名.**类名需要是根目录为起始目录完整的文件路径且已 _ 分隔目录层级**.比如,调用 lib/cookie.php 文件的get方法.
- 类名必须是 **class lib_cookie**
- 通过 **lib_cookie:: get('xxx');**调用
再比如,调用 freak/pdo.php 的 query 方法
- 类名必须是 **class freak_pdo**
- 通过 **new freak_pdo()**调用