@echo off
setlocal enabledelayedexpansion

echo 正在准备将网站推送到GitHub...
echo 当前目录: F:\01-个人网站\linked-pages

cd /d F:\01-个人网站\linked-pages

echo.
echo 步骤1: 初始化Git仓库
git init

echo.
echo 步骤2: 配置Git用户信息
git config user.name "Chopiglat"
git config user.email "chopiglat@example.com"

echo.
echo 步骤3: 添加远程仓库
git remote add origin https://github.com/Chopiglat/Chopiglat.github.io.git

echo.
echo 步骤4: 添加所有文件到暂存区
git add .

echo.
echo 步骤5: 提交更改
git commit -m "更新网站内容 - %date% %time%"

echo.
echo 步骤6: 推送到GitHub
git branch -M main
git push -u origin main --force

echo.
echo 推送完成！
echo 请访问: https://chopiglat.github.io/
pause
