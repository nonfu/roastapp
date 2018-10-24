# roastapp

本项目是 Laravel 学院 Roast 应用源码，Roast 是一个基于高德地图搜索附件咖啡店的项目，基于 Larave 5.6 + Vue 2 开发，是一个由 API 驱动的前后端分离应用。

### 应用截图

首页：

![Roast首页](https://static.laravelacademy.org/wp-content/uploads/2018/10/0699b21232cd88aef0e705d0512741eb.jpg)

详情页：

![Roast详情页](https://static.laravelacademy.org/wp-content/uploads/2018/10/9ec1f3dc7f78a27a3ba6411497142d6c.jpg)

新增页：

![Roast编辑页](https://static.laravelacademy.org/wp-content/uploads/2018/10/9b2777f086dcb29bc9992fe4ce2d5e03.jpg)

### 使用说明

    git clone https://github.com/nonfu/roastapp.git
    cd roastapp
    composer install
    cp .env.example .env
    php artisan key:generate
    
 配置 `.env` 中的数据库配置，Github 相关配置和高德地图 Web 服务 API KEY，然后运行
    
    php artisan migrate
    php artisan passport:install
    
 执行数据库变更，并初始化 API 认证所需脚手架，后端初始化完成之后，初始化前端资源：
 
    npm install 
    npm remove bootstrap --save-dev
    npm remove bootstrap-sass --save-dev
    npm run dev
 
 配置应用本地域名为 `roast.test`，就可访问应用了。 

### 构建教程

<https://laravelacademy.org/api-driven-development-laravel-vue>
