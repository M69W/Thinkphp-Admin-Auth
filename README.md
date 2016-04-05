# Thinkphp-Admin-Auth
----------------------1.1.0------------------
1.去除redis支持，因为陪着起来比较麻烦，直接开启session就可以使用
2.去除子域名部署模式
  1.直接将域名解析到项目目录就可以使用
3.ningx 配置,如果是apache，请自行陪着

  server{
      listen 80;
      #listen [::]:80;
      server_name admin.com www.admin.com;
      index index.html index.htm index.php default.html default.htm default.php;
      root  /Test/Admin/Program;

      include none.conf;
      error_page   404 = /404.html;
      location ~ [^/]\.php(/|$)
              {
                      #comment try_files $uri =404; to enable pathinfo
                      try_files $uri =404;
                      fastcgi_pass  unix:/tmp/php-cgi.sock;
                      fastcgi_index index.php;
                      include fastcgi.conf;
                      #fastcgi_param PHP_VALUE "session.name = codeclassroom_session";
                      #fastcgi_param PHP_VALUE "session.cookie_domain =.codeclassroom.com";
                      #include pathinfo.conf;
              }
      location / {
              if (!-e $request_filename) {
            	        rewrite ^(.*) /index.php?s=$1 last;
            	        break;
        	   	}
             autoindex on;
      }
      }
  
4. Mysql




----------------------1.0.0------------------
基于Thinkphp的站点通用管理员后台,基于Auth验证
1.需要redis支持
  1.1 session需要redis支持
2.需要解析两个域名到项目上面
  2.1 解析到项目目录website
  2.2 解析到资源目录statics
  2.3 需要在配置文件里面配置资源目录路径
3. 数据库表在mysql文件夹下面
4. nginx配置在nginx目录下面
